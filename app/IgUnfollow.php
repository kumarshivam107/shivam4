<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\IgUnfollow
 *
 * @property int $id
 * @property int $instagram_id
 * @property string $start_date
 * @property string|null $end_date
 * @property string $status
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\IgUnfollow whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\IgUnfollow whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\IgUnfollow whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\IgUnfollow whereInstagramId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\IgUnfollow whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\IgUnfollow whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\IgUnfollow whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class IgUnfollow extends Model
{
    public function instagram_account()
    {
        return $this->belongsTo('App\InstagramAccount', 'instagram_id', 'id');
    }
}
