<?php
/**
 * Created by PhpStorm.
 * User: nick
 * Date: 2020-03-02
 * Time: 18:22
 */


use Illuminate\Support\Facades\Route;
use Illuminate\Routing\Router;

Route::group([
    'prefix'=> 'touge-aliyun-live',
], function(Router $router){
    /**
     * 直播频道列表
     */
    $router->post("plan/fetch_list" ,"PlanController@fetch_list")->name('plan.fetch_list');
    $router->post("plan/stream_list" ,"PlanController@stream_list")->name('plan.stream_list');
    $router->post("plan/check_streaming" ,"PlanController@check_streaming")->name('plan.check_streaming');
    $router->post("plan/info" ,"PlanController@info")->name('plan.info');

});