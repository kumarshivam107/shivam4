<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\TwUnfollow
 *
 * @property int $id
 * @property int $twitter_id
 * @property string $start_date
 * @property string|null $end_date
 * @property string $status
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TwUnfollow whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TwUnfollow whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TwUnfollow whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TwUnfollow whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TwUnfollow whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TwUnfollow whereTwitterId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TwUnfollow whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class TwUnfollow extends Model
{
    public function twitter_account()
    {
        return $this->belongsTo('App\TwitterAccount', 'twitter_id', 'id');
    }
}
