<?php
/**
 * Created by PhpStorm.
 * User: nick
 * Date: 2019-03-09
 * Time: 14:35
 */

namespace Touge\AdminAliyunLive\Exceptions;


use Exception;

class ResponseFailedException extends Exception
{
    public function __construct(string $message = "", int $code = 200)
    {
        parent::__construct($message, $code);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function render(){
        return response()->json([
            "status"=> "failed",
            "message"=>$this->getMessage()
        ],$this->getCode());
    }
}