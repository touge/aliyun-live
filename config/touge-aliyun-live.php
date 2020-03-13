<?php
/**
 * Created by PhpStorm.
 * User: nick
 * Date: 2018/8/29
 * Time: 上午11:07
 */
return [
    'accessKeyId'=> '*',
    'accessKeySecret'=> '*',

    /**
     * 域名配置
     */
    'domain'=> [
        'pull'=> [
            'auth_key'=> '*',
            'url'=> '*',
            'expire'=> 10800, //三个小时
        ],
        'push'=> [
            'auth_key'=> '*',
            'url'=> '*',
            'expire'=> 10800,
        ]
    ],
];