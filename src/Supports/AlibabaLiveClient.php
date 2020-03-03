<?php
/**
 * Created by PhpStorm.
 * User: nick
 * Date: 2020-02-29
 * Time: 12:45
 */

namespace Touge\AdminAliyunLive\Supports;
use AlibabaCloud\Client\AlibabaCloud;
use AlibabaCloud\Client\Exception\ClientException;
use AlibabaCloud\Client\Exception\ServerException;
use Touge\AdminAliyunLive\Exceptions\ThrowError;


class AlibabaLiveClient
{
    use ThrowError;


    protected static $product= 'live';
    protected static $version= '2016-11-01';
    protected static $host= 'live.aliyuncs.com';
    protected $client= null;


    /**
     * 初始化
     */
    protected static function InitializationClient(){
        $config= config('touge-aliyun-live');
        AlibabaCloud::accessKeyClient($config['accessKeyId'], $config['accessKeySecret'])
            ->regionId('cn-hangzhou')
            ->asDefaultClient();
    }


    /**
     * @return \AlibabaCloud\Client\Request\RpcRequest
     * @throws ClientException
     */
    protected static function request_prefix(){
        static::InitializationClient();
        return AlibabaCloud::rpc()
            ->product(static::$product)
            ->version(static::$version)
            ->method('POST')
            ->host(static::$host);
    }

    /**
     * 新增域名
     *
     * @param array $params ['RegionId' => "cn-hangzhou",'LiveDomainType' => "liveVideo",'DomainName' => "pull.the9edu.com",'Region' => "cn-shanghai"]
     * @return array
     * @throws \Touge\AdminAliyunLive\Exceptions\ResponseFailedException
     */
    public static function AddLiveDomain(Array $params)
    {
        try {
            $response= static::request_prefix()
                ->options(['query'=> $params])
                ->action('AddLiveDomain')
                ->request();
            return [
                'status'=> 'successful',
                'data'=> $response->toArray()
            ];

        }catch (ClientException $exception){
            return [
                'status'=> 'failed',
                'message'=> $exception->getErrorMessage(),
                'code'=> $exception->getErrorCode()
            ];
        }catch (ServerException $exception){
            return [
                'status'=> 'failed',
                'message'=> $exception->getErrorMessage(),
                'code'=> $exception->getErrorCode()
            ];
        }
    }


    /**
     * 获得域名列表
     *
     * @param array $params
     * @return mixed
     * @throws \Touge\AdminAliyunLive\Exceptions\ResponseFailedException
     */
    public static function DescribeLiveUserDomains(Array $params)
    {
        try {
            $response= static::request_prefix()
                ->options(['query'=> $params])
                ->action('DescribeLiveUserDomains')
                ->request();
            return $response->toArray();
        }catch (ClientException $exception){
            return [
                'status'=> 'failed',
                'message'=> $exception->getErrorMessage(),
                'code'=> $exception->getErrorCode()
            ];
        }catch (ServerException $exception){
            return [
                'status'=> 'failed',
                'message'=> $exception->getErrorMessage(),
                'code'=> $exception->getErrorCode()
            ];
        }
    }


    /**
     * 调用DescribeLiveDomainDetail获取指定直播域名配置的基本信息。
     */
    public static function DescribeLiveDomainDetail(Array $params)
    {
        try {
            $response = static::request_prefix()
                ->options(['query' => $params])
                ->action('DescribeLiveDomainDetail')
                ->request();
            return [
                'status'=> 'successful',
                'data'=> $response->toArray()
            ];
        } catch (ClientException $exception) {
            return [
                'status'=> 'failed',
                'message'=> $exception->getErrorMessage(),
                'code'=> $exception->getErrorCode()
            ];
        } catch (ServerException $exception) {
            return [
                'status'=> 'failed',
                'message'=> $exception->getErrorMessage(),
                'code'=> $exception->getErrorCode()
            ];
        }
    }


    /**
     * 调用DeleteLiveDomain删除已添加的直播域名。
     * @param array $params
     * @return mixed
     * @throws \Touge\AdminAliyunLive\Exceptions\ResponseFailedException
     */
    public static function DeleteLiveDomain(Array $params)
    {
        try {
            $response = static::request_prefix()
                ->options(['query' => $params])
                ->action('DeleteLiveDomain')
                ->request();
            return $response->toArray();
        } catch (ClientException $exception) {
            return [
                'status'=> 'failed',
                'message'=> $exception->getErrorMessage(),
                'code'=> $exception->getErrorCode()
            ];
        } catch (ServerException $exception) {
            return [
                'status'=> 'failed',
                'message'=> $exception->getErrorMessage(),
                'code'=> $exception->getErrorCode()
            ];
        }
    }


    /**
     * 指定域名下（或者指定域名下某个应用）的所有正在推的流的信息
     * @param array $params
     * @return array
     */
    public static function DescribeLiveStreamsOnlineList(array $params){
        try {
            $response = static::request_prefix()
                ->options(['query' => $params])
                ->action('DescribeLiveStreamsOnlineList')
                ->request();
            return [
                'status'=> 'successful',
                'data'=> $response->toArray()
            ];
        } catch (ClientException $exception) {
            return [
                'status'=> 'failed',
                'message'=> $exception->getErrorMessage(),
                'code'=> $exception->getErrorCode()
            ];
        } catch (ServerException $exception) {
            return [
                'status'=> 'failed',
                'message'=> $exception->getErrorMessage(),
                'code'=> $exception->getErrorCode()
            ];
        }
    }


    /**
     * 直播地址生成
     * @param $app_name
     * @param $stream_name
     * @return array
     */
    public static function liveUrlBuilder($app_name, $stream_name)
    {
        return [
            'push'=> static::__pushLiveUrlBuilder($app_name, $stream_name),
            'pull'=> static::__pullLiveUrlBuilder($app_name, $stream_name),
        ];
    }

    /**
     * @param $app_name
     * @param $stream_name
     * @return array
     */
    protected static function __pushLiveUrlBuilder($app_name, $stream_name)
    {
        $config= config('touge-aliyun-live.domain');

        /**
         * 推流配置
         */
        $push_cdn = $config['push']['url'];
        $push_key = $config['push']['auth_key'];
        $push_expire_time = time() + $config['push']['expire'];

        //推流地址
        $strpush = "/{$app_name}/{$stream_name}-{$push_expire_time}-0-0-{$push_key}";

        return [
            'url'=> "rtmp://{$push_cdn}/{$app_name}/",
            'auth_key'=> "{$stream_name}?auth_key={$push_expire_time}-0-0-".md5($strpush),
        ];
    }

    /**
     * @param $config
     * @param $app_name
     * @param $stream_name
     *
     * @return array
     */
    protected static function __pullLiveUrlBuilder($app_name, $stream_name)
    {
        $config= config('touge-aliyun-live.domain');
        /**
         * 拉游配置
         */
        $pull_cdn = $config['pull']['url'];
        $pull_key = $config['pull']['auth_key'];
        $pull_expire_time = time() + $config['pull']['expire'];

        $strviewrtmp    = "/{$app_name}/{$stream_name}-{$pull_expire_time}-0-0-{$pull_key}";
        $strviewflv     = "/{$app_name}/{$stream_name}.flv-{$pull_expire_time}-0-0-{$pull_key}";
        $strviewm3u8    = "/{$app_name}/{$stream_name}.m3u8-{$pull_expire_time}-0-0-{$pull_key}";

        return [
            'rtmp'=> [
                'type'=> 'rtmp',
                'url'=> "rtmp://{$pull_cdn}/{$app_name}/{$stream_name}?auth_key={$pull_expire_time}-0-0-".md5($strviewrtmp)
            ],
            'flv'=> [
                'type'=> 'flv',
                'url'=> "http://{$pull_cdn}/{$app_name}/{$stream_name}.flv?auth_key={$pull_expire_time}-0-0-".md5($strviewflv)
            ],
            'm3u8'=> [
                'type'=> 'm3u8',
                "url"=> "http://{$pull_cdn}/{$app_name}/{$stream_name}.m3u8?auth_key={$pull_expire_time}-0-0-".md5($strviewm3u8)
            ]
        ];
    }


}