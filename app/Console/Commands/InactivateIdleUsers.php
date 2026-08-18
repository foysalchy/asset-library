<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Carbon\Carbon;

class InactivateIdleUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:inactivate-idle';
    protected $description = 'Inactivate users who have not logged in for the last 7 days, excluding Super Admins';

    public function handle()
    {
        $sevenDaysAgo = Carbon::now()->subDays(7);

        // Logic:
        // 1. User must be currently 'active'
        // 2. User must NOT have the 'super_admin' role
        // 3. (last_login_at < 7 days ago) OR (never logged in AND account created > 7 days ago)

        $query = User::where('status', 'active')
            ->whereDoesntHave('roles', function ($q) {
                $q->where('name', 'super_admin');
            })
            ->where(function ($query) use ($sevenDaysAgo) {
                $query->where('last_login_at', '<', $sevenDaysAgo)
                    ->orWhere(function ($q) use ($sevenDaysAgo) {
                        $q->whereNull('last_login_at')
                            ->where('created_at', '<', $sevenDaysAgo);
                    });
            });

        $inactivatedCount = $query->update(['status' => 'inactive']);

        $this->info("Total {$inactivatedCount} idle users have been inactivated.");
    }
}
