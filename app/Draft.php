<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Draft
 *
 * @property int $id
 * @property string $msg_body
 * @property string|null $instagram_ids
 * @property string|null $facebook_ids
 * @property string|null $twitter_ids
 * @property string|null $fb_group_ids
 * @property string|null $fb_page_ids
 * @property string $schedule_time
 * @property string|null $image_file
 * @property string|null $video_file
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Draft whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Draft whereFacebookIds($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Draft whereFbGroupIds($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Draft whereFbPageIds($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Draft whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Draft whereImageFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Draft whereInstagramIds($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Draft whereMsgBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Draft whereScheduleTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Draft whereTwitterIds($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Draft whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Draft whereVideoFile($value)
 * @mixin \Eloquent
 */
class Draft extends Model
{
    //
}
