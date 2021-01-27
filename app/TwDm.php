<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\TwDm
 *
 * @property int $id
 * @property int $twitter_id
 * @property string $start_date
 * @property string|null $end_date
 * @property string $status
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TwDm whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TwDm whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TwDm whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TwDm whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TwDm whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TwDm whereTwitterId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TwDm whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class TwDm extends Model
{
    public function twitter_account()
    {
        return $this->belongsTo('App\TwitterAccount', 'twitter_id', 'id');
    }
}
