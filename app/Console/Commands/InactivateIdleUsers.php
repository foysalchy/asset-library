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

        // Step 1: Fix previously wrongly-inactivated users
        // Jader last_login_at null but status already 'inactive' (agei kora hoye gese),
        // tader abar 'active' kore dicchi.
        $reactivatedCount = User::where('status', 'inactive')
            ->whereNull('last_login_at')
            ->whereDoesntHave('roles', function ($q) {
                $q->where('name', 'super_admin');
            })
            ->update(['status' => 'active']);

        $this->info("Total {$reactivatedCount} wrongly inactivated users have been reactivated.");

        // Step 2: Inactivate idle users
        // Logic:
        // 1. User must be currently 'active'
        // 2. User must NOT have the 'super_admin' role
        // 3. last_login_at < 7 days ago (jader last_login_at null tader ekhon r touch kora hobe na)
        $query = User::where('status', 'active')
            ->whereDoesntHave('roles', function ($q) {
                $q->where('name', 'super_admin');
            })
            ->whereNotNull('last_login_at')
            ->where('last_login_at', '<', $sevenDaysAgo);

        $inactivatedCount = $query->update(['status' => 'inactive']);

        $this->info("Total {$inactivatedCount} idle users have been inactivated.");
    }
}