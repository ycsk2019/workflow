<?php


namespace Ycsk\Definedform\Modules\Definedform\Repositories;

use Ycsk\Definedform\Modules\Definedform\Models\Process;
use Ycsk\Definedform\Modules\Definedform\Models\ProcessNode;
use Ycsk\Definedform\Modules\Definedform\Models\ProcessNodeLink;
use Ycsk\Definedform\Modules\Definedform\Models\ProcessTask;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Ycsk\Definedform\Workflow;

class WorkflowRepository implements WorkflowRepositoryInterface
{
    const is_completed = 1;
    const is_not_completed = 0;
    const is_locked = 1;
    const is_not_locked = 0;

    public function __construct()
    {
    }

    /**
     * 创建工作流
     *
     * @param  $order_id    订单ID
     * @return Response
     */
    public function start($order_id,$process_id = 1,$remark = '')
    {
        try{
            $workflow = new Workflow($process_id);
            $process_instance = $workflow->create();
            $node_instance = $workflow->start($process_instance['process_instance_id']);
            foreach($node_instance as $k => $v){
                //保存订单的当前工作流状态
                $processTask = new ProcessTask();
                $processTask->process_id = $process_id;
                $processTask->process_instance_id = $process_instance['process_instance_id'];
                $processTask->node_instance_id = $v->id;
                $processTask->node_id = $v->node_id;
                $processTask->order_id = $order_id;
                $processTask->node_title = $v->title;
                $processTask->status = $v->status;
                $processTask->remark = $remark;

                $processNode = ProcessNode::where('node_id',$v->node_id)->first();
                $processTask->wap_title = $processNode->wap_title;
                $processTask->wap_line_title = $processNode->wap_line_title;

                $processTask->save();
            }

            if($workflow && $process_instance && $node_instance && $processTask){
                $result = array(
                    'error_no' => 200,
                    'msg' => '创建成功',
                    'node_instance' => $node_instance
                );
                return $result;
            }

        }catch (\Exception $e){
            $result = array(
                'error_no' => 1000010,
                'msg' => '创建失败'
            );
            return $result;
        }
    }

    /**
     * 完成并进入下一节点
     *
     * @param  $order_id    订单ID
     * @param  $node_id    节点类别ID
     * @param  $node_instance_id    节点ID
     * @param  $remark    审核备注
     * @param  $process_instance_id    流程实例ID
     * @param  $condition    工作流跳转条件数组
     * @param  $admin_user_id    操作用户ID
     * @return Response
     */
    public function complete($order_id,$node_id,$node_instance_id,$remark = '',$process_instance_id = 0,$condition = '',$process_id = 1,$admin_user_id = 0)
    {
        try{
            $Node = ProcessNode::where('node_id',$node_id)->first();

            $search_condition = array(
                'order_id'=>$order_id,
                'node_instance_id'=>$node_instance_id,
                'is_completed'=>self::is_not_completed
            );
            $Task = ProcessTask::where($search_condition)->first();

            if ($Node->need_lock == 1){
                if (!$Task){
                    $result = array(
                        'error_no' => 404,
                        'msg' => '任务不存在'
                    );
                    return $result;
                }
                elseif ($Task->is_locked == self::is_not_locked){
                    $result = array(
                        'error_no' => 1000011,
                        'msg' => '操作失败，请先认领任务'
                    );
                    return $result;
                }
                elseif ($Task->admin_user_id != $admin_user_id){
                    $result = array(
                        'error_no' => 1000012,
                        'msg' => '您不是该任务的认领人'
                    );
                    return $result;
                }

            }
            $key = 'option_'.intval($node_id);

            $Task->is_completed = self::is_completed;
            $Task->completed_at = Carbon::now();
            $Task->remark = $remark ? $remark : '';
            $Task->option = isset($condition[$key]) ? $condition[$key] : 0;
            $Task->save();

            $workflow = new Workflow($process_id);
            $node_instance = $workflow->complateAndToNextNode($node_instance_id,$condition);



            foreach($node_instance as $k => $v){
                //保存订单的当前工作流状态
                $processTask = new ProcessTask();
                $processTask->process_id = $process_id;
                $processTask->process_instance_id = $process_instance_id;
                $processTask->node_instance_id = $v->id;
                $processTask->node_id = $v->node_id;
                $processTask->order_id = $order_id;
                $processTask->node_title = $v->title;
                $processTask->status = $v->status;

                $processNode = ProcessNode::where('node_id',$v->node_id)->first();
                $processTask->wap_title = $processNode->wap_title;
                $processTask->wap_line_title = $processNode->wap_line_title;
                $processTask->last_option = isset($condition[$key]) ? $condition[$key] : 0;

                $processTask->save();
            }

            if($workflow && $node_instance && $processTask){
                $result = array(
                    'error_no' => 200,
                    'msg' => '成功'
                );
                return $result;
            }
            else{
                $result = array(
                    'error_no' => 1000013,
                    'msg' => '请求失败，完成任务时出错'
                );
                return $result;
            }

        }catch (\Exception $e){
            $result = array(
                'error_no' => 1000014,
                'msg' => '请求失败，完成任务时出错'
            );
            return $result;
        }
    }

    /**
     * 查看订单详情
     *
     * @param  $order_id    订单ID
     * @return Response
     */
    public function task_detail($order_id,$condition = array())
    {
        $condition = $condition;
        $condition['order_id'] = $order_id;
        $processTask = ProcessTask::where($condition)->get()->toArray();
        return $processTask;
    }

    /**
     * 查看当前订单状态
     *
     * @param  $order_id    订单ID
     * @param  $node_id    节点状态
     * @return Response
     */
    public function task_lastest_detail($order_id,$node_id = '')
    {
        $condition = array(
            'order_id'=>$order_id,
            'is_completed'=>self::is_not_completed,
        );
        if ($node_id != ''){
            $condition['node_id'] = $node_id;
        }
        $processTask = ProcessTask::select('node_instance_id','node_id','process_instance_id')->where($condition)->get()->toArray();
        return $processTask;
    }

    /**
     * 查看节点列表
     *
     * @return Response
     */
    public function node_list($where, $columns = ['*'])
    {
        return ProcessNode::select($columns)->where($where)->get();
    }

    /**
     * 查看节点列表
     *
     * @return Response
     */
    public function node_link_list($where, $columns = ['*'])
    {
        return ProcessNodeLink::select($columns)->where($where)->get();
    }

    /**
     * 认领任务
     *
     * @param  $order_id    订单ID
     * @param  $process_task_id    任务ID
     * @param  $admin_user_id    用户ID
     * @return Array
     */
    public function lock($order_id,$process_task_id,$admin_user_id)
    {
        $condition = array(
            'id'=>$process_task_id,
            'order_id'=>$order_id,
            'is_completed'=>self::is_not_completed,
        );
        DB::beginTransaction();
        $processTask = ProcessTask::where($condition)->first();
        if (!$processTask){
            $result = array(
                'error_no' => 404,
                'msg' => '任务不存在'
            );
            DB::rollBack();
        }
        elseif ($processTask->is_locked == self::is_locked){
            $result = array(
                'error_no' => 1000001,
                'msg' => '任务已被认领'
            );
            DB::rollBack();
        }
        else{
            $processTask->is_locked = self::is_locked;
            $processTask->claimed_at = Carbon::now();
            $processTask->admin_user_id = $admin_user_id;
            $r = $processTask->save();
            $result = array(
                'error_no' => $r,
                'msg' => '认领成功'
            );
            DB::commit();
        }
        return $result;
    }

    /**
     * 退领任务
     *
     * @param  $order_id    订单ID
     * @param  $process_task_id    任务ID
     * @param  $admin_user_id    用户ID
     * @return Array
     */
    public function unlock($order_id,$process_task_id,$admin_user_id)
    {
        $condition = array(
            'id'=>$process_task_id,
            'order_id'=>$order_id,
            'is_completed'=>self::is_not_completed,
        );
        DB::beginTransaction();
        $processTask = ProcessTask::where($condition)->first();
        if (!$processTask){
            $result = array(
                'error_no' => 404,
                'msg' => '任务不存在'
            );
            DB::rollBack();
        }
        elseif ($processTask->is_locked == self::is_not_locked){
            $result = array(
                'error_no' => 1000002,
                'msg' => '任务未被认领'
            );
            DB::rollBack();
        }
        elseif ($processTask->admin_user_id != $admin_user_id){
            $result = array(
                'error_no' => 1000003,
                'msg' => '任务已被其他人认领'
            );
            DB::rollBack();
        }
        else{
            $processTask->is_locked = self::is_not_locked;
            $processTask->claimed_at = NULL;
            $processTask->admin_user_id = 0;
            $r = $processTask->save();
            $result = array(
                'error_no' => $r,
                'msg' => '退领成功'
            );
            DB::commit();
        }
        return $result;
    }

    /**
     * 查看工作流列表
     *
     * @param $data
     * @param array $columns
     * @return \Illuminate\Support\Collection
     */
    public function process_list($where, $columns = ['*'])
    {
        return Process::select($columns)->where($where)->with('process_node')->get();
    }

    public function all($columns = ['*'])
    {
        // TODO: Implement all() method.
    }

    public function paginate($perPage = 15, $columns = ['*'])
    {
        // TODO: Implement paginate() method.
    }

    public function create(array $data)
    {
        // TODO: Implement create() method.
    }

    public function save(array $data)
    {
        // TODO: Implement save() method.
    }

    public function delete($id)
    {
        // TODO: Implement delete() method.
    }

    public function update(array $data)
    {
        // TODO: Implement update() method.
    }

    public function updateBy(array $data, $field, $value, $columns = ['*'])
    {
        // TODO: Implement updateBy() method.
    }

    public function updateWhere(array $data, $where, $columns = ['*'])
    {
        // TODO: Implement updateWhere() method.
    }

    public function find($id, $columns = ['*'])
    {
        // TODO: Implement find() method.
    }

    public function findBy($field, $value, $columns = ['*'])
    {
        // TODO: Implement findBy() method.
    }

    public function findWhere($where, $columns = ['*'])
    {
        // TODO: Implement findWhere() method.
    }

    public function findFirstBy($field, $value, $columns = ['*'])
    {
        // TODO: Implement findFirstBy() method.
    }

    public function findFirstWhere($where, $columns = ['*'])
    {
        // TODO: Implement findFirstWhere() method.
    }

}