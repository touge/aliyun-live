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
        'connection'=> 'main_system',
    ],

    'accessKeyId'=> 'LTAI4FdWxvEgfbEYaA238XjH',
    'accessKeySecret'=> 'ke489lTWyVyIeHgQw1KrNZRMxOFJVp',


    /**
     * 域名配置
     */
    'domain'=> [
        'pull'=> [
            'auth_key'=> 'Xm2VydS4El',
            'url'=> 'pull.live.the9edu.com',
            'expire'=> 10800, //三个小时
        ],
        'push'=> [
            'auth_key'=> 'Xm2VydS4El',
            'url'=> 'push.live.the9edu.com',
            'expire'=> 10800,
        ]
    ],
];