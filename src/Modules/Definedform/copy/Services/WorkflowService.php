<?php


namespace App\Modules\Definedform\Services;

use App\Modules\Definedform\Repositories\WorkflowRepositoryInterface;
use Illuminate\Support\Facades\DB;

class WorkflowService implements WorkflowServiceInterface
{
    protected $workflowRepository;

    public function __construct(WorkflowRepositoryInterface $workflowRepository)
    {
        $this->workflowRepository = $workflowRepository;
    }

    public function start($order_id,$process_id = 1,$remark = '')
    {
        return $this->workflowRepository->start($order_id,$process_id,$remark);
    }

    public function complete($order_id,$node_id,$node_instance_id,$remark = '',$process_instance_id = 0,$condition = '',$process_id = 1,$admin_user_id = 0)
    {
        return $this->workflowRepository->complete($order_id,$node_id,$node_instance_id,$remark,$process_instance_id,$condition,$process_id,$admin_user_id);
    }

    public function task_detail($order_id,$condition = array())
    {
        return $this->workflowRepository->task_detail($order_id,$condition);
    }

    public function task_lastest_detail($order_id,$node_id = '')
    {
        return $this->workflowRepository->task_lastest_detail($order_id,$node_id);
    }

    public function node_list($where, $columns = ['*'])
    {
        return $this->workflowRepository->node_list($where, $columns );
    }

    public function node_link_list($where, $columns = ['*'])
    {
        return $this->workflowRepository->node_link_list($where, $columns);
    }

    public function lock($order_id,$process_task_id,$admin_user_id)
    {
        return $this->workflowRepository->lock($order_id,$process_task_id,$admin_user_id);
    }

    public function unlock($order_id,$process_task_id,$admin_user_id)
    {
        return $this->workflowRepository->unlock($order_id,$process_task_id,$admin_user_id);
    }

    public function process_list($where, $columns = ['*'])
    {
        return $this->workflowRepository->process_list($where, $columns);
    }
}