<?php

namespace App\Console\Commands;

use App\Models\CustomerHistory;
use App\Models\Timeslot;
use App\Models\TimeslotQuota;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Stripe\Checkout\Session;
use Stripe\Stripe;

class ExpirePayment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:expire-payment';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete order history after 30 minutes';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        DB::transaction(function () {
            Stripe::setApiKey(config('stripe.secret_key'));
            $orders = CustomerHistory::where('paid', false)
                ->whereNotNull('stripe_id')
                ->where('created_at', '<', now()->subMinutes(30))
                ->get();
            foreach ($orders as $order) {
                $session = Session::retrieve($order->stripe_id);
                if ($session->payment_status === 'paid') {
                    Log::info('payment', $session->toArray());
                    $order->update(['paid' => true]);
                } else {
                    //Delete stripe session
                    $session->expire();
                    //Release stock
                    $timeslot = Timeslot::where('available_date', $order->blood_date)->first();
                    if ($timeslot !== null) {
                        $time_range = explode('-', $timeslot->blood_time);
                        if (count($time_range) === 2) {
                            $time_check = TimeslotQuota::where('timeslot_id', $timeslot->id)
                                ->where('from', $time_range[0])->where('to', $time_range[1])->first();
                            ++$time_check->quota;
                            $time_check->save();
                        }
                    }
                    $order->delete();
                }
            }
        });
    }
}
