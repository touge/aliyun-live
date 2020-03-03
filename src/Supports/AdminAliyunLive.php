<?php

namespace Touge\AdminAliyunLive\Supports;

use Encore\Admin\Extension;

class AdminAliyunLive extends Extension
{
    public $name = 'admin-aliyun-live';
    public $views = __DIR__.'/../../resources/views';
    public $assets = __DIR__.'/../../resources/assets';


    public static function config_path(){
        return __DIR__.'/../../config';
    }
}