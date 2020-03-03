<?php
/**
 * Created by PhpStorm.
 * User: nick
 * Date: 2019-12-29
 * Time: 12:25
 */

namespace Touge\AdminAliyunLive\Exceptions;


trait ThrowError
{
    /**
     * @param $message
     * @param int $httpCode
     * @throws ResponseFailedException
     */
    public function throw_error($message, $httpCode=200){
        throw new ResponseFailedException($message ,$httpCode);
    }

    /**
     * 静态类调用
     *
     * @param $message
     * @param int $httpCode
     * @throws ResponseFailedException
     */
    public static function static_throw_error($message, $httpCode=200){
        throw new ResponseFailedException($message, $httpCode);
    }
}