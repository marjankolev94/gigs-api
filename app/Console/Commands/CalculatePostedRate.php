<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Gig;
use Illuminate\Support\Facades\DB;

class CalculatePostedRate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:calculate-posted-rate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $users = User::with('companies')->get();
        $gigCounts = Gig::select('company_id', 'status')
                        ->get()
                        ->groupBy('company_id');

        $cases = [];
        $userIds = [];

        foreach ($users as $user) {
            $companyIds = $user->companies->pluck('id')->toArray();
            $totalGigsCount = $postedGigsCount = 0;
    
            foreach ($companyIds as $companyId) {
                if (isset($gigCounts[$companyId])) {
                    $totalGigsCount += $gigCounts[$companyId]->count();
                    $postedGigsCount += $gigCounts[$companyId]->where('status', true)->count();
                }
            }
    
            $postRate = $totalGigsCount > 0 ? round(($postedGigsCount / $totalGigsCount) * 100, 2) : 0;
    
            $cases[] = "WHEN id = {$user->id} THEN {$postRate}";
            $userIds[] = $user->id;
    
            $this->info("Calculated posted rate for user {$user->first_name} {$user->last_name}: {$postRate}%");
        }

        $caseStatement = implode(' ', $cases);
        $userIds = implode(',', $userIds);

        DB::statement("UPDATE users SET posted_rate = CASE {$caseStatement} END WHERE id IN ({$userIds})");

        $this->info('Posted rates calculated for all users.');
    }
}
