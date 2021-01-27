<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\UnfollowLog
 *
 * @property int $id
 * @property string $type
 * @property string $link_url
 * @property string $user_name
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UnfollowLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UnfollowLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UnfollowLog whereLinkUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UnfollowLog whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UnfollowLog whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UnfollowLog whereUserName($value)
 * @mixin \Eloquent
 */
class UnfollowLog extends Model
{
    //
}
