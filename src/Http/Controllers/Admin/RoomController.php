<?php
/**
 * Created by PhpStorm.
 * User: nick
 * Date: 2020-03-02
 * Time: 19:14
 */

namespace Touge\AdminAliyunLive\Http\Controllers\Admin;


use Encore\Admin\Form;
use Illuminate\Http\Request;
use Touge\AdminAliyunLive\Http\Controllers\BaseAdminController;
use Touge\AdminAliyunLive\Models\Channel;
use Touge\AdminAliyunLive\Models\Room;
use Touge\AdminAliyunLive\Supports\AlibabaLiveClient;
use Touge\AdminOverwrite\Grid\Displayers\Actions;
use Touge\AdminOverwrite\Grid\Grid;

class RoomController extends BaseAdminController
{
    use HasTrait;

    protected $__= [
        'status'=> '状态',
        'title'=> '直播名称',
        'app_name'=> '频道',
        'stream_name'=> '房间',
        'push_url'=> '推流域名',
        'pull_url'=> '拉流域名',
        'publish_at'=> '计划时间',
    ];


    /**
     * @param Request $request
     * @return mixed
     */
    public function room4channel(Request $request){
        $channel_id= $request->get('q');
        return Room::where(['channel_id'=> $channel_id])->get(['id','name as text']);
    }

    /**
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Room());
        $grid->model()->orderBy('id','desc');


        $grid->column('id', "#ID");
        $grid->column('name', $this->__['stream_name'])->label('primary');
        $grid->column('channel.name', $this->__['app_name'])->label('danger');

        $OnlineInfo= $this->live_online_info();
        $grid->column('app-status', $this->__['status'])->display(function() use($OnlineInfo){
            $online= false;
            foreach((array)$OnlineInfo as $key=>$val){
                if($val['AppName']==$this->app_name && $val['StreamName']== $this->stream_name){
                    $online= true;
                    break;
                }
            }
            if($online){
                return '<span class="label label-success" style="width: 8px;height: 8px;padding: 0;border-radius: 50%;display: inline-block;"></span> 推流中';
            }
            return '<span class="label label-warning" style="width: 8px;height: 8px;padding: 0;border-radius: 50%;display: inline-block;"></span> 已下线';
        });

        $grid->disableRowSelector()
            ->disableFilter()
            ->disableExport()
            ->disableColumnSelector();
        $grid->actions(function(Actions $actions){
           $actions->disableView();
        });

        return $grid;
    }

    /**
     * @return Form
     */
    protected function form(){
        $form = new Form(new Room());

        $channel_options= Channel::all()->pluck('name','id');
        $form->select('channel_id', $this->__['app_name'])->options($channel_options);
        $form->text('name', $this->__['stream_name'])->rules('required|min:3');

        $form->disableViewCheck()->disableEditingCheck()->disableCreatingCheck()->disableReset();
        $form->tools(function(Form\Tools $tools){
            $tools->disableDelete()->disableView();
        });


        return $form;
    }
}