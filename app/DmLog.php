<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\DmLog
 *
 * @property int $id
 * @property string $type
 * @property string $link_url
 * @property string $user_name
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DmLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DmLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DmLog whereLinkUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DmLog whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DmLog whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DmLog whereUserName($value)
 * @mixin \Eloquent
 */
class DmLog extends Model
{
    //
}
