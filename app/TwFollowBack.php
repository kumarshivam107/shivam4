<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\TwFollowBack
 *
 * @property int $id
 * @property int $twitter_id
 * @property string $start_date
 * @property string|null $end_date
 * @property string $status
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TwFollowBack whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TwFollowBack whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TwFollowBack whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TwFollowBack whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TwFollowBack whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TwFollowBack whereTwitterId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TwFollowBack whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class TwFollowBack extends Model
{
    public function twitter_account()
    {
        return $this->belongsTo('App\TwitterAccount', 'twitter_id', 'id');
    }
}
