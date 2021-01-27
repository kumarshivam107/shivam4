<?php

namespace App\Console\Commands;

use App\IgDm;
use App\IgFollowBack;
use App\IgUnfollow;
use App\TwDm;
use App\TwFollowBack;
use App\TwUnfollow;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CleanUpSchedule extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'schedule:clean_up';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Performs necessary scheduled cleanup, i.e Tag expired schedule, completed.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // Tag all expired Instagram schedule completed...
        $queue = IgFollowBack::where('status', 'active')
            ->where('end_date', '<', Carbon::now())
            ->get();
        foreach($queue as $q){
            $q->status = 'completed';
            $q->save();
        }

        $queue = IgDm::where('status', 'active')
            ->where('end_date', '<', Carbon::now())
            ->get();
        foreach($queue as $q){
            $q->status = 'completed';
            $q->save();
        }

        $queue = IgUnfollow::where('status', 'active')
            ->where('end_date', '<', Carbon::now())
            ->get();
        foreach($queue as $q){
            $q->status = 'completed';
            $q->save();
        }

        // Tag all expired Twitter schedule completed...
        $queue = TwFollowBack::where('status', 'active')
            ->where('end_date', '<', Carbon::now())
            ->get();
        foreach($queue as $q){
            $q->status = 'completed';
            $q->save();
        }

        $queue = TwDm::where('status', 'active')
            ->where('end_date', '<', Carbon::now())
            ->get();
        foreach($queue as $q){
            $q->status = 'completed';
            $q->save();
        }

        $queue = TwUnfollow::where('status', 'active')
            ->where('end_date', '<', Carbon::now())
            ->get();
        foreach($queue as $q){
            $q->status = 'completed';
            $q->save();
        }
    }
}
