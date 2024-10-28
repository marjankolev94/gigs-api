<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Gig;

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
        $users = User::all();

        foreach($users as $user) {
            $postedGigsCount = Gig::whereIn('company_id', $user->companies->pluck('id')->toArray())
                                ->where('status', true)
                                ->count();
            
            $totalGigsCount = Gig::whereIn('company_id', $user->companies->pluck('id')->toArray())
                                ->count();
                            
            $postRate = $totalGigsCount > 0 ? round(($postedGigsCount / $totalGigsCount) * 100, 2) : 0;
            $user->posted_rate = $postRate;

            $user->save();

            $this->info("Updated posted rate for user {$user->first_name} {$user->last_name}: {$postRate}%");
        }

        $this->info('Posted rates calculated for all users.');
    }
}
