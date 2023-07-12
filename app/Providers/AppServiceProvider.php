<?php

namespace App\Providers;

use App\Models\PasswordResetToken;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Database\Connection;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //Only allow super admin to access log viewer
        /*LogViewer::auth(function ($request) {
            return $request->user() && $request->user()->hasRole('Super Admin');
        });*/

        DB::whenQueryingForLongerThan(500, function (Connection $connection, QueryExecuted $event) {
            Log::warning("Database queries exceeded 5 seconds on {$connection->getName()}", ['sql' => $event, 'bindings' => $event->bindings, 'time' => $event->time]);
        });

        VerifyEmail::createUrlUsing(function ($notifiable) {
            $route = route('v1.auth.verify', $notifiable->token);
            return Str::replace(config('app.root_url'), config('app.url'), $route);
        });

        ResetPassword::createUrlUsing(function ($notifiable) {
            $token = PasswordResetToken::where('email', $notifiable->email)->first()->token;
            return config('app.password_reset_url') . $token;
        });
    }
}
