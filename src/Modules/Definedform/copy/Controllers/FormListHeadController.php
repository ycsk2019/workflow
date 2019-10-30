<?php


namespace App\Modules\Definedform\Controllers;

use App\Modules\Definedform\Services\FormListHeadServiceInterface;
use Illuminate\Http\Request;
use App\Modules\Definedform\Helpers\ApiResponse;

class FormListHeadController extends Controller
{
    protected $formListHeadService;

    public function __construct(FormListHeadServiceInterface $formListHeadService)
    {
        $this->formListHeadService = $formListHeadService;
    }

    /**
     * 查询表单列表头设计列表
     *
     * @return list
     */
    public function index(){
        $result = $this->formListHeadService->all();
        ApiResponse::output($result);
    }

    /**
     * 查询表单列表头设计详情
     *
     * @return
     */
    public function detail(Request $request){
        $result = $this->formListHeadService->find($request->input('id'));
        ApiResponse::output($result);
    }

    /**
     * 创建表单列表头设计
     *
     */
    public function create(Request $request)
    {
        $input = $request->input();
        $result = $this->formListHeadService->create($input);
        ApiResponse::output($result);
    }

    /**
     * 更新表单列表头设计
     *
     */
    public function update(Request $request)
    {
        /*$this->validate($request, [
            'form_no' => "string|required"
        ]);*/
        $input = $request->input();
        $result = $this->formListHeadService->update($input);
        ApiResponse::output($result);
    }

    public function findByMenuId(Request $request)
    {
        /*$this->validate($request, [
            'menu_id' => "int|required"
        ]);*/
        return $this->formListHeadService->findByMenuId($request->input('menu_id'));
    }
}