<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\InstagramAccount
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
 * @method static \Illuminate\Database\Eloquent\Builder|\App\InstagramAccount whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\InstagramAccount whereCredentials($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\InstagramAccount whereFollowers($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\InstagramAccount whereFollowing($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\InstagramAccount whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\InstagramAccount whereProfileId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\InstagramAccount whereProfileName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\InstagramAccount whereProfilePicture($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\InstagramAccount whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\InstagramAccount whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class InstagramAccount extends Model
{
    public function follow_back()
    {
        return $this->hasMany('App\IgFollowBack', 'instagram_id', 'id');
    }

    public function dm()
    {
        return $this->hasMany('App\IgDm', 'instagram_id', 'id');
    }

    public function unfollow()
    {
        return $this->hasMany('App\IgUnfollow', 'instagram_id', 'id');
    }
}
