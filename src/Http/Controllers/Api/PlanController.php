<?php
/**
 * Created by PhpStorm.
 * User: nick
 * Date: 2020-03-02
 * Time: 18:21
 */
namespace Touge\AdminAliyunLive\Http\Controllers\Api;

use Touge\AdminAliyunLive\Http\Controllers\BaseApiController;
use Touge\AdminAliyunLive\Models\LiveUrl;

class PlanController extends BaseApiController
{
    public function fetch_list()
    {
        $data= LiveUrl::all();

        return $this->success($data);
    }
}