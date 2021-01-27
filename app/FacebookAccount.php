<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\FacebookAccount
 *
 * @property int $id
 * @property string $credentials
 * @property string $profile_name
 * @property int $profile_id
 * @property string|null $profile_picture
 * @property string $status
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FacebookAccount whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FacebookAccount whereCredentials($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FacebookAccount whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FacebookAccount whereProfileId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FacebookAccount whereProfileName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FacebookAccount whereProfilePicture($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FacebookAccount whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FacebookAccount whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class FacebookAccount extends Model
{
    public function pages()
    {
        return $this->hasMany('App\FbPage', 'facebook_id', 'id');
    }

    public function groups()
    {
        return $this->hasMany('App\FbGroup', 'facebook_id', 'id');
    }
}
