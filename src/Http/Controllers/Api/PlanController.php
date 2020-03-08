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
    public function fetch_list()
    {
        $select_filed= ['id','channel_id', 'room_id', 'title','anchor','publish_at','end_at'];
        $_plans= Plan::where('end_at' ,'>' ,date('Y-m-d h:i:s'))->get($select_filed);

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

        $data= [
            'plan'=> [
                'id'=> $plan_id,
                'title'=> $plan->title,
                'anchor'=> $plan->anchor,
                'publish_at'=> $plan->publish_at
            ],
            'urls'=> $pull
        ];

        return $this->success($data);
    }

}