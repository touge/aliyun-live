<?php


use Illuminate\Support\Facades\Route;
use Illuminate\Routing\Router;

Route::group([
    'prefix'        => 'admin-aliyun-live',
    'namespace'     => '\Touge\AdminAliyunLive\Http\Controllers\Admin',
    'middleware'    => config('admin.route.middleware'),
    'as'=> 'aliyun-live.'
],function(Router $router){
    $router->resource('domain', "DomainController");
    $router->resource('channel', "ChannelController");

    $router->get('room/rooms4channel', "RoomController@room4channel");
    $router->resource('room', "RoomController");

    $router->resource('plan', "PlanController");
});