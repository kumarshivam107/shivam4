<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\TwitterAccount
 *
 * @property int $id
 * @property string $credentials
 * @property string $profile_name
 * @property int $profile_id
 * @property string|null $profile_picture
 * @property int $followers
 * @property int $following
 * @property string $status
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TwitterAccount whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TwitterAccount whereCredentials($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TwitterAccount whereFollowers($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TwitterAccount whereFollowing($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TwitterAccount whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TwitterAccount whereProfileId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TwitterAccount whereProfileName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TwitterAccount whereProfilePicture($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TwitterAccount whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TwitterAccount whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class TwitterAccount extends Model
{
    public function follow_back()
    {
        return $this->hasMany('App\TwFollowBack', 'twitter_id', 'id');
    }

    public function dm()
    {
        return $this->hasMany('App\TwDm', 'twitter_id', 'id');
    }

    public function unfollow()
    {
        return $this->hasMany('App\TwUnfollow', 'twitter_id', 'id');
    }
}
