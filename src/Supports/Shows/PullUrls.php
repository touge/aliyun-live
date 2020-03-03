<?php
/**
 * Created by PhpStorm.
 * User: nick
 * Date: 2019-11-20
 * Time: 16:04
 */

namespace Touge\AdminAliyunLive\Supports\Shows;


use Encore\Admin\Show\AbstractField;
use Encore\Admin\Widgets\Table;



class PullUrls extends AbstractField
{
    public $escape = false;
    public $border = true;

    /**
     * @param string $qid
     * @return Table|mixed
     */
    public function render($pull_urls='')
    {
        return $this->table($pull_urls);
    }

    /**
     *
     * @param $question_id
     * @return string
     */
    protected function table($pull_urls): string
    {
        $rows= [];
        foreach((array)$pull_urls as $key=>$val){
            $row= [
                'type'=> $val['type'],
                'url'=> "<span class='label label-success' style='word-break: break-all;white-space: normal;'>" . $val['url'] . "</span>"
            ];
            array_push($rows,$row);
        }
        $table= new Table(['类型','地址'], array_values($pull_urls));

        return $table;
    }

}