<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\PostLog
 *
 * @property int $id
 * @property string $type
 * @property string $link_url
 * @property string $profile_name
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PostLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PostLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PostLog whereLinkUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PostLog whereProfileName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PostLog whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PostLog whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class PostLog extends Model
{
    //
}
