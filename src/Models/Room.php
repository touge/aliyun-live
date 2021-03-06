<?php
/**
 * Created by PhpStorm.
 * User: nick
 * Date: 2020-03-02
 * Time: 18:58
 */

namespace Touge\AdminAliyunLive\Models;


use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Room extends BaseModel
{
    protected $table= 'touge_live_rooms';
    protected $guarded= ['id'];


    /**
     * @return HasOne
     */
    public function channel(): HasOne
    {
        return $this->hasOne(Channel::class ,'id', 'channel_id');
    }

    /**
     * 计划列表
     * @return HasMany
     */
    public function plans(): HasMany
    {
        return $this->hasMany(Plan::class, 'id', 'plan_id');
    }
}
