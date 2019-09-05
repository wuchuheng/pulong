<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Images;

class Members extends Model
{
    public $timestamps = false;

    /**
     * 关联用户评论
     */
    public function comments()
    {
        return $this->hasMany(Comments::class);
    }


    /**
     * 关联头像
     *
     */ 
    public function avatar()
    {
        return $this->hasOne(Images::class, 'id', 'avatar_image_id');
    }
}

