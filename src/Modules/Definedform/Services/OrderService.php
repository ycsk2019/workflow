<?php


namespace Ycsk\Definedform\Modules\Definedform\Services;

use Ycsk\Definedform\Modules\Definedform\Repositories\FormFormatRepositoryInterface;
use Ycsk\Definedform\Modules\Definedform\Repositories\FormListRepositoryInterface;
use Ycsk\Definedform\Modules\Definedform\Repositories\OrderRepositoryInterface;

class OrderService implements OrderServiceInterface
{
    protected $orderRepository;

    public function __construct(OrderRepositoryInterface $orderRepository,FormListRepositoryInterface $formListRepository,FormFormatRepositoryInterface $formFormatRepository)
    {
        $this->orderRepository = $orderRepository;
	    $this->formListRepository = $formListRepository;
	    $this->formFormatRepository = $formFormatRepository;
    }

    public function all(){
        return $this->orderRepository->all();
    }

    public function find($id, $columns = ['*'])
    {
        $detail = $this->orderRepository->find($id, $columns);
        $formFormat = $this->formFormatRepository->find($detail->form_logs->form_format_id);
        $detail->form_logs->form_format = $formFormat;
        return $detail;
    }

    public function create($data){
        return $this->orderRepository->create($data);
    }

    public function update($data){
        return $this->orderRepository->update($data);
    }

    public function findByFieldText($value, $columns = ['*'])
    {
        return $this->orderRepository->findByFieldText($value,$columns);
    }

    public function findByMenuId($menu_id)
    {
        return $this->orderRepository->findByMenuId($menu_id);
    }

    public function lists($where, $search_data,$page = 1, $size = 20){
        $log_ids = $this->formListRepository->findLogIdByMenuId($where['menu_id']);
        $array_form = $this->formListRepository->findSearchFormFieldByMenuId($where['menu_id']);
        $array_system = $this->formListRepository->findSearchSystemFieldByMenuId($where['menu_id']);
        $search_ids = array_merge($array_form,$array_system);
        /*$search_data = array(
            'input_201908021440003123549'=>'是',
            'input_201908021440003123546'=>'郑水根'
        );*/
        foreach($search_data as $k => $v){
            if(!in_array($k,$search_ids)){
                $result = array(
                    'error_no' => 1000001,
                    'msg' => '字段: '.$k.' 为不可搜索字段'
                );
                return $result;
            }
        }

        $order_list = $this->orderRepository->findByLogIdsSearch($log_ids,$search_data,$page, $size);
        $fields_array = $this->formListRepository->findFieldNoByMenuId($where['menu_id']);

        $data = $order_list->getCollection();//从paginate抽取
        $order_array = json_decode($data->toJson(),true);

        foreach ($order_array as $k => $v){
            $order_array[$k]['fields_array'] = $fields_array;
        }

        $order_collect = collect($order_array);

        $multiplied = $order_collect->map(function ($item, $key) {
            return $this->itemCollect($item, $key);
        });

        $multiplied->all();



        $order_list->setCollection($multiplied);//设置到paginate中
        return $order_list;
    }

    private function itemCollect($item,$key){
        $form_info_array = json_decode($item['form_info'],true);
        $fields_array = $item['fields_array'];
        $result = array();
        foreach ($fields_array as $k => $v) {
            if (isset($v['system_field_name'])){
                $system_key = $v['system_field_name'][0];
                $result[$system_key] = $item[$system_key];
            }
            else{
                foreach ($v['field_no'] as $m => $field_no){
                    foreach ($form_info_array as $n => $form_info){
                        if ($n == $field_no){
                            $form_key = $n;
                            $result[$form_key] = $form_info;
                        }
                    }
                }
                //$v['field_no'] array
                //$field_no_array=array_flip($v['field_no']);    //反转数组中所有的键以及它们关联的值
                //$temp_array = array_intersect_key($field_no_array,$form_info_array);
                /*$form_info_array_flip = array_flip($form_info_array);
                return $form_info_array_flip;
                $temp_array = array_intersect($v['field_no'],$form_info_array_flip);
                $form_info_key = isset($temp_array[0]) ? $temp_array[0] : $v['field_no'][0];
                $result[$form_info_key] = $form_info_array[$form_info_key];*/
            }
        }
        return $result;
    }
}