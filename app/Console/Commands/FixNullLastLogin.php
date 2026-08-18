<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Carbon\Carbon;

class FixNullLastLogin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:fix-null-last-login';
    protected $description = 'Set last_login_at to today for users where it is currently null';

    public function handle()
    {
        $now = Carbon::now();

        $updatedCount = User::whereNull('last_login_at')
            ->update(['last_login_at' => $now]);

        $this->info("Total {$updatedCount} users' last_login_at updated to {$now->toDateTimeString()}.");
    }
}