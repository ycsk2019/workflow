<?php


namespace App\Modules\Definedform\Controllers;

use App\Modules\Definedform\Services\FormFormatServiceInterface;
use App\Modules\Definedform\Services\FormListServiceInterface;
use Illuminate\Http\Request;
use App\Modules\Definedform\Helpers\ApiResponse;

class FormListController extends Controller
{
    protected $formListService;
    protected $formFormatService;

    public function __construct(FormListServiceInterface $formListService,FormFormatServiceInterface $formFormatService)
    {
        $this->formListService = $formListService;
        $this->formFormatService = $formFormatService;
    }

    /**
     * 查询表单列表设计列表
     *
     * @return list
     */
    public function index(){
        $result = $this->formListService->all();
        ApiResponse::output($result);
    }

    /**
     * 查询表单列表设计详情
     *
     * @return
     */
    public function detail(Request $request){
        $result = $this->formListService->findAttach($request->input('id'));
        ApiResponse::output($result);
    }

    /**
     * 创建表单列表设计
     *
     */
    public function create(Request $request)
    {
        //TODO 创建和修改时都需要修改菜单和表单模板关联关系
        $this->validate($request, [
            'menu_id' => "int|required",
            'type' => "string|required",
            'system_field_id' => "int",
            'searchable' => "int|required|between:1,2",
            'form_format_ids' => "array"
        ]);
        $input = $request->input();
        $form_format_ids = $input['form_format_ids'];
        $result = $input['type'] == 'form' ? $this->formListService->createAttach($input,$form_format_ids) : $this->formListService->create($input);
        ApiResponse::output($result);
    }

    /**
     * 更新表单列表设计
     *
     */
    public function update(Request $request)
    {
        $this->validate($request, [
            'id' => "int|required",
            'menu_id' => "int|required",
            'type' => "string|required",
            'system_field_id' => "int",
            'searchable' => "int|required|between:1,2",
            'form_format_ids' => "array"
        ]);
        $input = $request->input();
        $result = $input['type'] == 'form' ? $this->formListService->updateAttach($input,$input['id'],$input['form_format_ids']) : $this->formListService->update($input,$input['id']) ;
        ApiResponse::output($result);
    }

    /**
     * 按菜单ID查询表单列表设计
     * @param Request $request
     * @return
     */
    public function findByMenuId(Request $request)
    {
        $this->validate($request, [
            'menu_id' => "int|required"
        ]);
        $list = $this->formListService->findAttachByMenuId($request->input('menu_id'));
        //$list = $this->formListService->findLogIdByMenuId($request->input('menu_id'));
        ApiResponse::output($list);
    }

    /**
     * 批量创建表单列表设计
     *
     */
    public function createMulti(Request $request)
    {
        $input = $request->input();
        $result = $this->formListService->createMulti($input);
        ApiResponse::output($result);
    }

    /**
     * 批量修改表单列表设计
     *
     */
    public function updateMulti(Request $request)
    {
        $input = $request->input();
        $result = $this->formListService->updateMulti($input);
        ApiResponse::output($result);
    }

    /**
     * 批量重排序表单列表设计
     *
     */
    public function resort(Request $request)
    {
        $this->validate($request, [
            'order_array' => "array|required"
        ]);
        $input = $request->input('order_array');
        $result = $this->formListService->updateMulti($input);
        ApiResponse::output($result);
    }

    /**
     * 删除表单列表设计
     *
     */
    public function delete(Request $request)
    {
        $this->validate($request, [
            'id' => "int|required"
        ]);
        $input = $request->input();
        $result = $this->formListService->deleteAttach($input['id']);
        ApiResponse::output($result);
    }

    /**
     * 查询系统字段列表
     *
     * @return list
     */
    public function formSystemFieldList(){
        $result = $this->formListService->formSystemFieldList();
        ApiResponse::output($result);
    }

    /**
     * 查找搜索字段
     *
     * @return list
     */
    public function findSearchFieldByMenuId(Request $request){
        $result = $this->formListService->findSearchFieldByMenuId($request->input("menu_id"));
        ApiResponse::output($result);
    }
}