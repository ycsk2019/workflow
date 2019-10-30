<?php


namespace App\Modules\Definedform\Controllers;

use App\Modules\Definedform\Services\FormMenuServiceInterface;
use Illuminate\Http\Request;
use App\Modules\Definedform\Helpers\ApiResponse;

class FormMenuController extends Controller
{
    protected $formMenuService;

    public function __construct(FormMenuServiceInterface $formMenuService)
    {
        $this->formMenuService = $formMenuService;
    }

    /**
     * 查询表单菜单设计列表
     *
     * @return list
     */
    public function index(){
        $result = $this->formMenuService->lists();
        ApiResponse::output($result);
    }

    /**
     * 查询表单菜单设计详情
     *
     * @return
     */
    public function detail(Request $request){
        $result = $this->formMenuService->findAttach($request->input('id'));
        ApiResponse::output($result);
    }

    /**
     * 创建表单菜单设计
     *
     */
    public function create(Request $request)
    {
        //TODO 创建和修改时都需要修改菜单和工作流关联关系
        $this->validate($request, [
            'name' => "string|required",
            'type' => "int|required|between:1,2",
            'workflow_info' => "string|required",
            'level' => "int|required",
            'parent_id' => "int|required",
            'process_ids' => "array|required"
        ]);
        $input = $request->input();
        $process_ids = $input['process_ids'];
        $result = $this->formMenuService->createAttach($input,$process_ids);
        ApiResponse::output($result);
    }

    /**
     * 更新表单菜单设计
     *
     */
    public function update(Request $request)
    {
        //TODO 创建和修改时都
        $this->validate($request, [
            'id' => "int|required",
            'name' => "string",
            'type' => "int|between:1,2",
            'workflow_info' => "string",
            'level' => "int",
            'parent_id' => "int",
            'process_ids' => "array|required"
        ]);
        $input = $request->input();
        $process_ids = $input['process_ids'];
        $result = $this->formMenuService->updateAttach($input,$input['id'],$process_ids);
        ApiResponse::output($result);
    }

    /**
     * 删除表单菜单设计
     *
     */
    public function delete(Request $request)
    {
        $this->validate($request, [
            'id' => "int|required"
        ]);
        $input = $request->input();
        $result = $this->formMenuService->deleteAttach($input['id']);
        ApiResponse::output($result);
    }

    /**
     * 按父菜单ID查询订单列表
     *
     * @return list
     */
    public function findByParentId(Request $request){
        $this->validate($request, [
            'parent_id' => 'required|int',
        ]);
        $result = $this->formMenuService->findByParentId($request->input("parent_id"));
        ApiResponse::output($result);
    }

    /**
     * 显示菜单列表
     *
     * @return list
     */
    public function showlist(){
        $result = $this->formMenuService->showlist();
        ApiResponse::output($result);
    }
}