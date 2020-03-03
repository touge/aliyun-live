<?php
/**
 * Created by PhpStorm.
 * User: nick
 * Date: 2019-12-18
 * Time: 17:18
 */
namespace Touge\AdminAliyunLive\Http\Controllers;

use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Layout\Content;

class BaseAdminController extends AdminController
{

    /**
     * 面包屑
     * @var array
     */
    protected $breadcrumb= [];

    /**
     * 插入面条屑
     *
     * @param $breadcrumb
     * @return $this
     */
    protected function push_breadcrumb($breadcrumb)
    {
        array_push($this->breadcrumb, $breadcrumb);
        return $this;
    }

    /**
     * 设置面包屑
     * @param Content $content
     *
     * @return $this
     */
    protected function set_breadcrumb(Content $content)
    {
        $content->breadcrumb(...$this->breadcrumb);
        return $this;
    }
}