<?php
/**
 * Created by PhpStorm.
 * User: nick
 * Date: 2020-03-02
 * Time: 19:43
 */

namespace Touge\AdminAliyunLive\Models;


use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $table= 'touge_live_plans';

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
}