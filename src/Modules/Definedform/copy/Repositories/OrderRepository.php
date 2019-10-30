<?php


namespace App\Modules\Definedform\Repositories;

use App\Modules\Definedform\Helpers\Util;
use App\Modules\Definedform\Models\FormLog;
use App\Modules\Definedform\Models\Order;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class OrderRepository implements OrderRepositoryInterface
{

    public function all($columns = ['*'])
    {
        return Order::with('form_logs')->get();
    }

    public function paginate($perPage = 15, $columns = ['*'])
    {
        // TODO: Implement paginate() method.
    }

    public function create(array $input)
    {
        DB::beginTransaction();
        try{
            //创建订单
            $data = array(
                'order_no'=>Util::random_order_id(),
                'apply_time'=>Carbon::now(),
            );
            $order = Order::create($data);
            if($order){
                //创建表单记录
                if ($input['form_log']){
                    $input['form_log']['order_id'] = $order->id;
                    $input['form_log']['form_format_id'] = $input['form_format_id'];
                    $form_log_result = FormLog::create($input['form_log']);
                    if($form_log_result){
                        //更新
                        $orderUpdate = Order::FindOrFail($order->id);
                        $orderUpdate->form_log_id = $form_log_result->id;
                        $updateResult = $orderUpdate->save();
                        if ($updateResult){
                            DB::commit();
                            $result = array(
                                'error_no' => 200,
                                'msg' => '创建成功',
                                'order_id' => $order->id
                            );
                            return $result;
                        }
                        else{
                            DB::rollBack();
                            $result = array(
                                'error_no' => 1000010,
                                'msg' => '创建失败'
                            );
                            return $result;
                        }
                    }
                    else{
                        DB::rollBack();
                        $result = array(
                            'error_no' => 1000011,
                            'msg' => '创建失败'
                        );
                        return $result;
                    }
                }
            }
        }catch (\Exception $e){
            DB::rollBack();
            return $e;
        }
    }

    public function save(array $data)
    {
        // TODO: Implement save() method.
    }

    public function delete($id)
    {
        // TODO: Implement delete() method.
    }

    public function update(array $input)
    {
        if (!isset($input['id'])) {
            $result = array(
                'error_no' => 1000012,
                'msg' => '缺少参数：订单ID'
            );
            return $result;
        }
        DB::beginTransaction();
        try{
            //修改订单
            $order = Order::findOrFail($input['id']);

            $form_log = FormLog::where('order_id', $order->id)->first();

            if ($input['form_log']['form_info']){
                $form_log->form_info = json_encode($input['form_log']['form_info']);
            }
            if ($input['form_log']['menu_id']){
                $form_log->menu_id = $input['form_log']['menu_id'];
            }
            if($order->save() && $form_log->save()){
                DB::commit();
                return $order;
            }
        }catch (\Exception $e){
            DB::rollBack();
            return $e;
        }
    }

    public function findByFieldText($value, $columns = ['*'])
    {
        $field_no = '"201908021440003123547"';
        $field_text = '"这是输入的内容2"';
        return DB::table('orders')
            ->join('form_logs', 'orders.id', '=', 'form_logs.order_id')
            ->select('orders.*', 'form_logs.*')
            ->whereRaw("JSON_CONTAINS(JSON_EXTRACT(form_logs.form_info, '$.fields[*].field_no'), '{$field_no}') 
            AND JSON_CONTAINS(JSON_EXTRACT(form_logs.form_info, '$.fields[*].field_value.field_text'), '{$field_text}')")
            ->get();
    }

    public function findByMenuId($menu_id)
    {
        $r = DB::table('orders')
            ->join('form_logs', 'orders.id', '=', 'form_logs.order_id')
            ->join('form_formats', 'form_logs.form_format_id', '=', 'form_formats.id')
            ->join('form_lists', 'form_formats.id', '=', 'form_lists.form_format_id')
            ->leftJoin('form_system_fields', 'form_lists.system_field_id', '=', 'form_system_fields.id')
            ->select('orders.id as order_id','orders.created_at as order_created_at','orders.updated_at as order_updated_at','orders.id as order_id','orders.*', 'form_logs.*', 'form_lists.*', 'form_system_fields.*')
            ->where(array('form_lists.menu_id'=>$menu_id))->orderByRaw('orders.id , form_lists.item_order asc')->get()->toArray();
        return $this->constructList($r);
    }

    public function constructList($r)
    {
        $array = array();
        $id = 0;
        $i = -1;

        foreach($r as $k => $v){
            $row = array();
            if ($id != $v->order_id){
                $id = $v->order_id;
                $i ++;
                if ($v->type == 'form'){    //表单字段
                    if ($v->form_info){
                        $form_info = json_decode($v->form_info,true);
                        foreach($form_info['form_info'] as $l =>$t){
                            if ($t['field_no'] == $v->field_no){
                                $row[$t['field_no']] = $t['field_value'];
                            }
                        }
                    }
                }
                else{                       //系统字段
                    $row[$v->system_field_name] = $v->{$v->system_field_name};
                }
                array_push($array,$row);
            }
            else{
                if ($v->type == 'form'){    //表单字段
                    if ($v->form_info){
                        $form_info = json_decode($v->form_info,true);
                        foreach($form_info['form_info'] as $l =>$t){
                            if ($t['field_no'] == $v->field_no){
                                $array[$i][$t['field_no']] = $t['field_value'];
                            }
                        }
                    }
                }
                else{                       //系统字段
                    $array[$i][$v->system_field_name] = $v->{$v->system_field_name};
                }

            }
        }

        return $array;
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
        return Order::with('form_logs')->select($columns)->findOrFail($id);
    }

    public function findBy($field, $value, $columns = ['*'])
    {
        // TODO: Implement findBy() method.
    }

    public function findWhere($where, $columns = ['*'])
    {
        return Order::with('form_logs')->select($columns)->where($where)->get();
    }

    public function findFirstBy($field, $value, $columns = ['*'])
    {
        // TODO: Implement findFirstBy() method.
    }

    public function findFirstWhere($where, $columns = ['*'])
    {
        // TODO: Implement findFirstWhere() method.
    }

    public function findByLogIds($log_ids, $columns = ['*'])
    {
        return Order::with('form_logs')->select($columns)->whereIn('form_log_id', $log_ids)->get();
    }

    public function findByLogIdsSearch($log_ids,$search_data, $page = 1, $size = 20, $columns = ['*'])
    {
        if (count($search_data) > 0){
            $i = 0;
            foreach($search_data as $k => $v){
                if($i == 0){
                    $whereRaw = "JSON_CONTAINS( form_logs.form_info, '\\\"{$v}\\\"', '$.{$k}' )";
                }
                else{
                    $whereRaw = $whereRaw . " AND JSON_CONTAINS( form_logs.form_info, '\\\"{$v}\\\"', '$.{$k}' )";
                }
                $i ++;
            }
            return DB::table('form_orders')
                ->join('form_logs', 'form_orders.id', '=', 'form_logs.order_id')
                ->select('form_orders.*', 'form_logs.form_info')
                ->whereIn('form_log_id', $log_ids)
                ->whereRaw($whereRaw)
                ->paginate($size,['*'], 'page', $page);
        }
        else{
            return DB::table('form_orders')
                ->join('form_logs', 'form_orders.id', '=', 'form_logs.order_id')
                ->select('form_orders.*', 'form_logs.form_info')
                ->whereIn('form_log_id', $log_ids)
                ->paginate($size,['*'], 'page', $page);
        }

    }

    public function findByLogIdsInfo($log_ids, $search_fields, $page = 1, $size = 20,$columns = ['*'])
    {
        if (count($search_fields) > 0){
            $i = 0;
            foreach($search_fields as $k => $and){
                $j = 0;
                foreach ($and as $m => $n){
                    if($j == 0){
                        $orRaw = " JSON_CONTAINS( form_logs.form_info, '\\\"{$n['search_value']}\\\"', '$.{$n['field_no']}' ) ";
                        $orRaw = $orRaw . " AND form_logs.form_format_id = {$n['form_format_id']} ";
                    }
                    else{
                        $orRaw = $orRaw . " AND JSON_CONTAINS( form_logs.form_info, '\\\"{$n['search_value']}\\\"', '$.{$n['field_no']}' ) ";
                    }
                    $j ++;
                }
                $orRaw = " ( " . $orRaw ." ) ";
                if($i == 0){
                    $whereRaw = $orRaw;
                }
                else{
                    $whereRaw = $whereRaw . " OR " . $orRaw;
                }
                $i ++;
            }
            return DB::table('form_orders')
                ->join('form_logs', 'form_orders.id', '=', 'form_logs.order_id')
                ->select('form_orders.*', 'form_logs.form_info')
                ->whereIn('form_log_id', $log_ids)
                ->whereRaw($whereRaw)
                ->offset(($page-1) * $size)
                ->limit($size)
                ->get();
        }
        else{
            return DB::table('form_orders')
                ->join('form_logs', 'form_orders.id', '=', 'form_logs.order_id')
                ->select('form_orders.*', 'form_logs.form_info')
                ->whereIn('form_log_id', $log_ids)
                ->offset(($page-1) * $size)
                ->limit($size)
                ->get();
        }

        /*
         array:2 [
          0 => array:2 [
            0 => array:4 [
              "form_list_id" => 2
              "form_format_id" => 2
              "field_no" => "input_201908021440003123549"
              "search_value" => "是"
            ]
            1 => array:4 [
              "form_list_id" => 3
              "form_format_id" => 2
              "field_no" => "input_201908021440003123546"
              "search_value" => "郑水根"
            ]
          ]
          1 => array:2 [
            0 => array:4 [
              "form_list_id" => 2
              "form_format_id" => 3
              "field_no" => "input_201908021440003123548"
              "search_value" => "是"
            ]
            1 => array:4 [
              "form_list_id" => 3
              "form_format_id" => 3
              "field_no" => "input_201908021440003123545"
              "search_value" => "郑水根"
            ]
          ]
        ]*/
    }
}