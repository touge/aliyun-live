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
use Touge\AdminAliyunLive\Models\Plan;
use Touge\AdminAliyunLive\Models\Room;
use Touge\AdminOverwrite\Grid\Displayers\Actions;
use Touge\AdminOverwrite\Grid\Grid;

class RoomController extends BaseAdminController
{
    use HasTrait;


    /**
     * @param Request $request
     * @return mixed
     */
    public function room4channel(Request $request){
        $channel_id= $request->get('q');
        return Room::where([
            'customer_school_id'=> $this->customer_school_id(),
            'channel_id'=> $channel_id
        ])->get(['id','name as text']);
    }

    /**
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Room());
        $grid->model()
            ->where(['customer_school_id'=> $this->customer_school_id()])
            ->orderBy('id','desc');


        $grid->column('id', "#ID");
        $grid->column('channel.name', __('touge-aliyun::live.app_name'))->label('danger');
        $grid->column('name', __('touge-aliyun::live.stream_name'))->label('primary');

//        $OnlineInfo= $this->live_online_info();
//        $grid->column('app-status', __('touge-aliyun::live.status'))->display(function() use($OnlineInfo){
//            $online= false;
//            foreach((array)$OnlineInfo as $key=>$val){
//                if($val['AppName']==$this->channel->name && $val['StreamName']== $this->name){
//                    $online= true;
//                    break;
//                }
//            }
//            if($online){
//                return '<span class="label label-success" style="width: 8px;height: 8px;padding: 0;border-radius: 50%;display: inline-block;"></span> '. __('touge-aliyun::live.pushing');
//            }
//            return '<span class="label label-warning" style="width: 8px;height: 8px;padding: 0;border-radius: 50%;display: inline-block;"></span> ' . __('touge-aliyun::live.push_end');;
//        });

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
        $model= $form->model();
        $form->hidden('customer_school_id')->default($this->customer_school_id());


        $channel_options= Channel::where(['customer_school_id'=> $this->customer_school_id()])->get()->pluck('name','id');
        $form->select('channel_id', __('touge-aliyun::live.app_name'))->options($channel_options);
        $form->text('name', __('touge-aliyun::live.stream_name'))->rules('required|min:3');

        $form->disableViewCheck()->disableEditingCheck()->disableCreatingCheck()->disableReset();
        $form->tools(function(Form\Tools $tools){
            $tools->disableDelete()->disableView();
        });
        return $form;
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        /**
         * 检测关联数据
         */
        $planed= Plan::where(['room_id'=> $id])->count();
        if($planed>0){
            $response = [
                'status'  => false,
                'message' => '请先删除房间中的直播计划',
            ];
            return response()->json($response);
        }


        return $this->form()->destroy($id);
    }

}
