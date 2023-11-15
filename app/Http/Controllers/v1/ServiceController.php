<?php

namespace App\Http\Controllers\v1;

use App\Enums\CouponType;
use App\Http\Controllers\Controller;
use App\Http\Requests\v1\Service\CalculateRequest;
use App\Http\Requests\v1\Service\PurchaseRequest;
use App\Http\Resources\v1\ServiceCollection;
use App\Http\Resources\v1\ServiceResource;
use App\Mail\Invoice;
use App\Models\Coupon;
use App\Models\Customer;
use App\Models\CustomerHistory;
use App\Models\District;
use App\Models\ReportExplanation;
use App\Models\Service;
use App\Models\Timeslot;
use App\Models\TimeslotQuota;
use Exception;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Subgroup;
use Knuckles\Scribe\Attributes\Unauthenticated;
use Knuckles\Scribe\Attributes\UrlParam;
use Stripe\Checkout\Session;
use Stripe\Exception\SignatureVerificationException;
use Stripe\Stripe;
use Stripe\Webhook;
use UnexpectedValueException;

#[Group('Frontend API')]
#[Subgroup('Service')]
class ServiceController extends Controller
{
    #[Endpoint('Service List')]
    #[Unauthenticated]
    #[UrlParam('category_slug', 'string', 'Category Slug or Id')]
    public function index(string $category_slug)
    {
        $data = Service::enabled()
            ->categorySlugOrId($category_slug)
            ->sequence()
            ->with('category', 'service_descriptions')
            ->get();
        return new ServiceCollection($data);
    }

    #[Endpoint('Service Detail')]
    #[Unauthenticated]
    #[UrlParam('slug', 'string', 'Category Slug or Id')]
    public function show(string $slug)
    {
        $data = Service::enabled()->slugOrId($slug)->with('service_descriptions', 'service_details')->first();
        return new ServiceResource($data);
    }

    #[Endpoint('Purchase')]
    #[Unauthenticated]
    public function purchase(PurchaseRequest $request)
    {
        $validated = $request->validated();
        $validated['customer_id'] = auth('sanctum')->user()->id ?? null;
        $validated['locale'] = App::currentLocale();
        $district = District::find($validated['district_id']);
        $validated['en']['district'] = $district->{'name:en'};
        $validated['tc']['district'] = $district->{'name:tc'};
        $customer_data = collect($validated)->only(['name', 'gender', 'birthday', 'hkid', 'tel', 'email', 'password'])->toArray();
        //Create customer
        if (empty($validated['customer_id']) && Customer::email($customer_data['email'])->count()) {
            return $this->error(__('auth.already_registered'));
        }

        $date_check = Timeslot::where('available_date', $validated['blood_date'])->enabled()->first();
        if (!$date_check) {
            return $this->error(__('auth.invalid_date'));
        }

        try {
            DB::beginTransaction();
            [$from, $to] = explode('-', $validated['blood_time']);

            $time_check = TimeslotQuota::lockForUpdate()
                ->where('timeslot_id', $date_check->id)
                ->where('from', $from)->where('to', $to)
                ->first();
            if (!$time_check) {
                return $this->error(__('auth.invalid_date'));
            }
            if ($time_check->quota === 0) {
                return $this->error(__('auth.quota_exceeded'));
            }
            //Keep Quota
            --$time_check->quota;
            $time_check->save();

            $service = Service::findOrFail($validated['service_id']);
            $district = District::findOrFail($validated['district_id']);
            $amount = $service->price;

            if (!empty($validated['code'])) {
                $coupon = Coupon::lockForUpdate()
                    ->where('code', $validated['code'])
                    ->validDate(now()->format('Y-m-d'))
                    ->validLimitation($amount)
                    ->first();
                if ($coupon === null) {
                    return $this->error(__('base.invalid_coupon_code'));
                }

                //Keep Quota
                ++$coupon->used;
                $coupon->save();
            }
            if (empty($validated['customer_id'])) {
                $customer_data['token'] = Str::random(64);
                $customer = Customer::create($customer_data);
                $validated['customer_id'] = $customer->id;
                event(new Registered($customer));
            }
            //Create Order
            $history = CustomerHistory::create($validated);
            //Prepare Order details
            $data = [
                [
                    'price' => $service->price,
                    'en' => [
                        'title' => $service->{'title:en'}
                    ],
                    'tc' => [
                        'title' => $service->{'title:tc'}
                    ]
                ],
            ];
            if ($district->extra_charge > 0) {
                $data[] = [
                    'price' => $district->extra_charge,
                    'en' => [
                        'title' => $district->{'name:en'}
                    ],
                    'tc' => [
                        'title' => $district->{'name:tc'}
                    ]
                ];
            }
            $explanation = ReportExplanation::where('type', $validated['report_explanation'])->first();
            if ($explanation->price > 0) {
                $data[] = [
                    'price' => $explanation->price,
                    'en' => [
                        'title' => __('base.' . $explanation->type, ['d' => $explanation->price], 'en')
                    ],
                    'tc' => [
                        'title' => __('base.' . $explanation->type, ['d' => $explanation->price], 'tc')
                    ]
                ];
            }

            $discount = 0;
            if (!empty($validated['code'])) {
                $coupon = Coupon::where('code', $validated['code'])
                    ->validDate(now()->format('Y-m-d'))
                    ->validLimitation($amount)
                    ->first();
                if ($coupon->type === CouponType::Amount) {
                    $discount -= $coupon->value;
                } else if ($coupon->type === CouponType::Percentage) {
                    $discount -= $amount * $coupon->value / 100;
                    $discount = round($discount);
                }
                $data[] = [
                    'price' => $discount,
                    'en' => [
                        'title' => $coupon->code
                    ],
                    'tc' => [
                        'title' => $coupon->code
                    ]
                ];
            }

            $history->customer_history_details()->createMany($data);
            $amount = $service->price + $district->extra_charge + $explanation->price + $discount;
            if ($amount > 0) {
                if ($amount < 4) {
                    DB::rollback();
                    return $this->error(__('auth.min_payment_charge'));
                }
                //Create payment section
                Stripe::setApiKey(config('stripe.secret_key'));
                $checkout_session = Session::create([
                    'payment_method_types' => ['card'],
                    'mode' => 'payment',
                    'success_url' => config('stripe.success_url') . $history->uuid,
                    'cancel_url' => config('stripe.cancel_url') . $history->uuid,
                    'client_reference_id' => $history->id,
                    'line_items' => [
                        [
                            'price_data' => [
                                'currency' => 'hkd',
                                'unit_amount' => $amount * 100,
                                'product_data' => [
                                    'name' => 'Service',
                                ]
                            ],
                            'quantity' => 1,
                        ]
                    ],
                ]);
                $history->update(['stripe_id' => $checkout_session->id]);
                Log::channel('payment')->info($history->id, $checkout_session->toArray());

                DB::commit();
                return $this->success(__('auth.purchase_success'), data: ['url' => $checkout_session->url]);
            }

            //No payment
            $history->update(['paid' => true]);
            DB::commit();
            return $this->success(__('auth.purchase_success'));
        } catch (Exception $e) {
            DB::rollback();
            Log::error('purchase', ['message' => $e->getMessage()]);
            return $this->error();
        }
    }

    #[Endpoint('Calculate')]
    #[Unauthenticated]
    public function calculate(CalculateRequest $request)
    {
        $validated = $request->validated();
        $service = Service::findOrFail($validated['service_id']);
        //$district = District::findOrFail($validated['district_id']);
        //$explanation = ReportExplanation::where('type', $validated['report_explanation'])->first();
        $amount = $service->price;
        $discount = 0;
        $coupon_info = [];
        if (!empty($validated['code'])) {
            $coupon = Coupon::where('code', $validated['code'])
                ->validDate(now()->format('Y-m-d'))
                ->validLimitation($amount)
                ->first();
            if ($coupon === null) {
                return $this->error(__('base.invalid_coupon_code'));
            }
            if ($coupon->type === CouponType::Amount) {
                $discount -= $coupon->value;
            } else if ($coupon->type === CouponType::Percentage) {
                $discount -= $amount * $coupon->value / 100;
                $discount = round($discount);
            }
            $coupon_info = [
                'code' => $coupon->code,
                'type' => $coupon->type,
                'limitation' => $coupon->limitation,
                'value' => $coupon->value,
                'discount' => $discount
            ];
        }

        //$amount += $district->extra_charge + $explanation->price;

        $data = [
            'service_price' => $amount,
            'discount' => $discount,
            'net_price' => $amount + $discount,
            //'extra_charge' => $district->extra_charge,
            //'explanation' => $explanation->price,
            //'coupon' => $coupon_info
        ];
        return $this->success(data: $data);
    }

    public function webhook(Request $request)
    {
        Stripe::setApiKey(config('stripe.secret_key'));
        $webhook_secret = config('stripe.webhook_secret');
        $payload = $request->getContent();
        $sig_header = $request->server('HTTP_STRIPE_SIGNATURE');
        Log::channel('payment')->info('webhook', $request->all());
        try {
            $event = Webhook::constructEvent(
                $payload, $sig_header, $webhook_secret
            );
        } catch (UnexpectedValueException $e) {
            // Invalid payload
            http_response_code(400);
            exit();
        } catch (SignatureVerificationException $e) {
            // Invalid signature
            http_response_code(400);
            exit();
        }
        try {
            if ($event->type === 'checkout.session.completed') {
                $session = $event->data->object;
                $record = CustomerHistory::where('stripe_id', $session->id)
                    ->where('paid', false)
                    ->with('customer_history_details')->first();
                $order_no = 'CBC' . now()->format('Ymd');
                $count = CustomerHistory::where('order_no', 'like' . $order_no . '%')->count() + 1;
                $count = sprintf("%03d", $count);
                $record?->update(['paid' => true, 'order_no' => $order_no . $count]);
                if ($record) {
                    Mail::to($record->email)->send(new Invoice($record));
                }
                return $this->success('OK');
            }
        } catch (Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
        return $this->error('Unknown event type');
    }

    public function testMail()
    {
        $record = CustomerHistory::where('id', 29)->with('customer_history_details')->first();
        if ($record !== null) {
            //return new Invoice($record);
            Mail::to($record->email)->send(new Invoice($record));
        }
        return 'OK';
    }
}
