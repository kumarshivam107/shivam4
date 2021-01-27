<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\IgDm
 *
 * @property int $id
 * @property int $instagram_id
 * @property string $start_date
 * @property string|null $end_date
 * @property string $status
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\IgDm whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\IgDm whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\IgDm whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\IgDm whereInstagramId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\IgDm whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\IgDm whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\IgDm whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class IgDm extends Model
{
    public function instagram_account()
    {
        return $this->belongsTo('App\InstagramAccount', 'instagram_id', 'id');
    }
}
