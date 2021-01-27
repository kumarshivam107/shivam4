<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(
    [
        'prefix' => LaravelLocalization::setLocale(),
        'middleware' => [ 'localeSessionRedirect', 'localizationRedirect', 'localeViewPath']
    ],
    function() {
        Auth::routes();
        Route::post('/lock-screen', 'LockScreenController@lock')->name('lock-screen');
        Route::get('/lock-screen', 'LockScreenController@viewLockScreen')->name('lock-screen-view');
        Route::post('/unlock-screen', 'LockScreenController@unlock')->name('unlock-screen');

        Route::get('/twitter/login', 'Settings\TwitterAPIController@login')->name('twitter.login');
        Route::get('/twitter/{twitter_id}/refresh', 'Settings\TwitterAPIController@refresh');
        Route::get('/twitter/callback', 'Settings\TwitterAPIController@callback')->name('twitter.callback');
        Route::post('/twitter/account/{id}/delete', 'Settings\TwitterAPIController@delete');

        Route::get('/facebook/login', 'Settings\FacebookAPIController@login')->name('facebook.login');
        Route::get('/facebook/{facebook_id}/refresh', 'Settings\FacebookAPIController@refresh');
        Route::get('/facebook/callback', 'Settings\FacebookAPIController@callback')->name('facebook.callback');
        Route::post('/facebook/account/{id}/delete', 'Settings\FacebookAPIController@delete');

        /* Handle Ajax Search Requests */
        Route::post('/search/ig/followers', 'SearchController@igFollowers')->name('search-ig-followers');
        Route::post('/search/ig/following', 'SearchController@igFollowing')->name('search-ig-following');
        Route::post('/search/ig/non-followers', 'SearchController@igNonFollowers')->name('search-ig-non-followers');
        Route::post('/search/ig/non-following', 'SearchController@igNonFollowing')->name('search-ig-non-following');

        Route::post('/search/tw/followers', 'SearchController@twFollowers')->name('search-tw-followers');
        Route::post('/search/tw/following', 'SearchController@twFollowing')->name('search-tw-following');
        Route::post('/search/tw/non-followers', 'SearchController@twNonFollowers')->name('search-tw-non-followers');
        Route::post('/search/tw/non-following', 'SearchController@twNonFollowing')->name('search-tw-non-following');
    }
);

Route::group(
    [
        'prefix' => LaravelLocalization::setLocale(),
        'middleware' => [ 'localeSessionRedirect', 'localizationRedirect', 'localeViewPath' ,'lock']
    ],
    function() {
        /* Dashboard */
        Route::get('/', function(){
            return redirect()->route('statistics');
        })->name('base-url');
        Route::get('/home', function(){
            return redirect()->route('statistics');
        })->name('home');

        Route::get('/statistics', 'Dashboard\StatisticsController@index')->name('statistics');
        Route::get('/logs/clear', 'Dashboard\StatisticsController@clearLog')->name('log-clear');
        Route::get('/activities', 'Dashboard\ActivityLogsController@index')->name('activities');

        /* General */
        Route::get('/schedule/posts', 'General\SchedulePostsController@index')->name('schedule-post');
        Route::post('/schedule/posts', 'General\SchedulePostsController@store')->name('schedule-post');
        Route::post('/schedule/posts/data', 'General\SchedulePostsController@getData')->name('post-queue-data');
        Route::get('/schedule/post/{id}/refresh', 'General\SchedulePostsController@refresh')->name('ig-unfollow-refresh');
        Route::get('/schedule/post/{id}/delete', 'General\SchedulePostsController@delete')->name('ig-unfollow-delete');

        Route::get('/schedule/picture', 'General\SchedulePictureController@index')->name('schedule-picture');
        Route::get('/calendar', 'General\CalendarController@index')->name('calendar');
        //Route::get('/draft', 'General\DraftController@index')->name('draft');

        /* Instagram */
        Route::get('/instagram/accounts', 'Instagram\AccountsController@index')->name('ig-accounts');
        Route::post('/instagram/accounts', 'Instagram\AccountsController@store')->name('ig-accounts');
        Route::get('/instagram/follow-back', 'Instagram\FollowBackController@index')->name('ig-follow-back');
        Route::post('/instagram/follow-back', 'Instagram\FollowBackController@store')->name('ig-follow-back');
        Route::post('/instagram/follow-back/data', 'Instagram\FollowBackController@getData')->name('ig-follow-back-data');
        Route::get('/instagram/follow-back/{id}/refresh', 'Instagram\FollowBackController@refresh')->name('ig-follow-back-refresh');
        Route::get('/instagram/follow-back/{id}/delete', 'Instagram\FollowBackController@delete')->name('ig-follow-back-delete');

        Route::get('/instagram/dm', 'Instagram\DMController@index')->name('ig-dm');
        Route::post('/instagram/dm', 'Instagram\DMController@store')->name('ig-dm');
        Route::post('/instagram/dm/data', 'Instagram\DMController@getData')->name('ig-dm-data');
        Route::get('/instagram/dm/{id}/refresh', 'Instagram\DMController@refresh')->name('ig-follow-back-refresh');
        Route::get('/instagram/dm/{id}/delete', 'Instagram\DMController@delete')->name('ig-follow-back-delete');

        Route::get('/instagram/unfollow', 'Instagram\UnfollowController@index')->name('ig-unfollow');
        Route::post('/instagram/unfollow/data', 'Instagram\UnfollowController@getData')->name('ig-unfollow-data');
        Route::post('/instagram/unfollow', 'Instagram\UnfollowController@store')->name('ig-unfollow');
        Route::get('/instagram/unfollow/{id}/refresh', 'Instagram\UnfollowController@refresh')->name('ig-unfollow-refresh');
        Route::get('/instagram/unfollow/{id}/delete', 'Instagram\UnfollowController@delete')->name('ig-unfollow-delete');

        // Twitter Account refresh & delete...
        Route::post('/instagram/refresh', 'Instagram\AccountsController@refresh')->name('ig-refresh');
        Route::post('/instagram/account/{id}/delete', 'Instagram\AccountsController@delete');

        /* Facebook */
        Route::get('/facebook/accounts', 'Facebook\AccountsController@index')->name('fb-accounts');
        Route::get('/facebook/page-group', 'Facebook\PageAndGroupController@index')->name('fb-page-group');
        Route::post('/facebook/page/data', 'Facebook\PageAndGroupController@getPageData')->name('fb-page-data');
        Route::post('/facebook/group/data', 'Facebook\PageAndGroupController@getGroupData')->name('fb-group-data');
        Route::get('/facebook/page/{id}/refresh', 'Facebook\PageAndGroupController@refreshPage');
        Route::get('/facebook/page/{id}/delete', 'Facebook\PageAndGroupController@deletePage');
        Route::get('/facebook/group/{id}/refresh', 'Facebook\PageAndGroupController@refreshGroup');
        Route::get('/facebook/group/{id}/delete', 'Facebook\PageAndGroupController@deleteGroup');


        /* Twitter */
        Route::get('/twitter/accounts', 'Twitter\AccountsController@index')->name('tw-accounts');
        Route::get('/twitter/follow-back', 'Twitter\FollowBackController@index')->name('tw-follow-back');
        Route::post('/twitter/follow-back', 'Twitter\FollowBackController@store')->name('tw-follow-back');
        Route::post('/twitter/follow-back/data', 'Twitter\FollowBackController@getData')->name('tw-follow-back-data');
        Route::get('/twitter/follow-back/{id}/refresh', 'Twitter\FollowBackController@refresh')->name('tw-follow-back-refresh');
        Route::get('/twitter/follow-back/{id}/delete', 'Twitter\FollowBackController@delete')->name('tw-follow-back-delete');
        Route::get('/twitter/dm', 'Twitter\DMController@index')->name('tw-dm');
        Route::post('/twitter/dm', 'Twitter\DMController@store')->name('tw-dm');
        Route::post('/twitter/dm/data', 'Twitter\DMController@getData')->name('tw-dm-data');
        Route::get('/twitter/dm/{id}/refresh', 'Twitter\DMController@refresh')->name('tw-follow-back-refresh');
        Route::get('/twitter/dm/{id}/delete', 'Twitter\DMController@delete')->name('tw-follow-back-delete');
        Route::get('/twitter/unfollow', 'Twitter\UnfollowController@index')->name('tw-unfollow');
        Route::post('/twitter/unfollow/data', 'Twitter\UnfollowController@getData')->name('tw-unfollow-data');
        Route::post('/twitter/unfollow', 'Twitter\UnfollowController@store')->name('tw-unfollow');
        Route::get('/twitter/unfollow/{id}/refresh', 'Twitter\UnfollowController@refresh')->name('tw-unfollow-refresh');
        Route::get('/twitter/unfollow/{id}/delete', 'Twitter\UnfollowController@delete')->name('tw-unfollow-delete');

        /* Settings */
        Route::get('/settings/facebook-api', 'Settings\FacebookAPIController@index')->name('set-fb-api');
        Route::post('/settings/facebook-api', 'Settings\FacebookAPIController@store');
        Route::get('/settings/twitter-api', 'Settings\TwitterAPIController@index')->name('set-tw-api');
        Route::post('/settings/twitter-api', 'Settings\TwitterAPIController@store')->name('set-tw-api');
        Route::get('/settings/profile', 'Settings\ProfileController@index')->name('set-profile');
        Route::post('/settings/profile/update', 'Settings\ProfileController@updateProfile')->name('update-profile');
        Route::get('/settings/global', 'Settings\GlobalController@index')->name('set-global');
    }
);
