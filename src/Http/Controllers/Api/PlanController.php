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
        $_plans= Plan::where([
            'customer_school_id'=> $this->user()->customer_school_id,
        ])->where('end_at' ,'>' ,date('Y-m-d h:i:s'))->get($select_filed);

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
     *
     * 流列表
     *
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
     *
     * 获得当前流
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function stream(Request $request){
        $channel_name= $request->get('channel', 'the9edu');
        $room_name= $request->get('room', '001');

        $options= [
            'DomainName'=> config('touge-aliyun-live.domain.push.url'),
            'AppName'=> $channel_name,
            'StreamName'=> $room_name
        ];
        $response= AlibabaLiveClient::DescribeLiveStreamsOnlineList($options);

        $data= $response['data'];
        $streaming= $data['TotalNum']>0 ?true :false;
        if($streaming){
            $LiveStreamOnlineInfo= $data['OnlineInfo']['LiveStreamOnlineInfo'];

            /**
             * 检测当前频道是否为转码模板
             */
            $transcodeId= '';
            foreach($LiveStreamOnlineInfo as $onlineInfo){
                if ($channel_name == $onlineInfo['AppName'] && $room_name == $onlineInfo['StreamName']){
                    if (array_key_exists('TranscodeId', $onlineInfo)){
                        $transcodeId= $onlineInfo['TranscodeId'];
                        break;
                    }
                }
            }
            $buildUrls= AlibabaLiveClient::liveUrlBuilder($channel_name, $room_name, $transcodeId);
            return $this->success($buildUrls['pull']);
        }
        return $this->response('直播未开始', 'failed');
    }


    public function info(Request $request){

        $plan_id= $request->get('plan_id', 0);
        if($plan_id==0){
            return $this->failed('索引为空');
        }

        $plan= Plan::findOrFail($plan_id);
        $data = [
            'id'=> $plan_id,
            'title'=> $plan->title,
            'anchor'=> $plan->anchor,
            'channel'=> $plan->channel->name,
            'room'=> $plan->room->name,
            'publish_at'=> $plan->publish_at
        ];

        return $this->success($data);
    }

}
