<?php
/**
 * Created by PhpStorm.
 * User: nick
 * Date: 2019-12-19
 * Time: 08:37
 */

namespace Touge\AdminAliyunLive\Http\Controllers;


use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Touge\AdminSundry\Exceptions\ThrowError;

class BaseApiController extends Controller
{
    use ThrowError;

    /**
     * @return mixed
     */
    protected function user(){
        return Auth::guard()->user();
    }

    /**
     * @param $data
     * @param string $status
     * @param int $httpCode
     * @return \Illuminate\Http\JsonResponse
     */
    protected function response($data,$status='successful',$httpCode=200){
        $output = [
            'status'=>$status
        ];

        if($status=='successful'){
            $output['data'] = $data;
        }else{
            $output['message'] = $data;
        }
        return response()->json($output,$httpCode)
            ->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }


    /**
     * @param $message
     * @param int $httpCode
     * @throws \Touge\AdminSundry\Exceptions\ResponseFailedException
     */
    protected function failed($message,$httpCode=200){
        if(is_array($message)){
            $message= json_encode($message,JSON_UNESCAPED_UNICODE);
        }
        $this->throw_error($message, $httpCode);
    }

    /**
     * @param $data
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    protected function success($data,$code=200){
        return $this->response($data,'successful',$code);
    }
}

