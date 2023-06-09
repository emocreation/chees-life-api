<?php

namespace App\Providers;

use Illuminate\Database\Connection;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use Opcodes\LogViewer\Facades\LogViewer;

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
        LogViewer::auth(function ($request) {
            return $request->user() && $request->user()->hasRole('Super Admin');
        });

        DB::whenQueryingForLongerThan(500, function (Connection $connection, QueryExecuted $event) {
            Log::warning("Database queries exceeded 5 seconds on {$connection->getName()}", ['sql' => $event, 'bindings' => $event->bindings, 'time' => $event->time]);
        });
    }
}
