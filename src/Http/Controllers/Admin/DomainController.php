<?php

namespace Touge\AdminAliyunLive\Http\Controllers\Admin;

use Encore\Admin\Show;
use Touge\AdminAliyunLive\Models\Domain;
use Touge\AdminOverwrite\Grid\Displayers\Actions;
use Touge\AdminAliyunLive\Http\Controllers\BaseAdminController;

use Touge\AdminOverwrite\Grid\Grid;


/**
 * 阿里云直播域名配置
 * Class DomainController
 * @package Touge\AdminAliyunLive\Http\Controllers\Admin
 */
class DomainController extends BaseAdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '域名列表';


    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Domain());

        $grid->column('url')->label('success');
        $grid->column('auth_key')->label('primary');
        $grid->column('expire')->label('default');


        $grid->disableExport()->disableCreateButton()
            ->disableColumnSelector()
            ->disableFilter()
            ->disableRowSelector();

        $grid->actions(function(Actions $actions){
            $actions->disableEdit()->disableDelete();
            $actions->setResource('domain/' . urlencode($actions->row->url));
        });

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $self= $this;

        $show = new Show( (new Domain())->findOrFail($id) );

        $show->field('url');
        $show->field('auth_key');
        $show->field('expire');


        $show->panel()->tools(function(Show\Tools $tools){
            $tools->disableDelete()->disableEdit();
        });

        return $show;
    }

}
