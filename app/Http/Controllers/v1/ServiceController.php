<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\Service\PurchaseRequest;
use App\Http\Resources\v1\ServiceCollection;
use App\Http\Resources\v1\ServiceResource;
use App\Models\CustomerHistory;
use App\Models\District;
use App\Models\Service;
use App\Models\Timeslot;
use App\Models\TimeslotQuota;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
        DB::transaction(function () use ($request) {
            $validated = $request->validated();
            $validated['customer_id'] = auth('sanctum')->user()->id ?? null;

            $date_check = Timeslot::where('available_date', $validated['blood_date'])->enable()->first();
            if (!$date_check) {
                return $this->error(__('auth.invalid_date'));
            }
            [$from, $to] = explode('-', $validated['blood_time'],);

            $time_check = TimeslotQuota::where('from', $from)->where('to', $to)->first();
            if (!$time_check) {
                return $this->error(__('auth.invalid_date'));
            }
            if ($time_check->quota === 0) {
                return $this->error(__('auth.quota_exceeded'));
            }

            //Keep Quota
            $time_check->lockForUpdate()->decrement('quota');
            //Create Order
            $history = CustomerHistory::create($validated);
            $service = Service::findOrFail($validated['service_id']);
            $district = District::findOrFail($validated['district_id']);
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
                        'district' => $district->{'name:en'}
                    ],
                    'tc' => [
                        'district' => $district->{'name:tc'}
                    ]
                ];
            }
            $history->customer_history_details()->createMany($data);
            $amount = $service->price + $district->extra_charge;
            if ($amount > 0) {
                if ($amount < 4) {
                    return $this->error(__('auth.min_payment_charge'));
                }
                //Create payment section
                Stripe::setApiKey(config('stripe.secret_key'));
                $checkout_session = Session::create([
                    'payment_method_types' => ['card'],
                    'mode' => 'payment',
                    'success_url' => config('stripe.success_url') . $history->id,
                    'cancel_url' => config('stripe.cancel_url') . $history->id,
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
                return $this->success(__('auth.purchase_success'), data: ['url' => $checkout_session->url]);
            }
            //No payment
            $history->update(['paid' => true]);
            return $this->success(__('auth.purchase_success'));
        });
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
                $record = CustomerHistory::where('stripe_id', $session->id)->where('paid', false)->first();
                $record?->update(['paid' => true]);
                return $this->success('OK');
            }
        } catch (Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
        return $this->error('Unknown event type');
    }
}
