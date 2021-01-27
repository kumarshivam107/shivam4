<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\FbGroup
 *
 * @property int $id
 * @property int $facebook_id
 * @property int $group_id
 * @property string $group_name
 * @property int $group_members
 * @property string $status
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FbGroup whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FbGroup whereFacebookId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FbGroup whereGroupId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FbGroup whereGroupMembers($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FbGroup whereGroupName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FbGroup whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FbGroup whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FbGroup whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class FbGroup extends Model
{
    public function facebook_account()
    {
        return $this->belongsTo('App\FacebookAccount', 'facebook_id', 'id');
    }
}
