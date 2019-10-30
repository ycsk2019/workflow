<?php


namespace Ycsk\Definedform;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Ycsk\Definedform\Exception\ParameterException;
use Ycsk\Definedform\Exception\ProcessException;
use Ycsk\Definedform\Modules\Definedform\Models\Process;
use Ycsk\Definedform\Modules\Definedform\Models\ProcessInstance;
use Ycsk\Definedform\Modules\Definedform\Models\ProcessNodeInstance;
use Ycsk\Definedform\Modules\Definedform\Models\ProcessNodeLink;

class Workflow extends ServiceProvider
{
    /**
     * 总流程id
     * @var int
     */
    private $processId = 0;
    private $process = null;

    public function __construct($processId)
    {
        $this->processId = $processId;

        $this->checkProcess();
    }

    /**
     * 检查process
     * @throws ParameterException
     */
    private function checkProcess()
    {
        $process = Process::find($this->processId);
        if (empty($process)) {
            throw new ParameterException('[processId]不存在');
        }
        $this->process = $process;
    }

    /**
     * 创建流程
     * @return array
     */
    public function create()
    {
        //添加任务实例
        $instanceId = ProcessInstance::insertGetId([
            'process_id'   => $this->processId,
            'title'        => $this->process->title,
            'status'       => 1,
            'is_completed' => 0,
            'is_stoped'    => 0,
        ]);

        return [
            'process_id'          => $this->processId,
            'process_instance_id' => $instanceId,
            'title'               => $this->process->title,
        ];
    }

    /**
     * 开始任务，返回当前在流程
     * @param $processInstanceId
     * @return array
     * @throws ParameterException
     * @throws ProcessException
     */
    public function start($processInstanceId)
    {
        $hasStart = ProcessNodeInstance::where('process_instance_id', $processInstanceId)->first();
        if (!empty($hasStart)) {
            throw new ProcessException('任务已经在进行中，不能再开始', ProcessException::PROCESS_STARTING);
        }
        $startNode     = current($this->nextNodes($processInstanceId, 0));//开始节点只有一个
        $nodeInstanceIds = $this->run($processInstanceId, $startNode, []);
        $nextNodes = [];
        foreach ($nodeInstanceIds as $k => $nodeInstanceId) {
            $nextNodes[] = ProcessNodeInstance::find($nodeInstanceId)->toArray();
        }
        return $nextNodes;
    }

    /**
     * 完成当前节点，并且走向下个节点
     */
    public function complateAndToNextNode($nodeInstanceId, $params = [])
    {
        $nodeInstance = ProcessNodeInstance::find($nodeInstanceId);
        if (empty($nodeInstance)) {
            throw new ParameterException('[node_item_id]参数错误');
        }
        if ($nodeInstance->is_completed == 1) {
            throw new ProcessException('该流程已经已经完成', ProcessException::PROCESS_COMPLATED);
        }
        ProcessNodeInstance::where('id', $nodeInstanceId)->update([
            'status'       => 2,
            'is_completed' => 1,
            'complete_at'  => date('Y-m-d H:i:s', time()),
        ]);
        $nodes     = $this->nextNodes($nodeInstance->process_instance_id, $nodeInstance->node_id);
        $nextNodes = [];
        foreach ($nodes as $k => $v) {
            $nodeInstanceIds = $this->run($nodeInstance->process_instance_id, $v, $params);
            if (!is_array($nodeInstanceIds)) {
                $nodeInstanceIds = [$nodeInstanceIds];
            }
            foreach ($nodeInstanceIds as $k => $nodeInstanceId) {
                if ($nodeInstanceId) {
                    $res = ProcessNodeInstance::find($nodeInstanceId);
                    if ($res) {
                        $nextNodes[] = $res;
                    }
                }
            }
        }
        return $nextNodes;
    }

    /**
     * 查找当前任务的下个走向节点
     * @param     $processInstanceId
     * @param int $nodeId
     * @return array
     * @throws ParameterException
     */
    public function nextNodes($processInstanceId, $nodeId = 0)
    {
        $processInstance = ProcessInstance::find($processInstanceId);
        if (empty($processInstance)) {
            throw new ParameterException('[process_instance_id]错误');
        }
        $where = [
                'process_id' => $processInstance->process_id,
                'current_id' => $nodeId,
            ];
        $links    = ProcessNodeLink::where($where)->with('next_node')->get()->toArray();
        $linksMap = [];
        foreach ($links as $k => $v) {
            $linksMap[$v['next_id']] = $v['next_node'];
        }
        return array_values($linksMap);
    }

    /**
     * 查找当前任务的上个走向节点
     * @param     $processInstanceId
     * @param int $nodeId
     * @return array
     * @throws ParameterException
     */
    public function prevNodes($processInstanceId, $nodeId = 0)
    {
        $processInstance = ProcessInstance::find($processInstanceId);
        if (empty($processInstance)) {
            throw new ParameterException('[process_instance_id]错误');
        }
        if ($nodeId == 0) {
            $links = [];
        } else {
            $links = ProcessNodeLink::where([
                'process_id' => $processInstance->process_id,
                'current_id' => $nodeId,
            ])->with('prev_node')->get()->toArray();

        }
        $linksMap = [];
        foreach ($links as $k => $v) {
            $linksMap[$v['prev_id']] = $v['prev_node'];
        }
        return array_values($linksMap);
    }
    
    /**
     * 查找当前任务节点的后续流向
     * @param     $processInstanceId
     * @param int $nodeId
     * @return array
     * @throws ParameterException
     */
    public function nextLinks($processInstanceId, $nodeId = 0)
    {
        $processInstance = ProcessInstance::find($processInstanceId);
        if (empty($processInstance)) {
            throw new ParameterException('[process_instance_id]错误');
        }
        if ($nodeId == 0) {
            $where = [
                'process_id' => $processInstance->process_id,
                'prev_id'    => 0,
            ];
        } else {
            $where = [
                'process_id' => $processInstance->process_id,
                'current_id' => $nodeId,
            ];
        }
        $links    = ProcessNodeLink::where($where)->with('next_node')->get()->toArray();
        return $links;
    }

    /**
     * 执行当前流程，返回产生的新node_instance_id数组
     * @param $processInstanceId
     * @param $node
     * @param $data
     * @return mixed
     */
    private function run($processInstanceId, $node, $data)
    {
        switch ($node['node_type']) {
            case 'event':              //事件：包括空开始事件、空结束事件、终止结束事件
                return $this->_runEvent($processInstanceId, $node, $data);
                break;
            case 'gateway':             //网关：包括唯一网关、并行网关、包含网关
                return $this->_runGateway($processInstanceId, $node, $data);
                break;
            case 'task':                //任务，包括人工任务、服务任务、脚本任务、手工任务、接收任务
                return $this->_runTask($processInstanceId, $node, $data);
                break;
//            case 'end' :
//                $this->_runEnd($processInstanceId, $node, $data);
            default:
                break;
        }
        // todo 通知项目进程下个节点
        return array();
    }

    /**
     * 执行任务
     * @param       $processInstanceId
     * @param       $node
     * @param array $data
     * @return mixed
     */
    private function _runTask($processInstanceId, $node, $data = [])
    {
        $param                     = [
            'process_id'          => $node['process_id'],
            'process_instance_id' => $processInstanceId,
            'node_id'             => $node['id'],
            'title'               => $node['title'],
        ];
        $nodeInstanceId            = ProcessNodeInstance::insertGetId($param);
        $param['node_instance_id'] = $nodeInstanceId;
        return [$nodeInstanceId];
    }


    private function _runGateway($processInstanceId, $node, $data = [])
    {
        $res  = [];
        $nextLinks = $this->nextLinks($processInstanceId, $node['id']);
        foreach ($nextLinks as $k => $link) {
            if ($this->_matchCondition($link, $data)) {
                $newNodeInstanceIds = $this->run($processInstanceId, $link['next_node'], $data);
                foreach($newNodeInstanceIds as $k2=>$newNodeInstanceId) {
                    $res[] = $newNodeInstanceId;
                }
            }
        }
        return $res;
    }

    private function _runEvent($processInstanceId, $node, $data)
    {
        $methodName = '_event_for_' . $node['node_subtype'];
        if (method_exists($this, $methodName)) {
            return $this->$methodName($processInstanceId, $node, $data);
        } else {
            throw new ProcessException('未知的节点类型', ProcessException::NODE_UNKNOW_TYPE_ERR);
        }
    }
    
    /**
     * 停止事件的处理逻辑
     * @param $processInstanceId
     * @param $node
     * @param $data
     * @return mixed
     */
    private function _event_for_stop($processInstanceId, $node, $data) {
        ProcessInstance::where('id', $processInstanceId)->update([
            'is_stoped' => 1,
            'stop_at'   => date('Y-m-d H:i:s')
        ]);
        return array();
    }
    
    /**
     * 开始事件的处理逻辑
     * @param $processInstanceId
     * @param $node
     * @param $data
     * @return mixed
     */
    private function _event_for_start($processInstanceId, $node, $data) {
        ProcessInstance::where('id', $processInstanceId)->update([
            'start_at'   => date('Y-m-d H:i:s')
        ]);
        $res  = [];
        $nextLinks = $this->nextLinks($processInstanceId, $node['id']);
        foreach ($nextLinks as $k => $link) {
            $newNodeInstanceIds = $this->run($processInstanceId, $link['next_node'], $data);
            foreach($newNodeInstanceIds as $k2=>$newNodeInstanceId) {
                $res[] = $newNodeInstanceId;
            }
        }
        return $res;
    }
    
    /**
     * 结束事件的处理逻辑
     * @param $processInstanceId
     * @param $node
     * @param $data
     * @return mixed
     */
    private function _event_for_end($processInstanceId, $node, $data) {
        ProcessInstance::where('id', $processInstanceId)->update([
            'is_completed' => 1,
            'complete_at'   => date('Y-m-d H:i:s')
        ]);
        return array();
    }

    /**
     * node条件比较,条件为空时返回true
     * @param $link
     * @param $data
     * @return mixed
     */
    private function _matchCondition($link, $data)
    {
        if (!trim($link['condition'])) {
            return true;
        }
        extract($data); //数组转变量
        @eval('$r = ' . $link['condition'] . ' ;'); //执行规则,@防止变量未定义
        return $r;
    }
}