<?php
/**
 * Created by PhpStorm.
 * User: nick
 * Date: 2020-03-02
 * Time: 18:39
 */

namespace Touge\AdminAliyunLive\Http\Controllers\Admin;


use Encore\Admin\Show;
use Touge\AdminAliyunLive\Http\Controllers\BaseAdminController;
use Touge\AdminAliyunLive\Models\Channel;
use Touge\AdminAliyunLive\Models\Plan;
use Encore\Admin\Form;
use Touge\AdminAliyunLive\Models\Room;
use Touge\AdminAliyunLive\Supports\AlibabaLiveClient;
use Touge\AdminAliyunLive\Supports\Shows\PullUrls;
use Touge\AdminAliyunLive\Supports\Shows\PushUrl;
use Touge\AdminOverwrite\Grid\Grid;

class PlanController extends BaseAdminController
{
    use HasTrait;

    protected $title = '直播计划';

    protected $__= [
        'status'=> '状态',
        'channel'=> '频道',
        'room'=> '房间',
        'title'=> '名称',
        'anchor'=> '主讲',
        'publish_at'=> '计划时间',
    ];

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $self= $this;

        $show = new Show(Plan::findOrFail($id));
        $model= $show->getModel();


        $show->field('channel-name', $this->__['channel'])->as(function(){
            return $this->channel->name;
        });
        $show->field('room-name', $this->__['room'])->as(function(){
            return $this->room->name;
        });
        $show->field('publish_at', $this->__['publish_at']);

        /**
         * 当前频道，房间是否在线
         */
        $online_status= false;
        $options= [
            'AppName'=> $model->channel->name,
            'StreamName'=> $model->room->name
        ];
        $live_online_infos= $self->live_online_info($options);
        foreach($live_online_infos as $key=>$val){
            if($val['AppName'] == $model->channel->name && $val['StreamName'] == $model->room->name)
            {
                $online_status= true;
                break;
            }
        }

        $show->field('online-status', $this->__['status'])->as(function() use($online_status){
            if($online_status){
                return '<span class="label label-success" style="width: 8px;height: 8px;padding: 0;border-radius: 50%;display: inline-block;"></span> 推流中';
            }
            return '<span class="label label-warning" style="width: 8px;height: 8px;padding: 0;border-radius: 50%;display: inline-block;"></span> 已下线';
        })->label('default');


        $buildUrls= AlibabaLiveClient::liveUrlBuilder($model->channel->name, $model->room->name);
        Show::extend('pull_urls', PullUrls::class);
        $show->field('PULL-URLS', '拉流地址')->pull_urls($buildUrls['pull']);
        
        Show::extend('push_url', PushUrl::class);
        $show->field('PUSH-URL', '推流地址')->push_url($buildUrls['push']);

        $show->panel()->tools(function(Show\Tools $tools){
            $tools->disableDelete()->disableEdit();
        });

        return $show;
    }

    protected function grid(){
        $grid = new Grid(new Plan());

        $grid->column('anchor', $this->__['anchor'])->label('default');
        $grid->column('title', $this->__['title'])->label('warning');
        $grid->column('channel.name', $this->__['channel'])->label('success');
        $grid->column('room.name', $this->__['room'])->label('primary');
        $grid->column('publish_at', $this->__['publish_at']);

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


        $grid->disableExport()
            ->disableColumnSelector()
            ->disableFilter()
            ->disableRowSelector();

        return $grid;
    }



    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Plan());


        $channel_options= Channel::all()->pluck('name', 'id');
        $form->select('channel_id', $this->__['channel'])
            ->options($channel_options)
            ->load('room_id', admin_url('admin-aliyun-live/room/rooms4channel'));


        $room_options= $this->fixEditOptions(request('plan'));
        $form->select('room_id', $this->__['room'])->options($room_options);


        $form->text('title', $this->__['title']);
        $form->text('anchor', $this->__['anchor']);
        $form->datetime('publish_at', $this->__['publish_at']);

        $form->disableViewCheck()->disableEditingCheck()->disableCreatingCheck()->disableReset();
        $form->tools(function(Form\Tools $tools){
            $tools->disableDelete()->disableView();
        });



        return $form;
    }


    /**
     * 修正当为编辑时子联动没有数据
     * @return array|null
     */
    protected function fixEditOptions($plan='null'){
        $options = [];

        if($plan){
            $channel_id = Plan::find($plan)->channel_id;
            $options = Room::where(['channel_id'=> $channel_id])->get()->pluck('name','id');
            return $options;
        }
        return $options;
    }
}