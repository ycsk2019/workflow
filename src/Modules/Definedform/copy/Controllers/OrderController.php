<?php


namespace App\Modules\Definedform\Controllers;


use App\Modules\Definedform\Helpers\ApiResponse;
use App\Modules\Definedform\Services\OrderServiceInterface;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    protected $orderService;

    public function __construct(OrderServiceInterface $orderService)
    {
        $this->orderService = $orderService;
    }

    /**
     * 查询订单列表
     *
     * @return list
     */
    public function index(){
        $result = $this->orderService->all();
        ApiResponse::output($result);
    }

    /**
     * 根据菜单ID和搜索项查询订单列表
     *
     * @return list
     */
    public function lists(Request $request){
        $this->validate($request, [
            'menu_id' => 'required|int',
            'search_data' => 'array',
            'page' => 'int',
            'size' => 'int',
        ]);
        $input = $request->input();
        $search_data = isset($input['search_data']) ? $input['search_data'] : array();
        $page = isset($input['page']) ? $input['page'] : 1;
        $size = isset($input['size']) ? $input['size'] : 20;
        $result = $this->orderService->lists($input, $search_data,$page, $size);
        ApiResponse::output($result);
    }

    /**
     * 查询订单详情
     *
     * @return list
     */
    public function detail(Request $request){
        $result = $this->orderService->find($request->input('id'));
        ApiResponse::output($result);
    }

    /**
     * 创建订单
     *
     */
    public function create(Request $request){
        $result = $this->orderService->create($request->input());
        ApiResponse::output($result);
    }

    /**
     * 修改订单
     *
     */
    public function update(Request $request){
        $result = $this->orderService->update($request->input());
        ApiResponse::output($result);
    }

    /**
     * 搜索订单
     *
     */
    public function findByFieldText(Request $request){
        $result = $this->orderService->findByFieldText($request->input("search"));
        ApiResponse::output($result);
    }

    /**
     * 按菜单ID查询订单列表
     *
     * @return list
     */
    public function findByMenuId(Request $request){
        $this->validate($request, [
            'menu_id' => 'required|int',
        ]);
        $result = $this->orderService->findByMenuId($request->input("menu_id"));
        ApiResponse::output($result);
    }
}