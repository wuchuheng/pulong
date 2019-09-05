<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MemberFollow extends Model
{
    protected $table = 'member_follows';

    /**
     *  获取粉丝量
     * @uid      init    用户id
     * @return   init    粉丝量
     */
    public  static  function  countFansByUid(int $uid)
    {
        $is_int = self::where('follow_member_id', $uid)
            ->count();
        return $is_int;
    }
}
