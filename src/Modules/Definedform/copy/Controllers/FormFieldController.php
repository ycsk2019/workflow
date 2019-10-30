<?php


namespace App\Modules\Definedform\Controllers;

use App\Modules\Definedform\Services\FormFieldServiceInterface;
use Illuminate\Http\Request;
use App\Modules\Definedform\Helpers\ApiResponse;


class FormFieldController extends Controller
{
    protected $formFieldService;

    public function __construct(FormFieldServiceInterface $formFieldService)
    {
        $this->formFieldService = $formFieldService;
    }

    /**
     * 查询表单设计列表
     *
     * @return list
     */
    public function index(){
        $result = $this->formFieldService->all();
        ApiResponse::output($result);
    }

    /**
     * 查询表单设计详情
     *
     * @return
     */
    public function detail(Request $request){
        $result = $this->formFieldService->find($request->input('id'));
        ApiResponse::output($result);
    }
}