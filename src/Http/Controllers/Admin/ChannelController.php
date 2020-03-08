<?php

namespace Touge\AdminAliyunLive\Http\Controllers\Admin;

use Encore\Admin\Form;
use Encore\Admin\Show;
use Illuminate\Http\Request;
use Touge\AdminAliyunLive\Models\Channel;
use Touge\AdminAliyunLive\Models\LiveUrl;
use Touge\AdminAliyunLive\Http\Controllers\BaseAdminController;


use Touge\AdminAliyunLive\Supports\AlibabaLiveClient;
use Touge\AdminAliyunLive\Supports\Shows\PullUrls;


use Touge\AdminAliyunLive\Supports\Shows\PushUrl;
use Touge\AdminOverwrite\Grid\Displayers\Actions;
use Touge\AdminOverwrite\Grid\Grid;


/**
 * 阿里云直播域名配置
 * Class DomainController
 * @package Touge\AdminAliyunLive\Http\Controllers\Admin
 */
class ChannelController extends BaseAdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '频道设置';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Channel());
        $grid->model()->orderBy('id','desc');

        $grid->column('name', __('touge-aliyun::live.app_name'))->label('danger');

        $grid->column('pull_url', __('touge-aliyun::live.pull_url'));
        $grid->column('push_url', __('touge-aliyun::live.push_url'));


        $grid->disableRowSelector()
            ->disableFilter()
            ->disableExport()
            ->disableColumnSelector();
        $grid->actions(function(Actions $action){
           $action->disableView();
        });

        return $grid;
    }




    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $domain= config('touge-aliyun-live.domain');

        $form = new Form(new Channel());
        $form->text('name',  __('touge-aliyun::live.app_name'))->rules('required|min:3');

        $form->text('pull_url', __('touge-aliyun::live.pull_url'))->default($domain['pull']['url'])->readonly();
        $form->text('push_url', __('touge-aliyun::live.push_url'))->default($domain['push']['url'])->readonly();

        $form->disableViewCheck()->disableEditingCheck()->disableCreatingCheck()->disableReset();
        $form->tools(function(Form\Tools $tools){
           $tools->disableDelete()->disableView();
        });


        return $form;
    }

}
