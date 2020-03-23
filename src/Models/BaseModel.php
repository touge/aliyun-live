<?php
/**
 * Created by PhpStorm.
 * User: nick
 * Date: 2019-12-12
 * Time: 18:12
 */

namespace Touge\AdminAliyunLive\Models;


use Encore\Admin\Traits\DefaultDatetimeFormat;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class BaseModel extends Model
{
    use DefaultDatetimeFormat;

    public function __construct(array $attributes = [])
    {
        $connection= config('touge-aliyun-live.database.connection');
        $this->setConnection($connection);
        parent::__construct($attributes);
    }
}
