<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\PictureQueue
 *
 * @property int $id
 * @property string $image_file
 * @property string|null $instagram_ids
 * @property string|null $facebook_ids
 * @property string|null $twitter_ids
 * @property string $schedule_time
 * @property string $status
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PictureQueue whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PictureQueue whereFacebookIds($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PictureQueue whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PictureQueue whereImageFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PictureQueue whereInstagramIds($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PictureQueue whereScheduleTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PictureQueue whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PictureQueue whereTwitterIds($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PictureQueue whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class PictureQueue extends Model
{
    protected $table = 'picture_queue';
}
