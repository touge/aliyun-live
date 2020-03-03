<?php
/**
 * Created by PhpStorm.
 * User: nick
 * Date: 2020-03-03
 * Time: 10:19
 */

namespace Touge\AdminAliyunLive\Http\Controllers\Admin;


use Touge\AdminAliyunLive\Supports\AlibabaLiveClient;

trait HasTrait
{
    /**
     * @param $params
     * @return mixed
     */
    public function live_online_info(array $params= [])
    {
        $options= ['DomainName'=> config('touge-aliyun-live.domain.push.url')];
        if ($params){
            $options= array_merge($options, $params);
        }

        $response= AlibabaLiveClient::DescribeLiveStreamsOnlineList($options);
        $OnlineInfo= $response['data']['OnlineInfo']['LiveStreamOnlineInfo'];
        return $OnlineInfo;
    }
}