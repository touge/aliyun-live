<?php
/**
 * Created by PhpStorm.
 * User: nick
 * Date: 2018/8/29
 * Time: 上午11:07
 */
return [
    /**
     * 数据库链接配置，需要在database.connections中进行配置，参考database.connections.mysql配置
     */
    'database'=> [
        'connection'=> env('ALIYUN_LIVE_DB_CONNECTION','main_system'),
    ],

    'accessKeyId'=> env('ALIYUN_LIVE_KEY', ''),
    'accessKeySecret'=> env('ALIYUN_LIVE_SECRET', ''),


    /**
     * 域名配置
     */
    'domain'=> [
        'pull'=> [
            'auth_key'=> env('ALIYUN_LIVE_PULL_KEY', ''),
            'url'=> env('ALIYUN_LIVE_PULL_URL', ''),
            'expire'=> env('ALIYUN_LIVE_PULL_EXPIRE', 10800), //三个小时
        ],
        'push'=> [
            'auth_key'=> env('ALIYUN_LIVE_PUSH_KEY', ''),
            'url'=> env('ALIYUN_LIVE_PUSH_URL', ''),
            'expire'=> env('ALIYUN_LIVE_PUSH_EXPIRE', 10800),
        ]
    ],
];
