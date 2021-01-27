<?php

namespace App\Console;

use App\DmLog;
use App\FacebookAccount;
use App\FbGroup;
use App\FbPage;
use App\FollowBackLog;
use App\IgDm;
use App\IgFollowBack;
use App\IgUnfollow;
use App\InstagramAccount;
use App\MyLog;
use App\PostLog;
use App\PostQueue;
use App\TwDm;
use App\TwFollowBack;
use App\TwitterAccount;
use App\TwUnfollow;
use App\UnfollowLog;
use Carbon\Carbon;
use Facebook\Exceptions\FacebookSDKException;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use InstagramAPI\Exception\AccountDisabledException;
use InstagramAPI\Exception\IncorrectPasswordException;
use InstagramAPI\Exception\NetworkException;
use InstagramAPI\Exception\NotFoundException;
use InstagramAPI\Instagram;
use SammyK\LaravelFacebookSdk\LaravelFacebookSdk;
use Thujohn\Twitter\Facades\Twitter;

class Kernel extends ConsoleKernel
{

    protected $fb;
    protected $ig;

    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // Process scheduled posts...
        $schedule->command('schedule:posts')->everyMinute()->withoutOverlapping();

        // Process auto follow back/dm for Instagram...
        $schedule->command('schedule:ig_follow_back')->everyFiveMinutes()->withoutOverlapping();

        // Process auto unfollow for Instagram...
        $schedule->command('schedule:ig_unfollow')->everyFiveMinutes()->withoutOverlapping();

        // Process auto follow back/dm for Twitter...
        $schedule->command('schedule:tw_follow_back')->everyFiveMinutes()->withoutOverlapping();

        // Process auto unfollow for Twitter...
        $schedule->command('schedule:tw_unfollow')->everyFiveMinutes()->withoutOverlapping();

        // Fetch all Instagram and Twitter details for all accounts...
        $schedule->command('fetch:instagram')->everyFiveMinutes()->withoutOverlapping();
        $schedule->command('fetch:twitter')->everyFiveMinutes()->withoutOverlapping();

        // Performs necessary clean up on schedules...
        $schedule->command('schedule:clean_up')->everyMinute()->withoutOverlapping();
    }


    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }

}
