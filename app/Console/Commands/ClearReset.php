<?php

namespace App\Console\Commands;

use App\Models\PasswordResetToken;
use Illuminate\Console\Command;

class ClearReset extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:clear-reset';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete password reset token if 1 hour has passed';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        PasswordResetToken::where('created_at', '<', now()->subHour())->delete();
    }
}
