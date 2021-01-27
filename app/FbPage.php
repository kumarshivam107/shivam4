<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\FbPage
 *
 * @property int $id
 * @property int $facebook_id
 * @property int $page_id
 * @property string $page_name
 * @property int $page_likes
 * @property string $status
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FbPage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FbPage whereFacebookId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FbPage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FbPage wherePageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FbPage wherePageLikes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FbPage wherePageName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FbPage whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FbPage whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class FbPage extends Model
{
    public function facebook_account()
    {
        return $this->belongsTo('App\FacebookAccount', 'facebook_id', 'id');
    }
}
