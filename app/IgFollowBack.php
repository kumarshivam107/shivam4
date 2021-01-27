<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\IgFollowBack
 *
 * @property int $id
 * @property int $instagram_id
 * @property string $start_date
 * @property string|null $end_date
 * @property string $status
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\IgFollowBack whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\IgFollowBack whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\IgFollowBack whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\IgFollowBack whereInstagramId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\IgFollowBack whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\IgFollowBack whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\IgFollowBack whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class IgFollowBack extends Model
{
    public function instagram_account()
    {
        return $this->belongsTo('App\InstagramAccount', 'instagram_id', 'id');
    }
}
