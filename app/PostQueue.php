<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\PostQueue
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
 * @property string $status
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PostQueue whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PostQueue whereFacebookIds($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PostQueue whereFbGroupIds($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PostQueue whereFbPageIds($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PostQueue whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PostQueue whereImageFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PostQueue whereInstagramIds($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PostQueue whereMsgBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PostQueue whereScheduleTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PostQueue whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PostQueue whereTwitterIds($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PostQueue whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PostQueue whereVideoFile($value)
 * @mixin \Eloquent
 */
class PostQueue extends Model
{
    protected $table = 'post_queue';
}
