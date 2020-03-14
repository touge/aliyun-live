<?php
/**
 * Created by PhpStorm.
 * User: nick
 * Date: 2020-02-29
 * Time: 12:26
 */

namespace Touge\AdminAliyunLive\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;


/**
 * Class Domain
 * @package Touge\AdminAliyunLive\Models
 */
class Domain extends Model
{

    /**
     * @throws \Touge\AdminAliyunLive\Exceptions\ResponseFailedException
     */
    public function paginate(){
        $page_data= array_values(config('touge-aliyun-live.domain'));

        extract($page_data);
        $domains = static::hydrate($page_data);

        $paginator = new LengthAwarePaginator($domains, 2, 2);
        $paginator->setPath(url()->current());

        return $paginator;
    }

    public static function with($relations)
    {
        return new static;
    }

    // 覆盖`orderBy`来收集排序的字段和方向
    public function orderBy($column, $direction = 'asc')
    {
    }

    // 覆盖`where`来收集筛选的字段和条件
    public function where($column, $operator = null, $value = null, $boolean = 'and')
    {
    }


    // 获取单项数据展示在form中
    public function findOrFail($url)
    {
//        $options= [
//            'DomainName' => $DomainName
//        ];
//        $response= AlibabaLiveClient::DescribeLiveDomainDetail($options);
//
//        if($response['status']=='failed'){
//            return $this->newFromBuilder([]);
//        }
//
//
//        $data= $response['data']['DomainDetail'];


        $data= array_values(config('touge-aliyun-live.domain'));
        $url= urldecode($url);

        foreach((array)$data as $key=>$val){
            if($val['url']===$url){
                return $this->newFromBuilder($val);
            }
        }
        return [];

    }

}