<?php
/**
 * Created by PhpStorm.
 * User: nick
 * Date: 2020-03-02
 * Time: 19:43
 */

namespace Touge\AdminAliyunLive\Models;



class Plan extends BaseModel
{
    protected $table= 'touge_live_plans';

    /**
     * 追加到模型数组表单的访问器
     *
     * @var array
     */
    //protected $appends = ['start_unix_time'];

    /**
     * @return mixed
     */
    public function channel(){
        return $this->hasOne(Channel::class, 'id', 'channel_id');
    }

    /**
     * @return mixed
     */
    public function room(){
        return $this->hasOne(Room::class, 'id', 'room_id');
    }


    /**
     * 开始的unix time
     * @return false|string
     *
     * //getHadNotLoginDaysAttribute
     */
    public function getStartUnixTimeAttribute()
    {
        $publish_at= $this->attributes['publish_at'];
        return strtotime($publish_at);
    }
}