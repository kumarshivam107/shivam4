<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\FollowBackLog
 *
 * @property int $id
 * @property string $type
 * @property string $link_url
 * @property string $user_name
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FollowBackLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FollowBackLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FollowBackLog whereLinkUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FollowBackLog whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FollowBackLog whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FollowBackLog whereUserName($value)
 * @mixin \Eloquent
 */
class FollowBackLog extends Model
{
    //
}
