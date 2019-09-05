<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Images;
use App\Models\Region;
use App\Models\Educations;

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


    /**
    *   关联地区
    *
    */
    public function region()
    {
        return $this->hasOne(Region::class, 'id', 'region_id');
    }

    /**
    * 关联学历
    *
    */
    public function education()
    {
       return $this->hasOne(Educations::class, 'id', 'education_id');
    }

}

