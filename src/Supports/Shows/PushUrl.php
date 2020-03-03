<?php
/**
 * Created by PhpStorm.
 * User: nick
 * Date: 2020-03-02
 * Time: 16:50
 */

namespace Touge\AdminAliyunLive\Supports\Shows;


use Encore\Admin\Show\AbstractField;
use Encore\Admin\Widgets\Box;
use Encore\Admin\Widgets\Table;

class PushUrl extends AbstractField
{
    public $escape = false;
    public $border = true;
    /**
     * @param string $qid
     * @return Table|mixed
     */
    public function render($qid='')
    {
        return $this->box($qid);
    }


    /**
     *
     * @param $question_id
     * @return string
     */
    protected function box($urls): string
    {
        $headers = [];
        $rows = [
            '服务器'   => $urls['url'],
            '密钥'    => $urls['auth_key'],
        ];
        $table = new Table($headers, $rows);
        return $table;
    }

}