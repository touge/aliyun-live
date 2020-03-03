<?php
/**
 * Created by PhpStorm.
 * User: nick
 * Date: 2020-03-02
 * Time: 18:21
 */
namespace Touge\AdminAliyunLive\Http\Controllers\Api;

use Illuminate\Http\Request;
use Touge\AdminAliyunLive\Http\Controllers\BaseApiController;
use Touge\AdminAliyunLive\Models\Plan;
use Touge\AdminAliyunLive\Supports\AlibabaLiveClient;

class PlanController extends BaseApiController
{
    protected $a= 86400;
    public function fetch_list()
    {

        $s= date('Y-m-d H:i:s', time() - 3600);
        $e= date('Y-m-d H:i:s',time() + $this->a);
        $_plans= Plan::whereBetween('publish_at', [$s, $e])->get(['id','channel_id', 'room_id', 'title','anchor','publish_at']);

        $plans= [];
        foreach($_plans as $key=>$plan){
            $row= $plan;
            $row['channel_name']= $plan->channel->name;
            $row['room_name']= $plan->room->name;
            array_push($plans, $row);
            unset($row['channel'],$row['room']);
        }

        $data= [
            'current_time'=> time(),
            'plans'=> $plans
        ];

        return $this->success($data);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function stream_list(Request $request)
    {
        $options= ['DomainName'=> config('touge-aliyun-live.domain.push.url')];
        $response= AlibabaLiveClient::DescribeLiveStreamsOnlineList($options);
        $OnlineInfo= $response['data']['OnlineInfo']['LiveStreamOnlineInfo'];

        return $this->success($OnlineInfo);
    }


    /**
     * @param Request $request
     * @return mixed
     */
    public function check_streaming(Request $request){
        $channel_name= $request->get('channel_name', 'the9edu');
        $room_name= $request->get('room_name', '001');


        $options= [
            'DomainName'=> config('touge-aliyun-live.domain.push.url'),
            'AppName'=> $channel_name,
            'StreamName'=> $room_name
        ];
        $response= AlibabaLiveClient::DescribeLiveStreamsOnlineList($options);

        $data= [
            'is_streaming'=> $response['data']['TotalNum']>0 ?true :false
        ];

        return $this->success($data);
    }

    public function info(Request $request){

        $plan_id= $request->get('plan_id', 0);
        if($plan_id==0){
            return $this->failed('索引为空');
        }

        $plan= Plan::findOrFail($plan_id);
        $buildUrls= AlibabaLiveClient::liveUrlBuilder($plan->channel->name, $plan->room->name);
        $pull= $buildUrls['pull'];

        return $this->success($pull);
    }

}