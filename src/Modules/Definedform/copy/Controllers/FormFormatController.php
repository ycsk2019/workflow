<?php

namespace App\Modules\Definedform\Controllers;

use App\Modules\Definedform\Services\FormFormatServiceInterface;
use Illuminate\Http\Request;
use App\Modules\Definedform\Helpers\ApiResponse;

class FormFormatController extends Controller
{
    protected $formFormatService;

    public function __construct(FormFormatServiceInterface $formFormatService)
    {
        $this->formFormatService = $formFormatService;
    }

    /**
     * 查询表单设计列表
     *
     * @return list
     */
    public function index(Request $request){
        $this->validate($request, [
            'form_no' => "string",
            'form_name_cn' => "string",
            'company_id' => "int|required"
        ]);
        $input = $request->input();
        //不传field_info，字段数据量太大会卡顿
        $columns = ['id','form_name_cn','form_no','process_id','company_id','is_new','version','desc','deleted_at','created_at','updated_at'];
        $result = $this->formFormatService->findWhere($input,$columns);
        ApiResponse::output($result);
    }

    /**
     * 查询表单设计详情
     *
     * @return
     */
    public function detail(Request $request){
        $result = $this->formFormatService->find($request->input('id'));
        ApiResponse::output($result);
    }

    /**
     * 创建表单设计
     *
     */
    public function create(Request $request)
    {
        $input = $request->input();
        $result = $this->formFormatService->create($input);
        ApiResponse::output($result);
    }

    /**
     * 更新表单设计,其实是创建一个新表单设计记录
     *
     */
    public function update(Request $request)
    {
        $this->validate($request, [
            'form_no' => "string|required"
        ]);
        $input = $request->input();
        $result = $this->formFormatService->updateByFormNo($input,$input['form_no']);
        ApiResponse::output($result);
    }

    /**
     * 删除表单设计
     *
     */
    public function delete(Request $request)
    {
        $input = $request->input();
        $result = $this->formFormatService->delete($input['id']);
        ApiResponse::output($result);
    }

    /**
     * 查询最新表单设计详情
     *
     * @return list
     */
    public function getLastest(Request $request){
        $this->validate($request, [
            'form_no' => 'required|string',
        ]);
        $input = $request->input();
        $result = $this->formFormatService->getLastestByFormNo($input['form_no']);
        ApiResponse::output($result);
    }

    /**
     * 查询最新表单设计详情列表
     *
     * @return list
     */
    public function getLastestList(Request $request){
        $this->validate($request, [
            'form_no' => 'string',
            'form_name_cn' => 'string',
        ]);
        $input = $request->input();
        $result = $this->formFormatService->getLastestList($input);
        ApiResponse::output($result);
    }

    /**
     * 根据表单编号和菜单ID查询表单模板详情
     *
     * @return list
     */
    public function findByFormNoVersion(Request $request)
    {
        $this->validate($request, [
            'form_no' => 'required|string',
            'version' => 'required|int',
        ]);
        $input = $request->input();
        $result = $this->formFormatService->findByFormNoVersion($input);
        ApiResponse::output($result);
    }

    /**
     * 根据公司ID和菜单ID查询表单模板列表
     *
     * @return list
     */
    public function findByCompanyIdMenuId(Request $request)
    {
        $this->validate($request, [
            'company_id' => 'required|int',
            'menu_id' => 'required|int',
        ]);
        $input = $request->input();
        $columns = ['id','form_name_cn','form_no','field_info','is_new','version','desc','process_id','company_id','updated_at','created_at'];
        $result = $this->formFormatService->findByCompanyIdMenuId($input['company_id'],$input['menu_id'],$columns);
        ApiResponse::output($result);
    }

}
