<?php


namespace App\Modules\Definedform\Repositories;

use App\Modules\Definedform\Models\FormList;
use App\Modules\Definedform\Models\FormFormatFormList;
use App\Modules\Definedform\Models\FormSystemField;

class FormListRepository implements FormListRepositoryInterface
{

    public function all($columns = ['*'])
    {
        return FormList::get();
    }

    public function paginate($perPage = 15, $columns = ['*'])
    {
        // TODO: Implement paginate() method.
    }

    public function create(array $data)
    {
        return FormList::create($data);
    }

    public function save(array $data)
    {
        // TODO: Implement save() method.
    }

    public function delete($id)
    {
        return FormList::where('id',$id)->delete();
    }

    public function deleteWhere($data)
    {
        return FormList::where($data)->delete();
    }

    public function update(array $data, $id)
    {
        return FormList::where('id',$id)->update($data);
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
        return FormList::select($columns)->where('id',$id)->first();
    }

    public function findBy($field, $value, $columns = ['*'],$order = 'id')
    {
        return FormList::select($columns)->where($field,$value)->orderBy($order)->get();
    }

    public function findWhere($where, $columns = ['*'])
    {
        return FormList::select($columns)->where($where)->get();
    }

    public function findFirstBy($field, $value, $columns = ['*'])
    {
        return FormList::select($columns)->where($field,$value)->first();
    }

    public function findFirstWhere($where, $columns = ['*'])
    {
        return FormList::select($columns)->where($where)->first();
    }

    public function findFirstByMenuId($menu_id, $columns = ['*'])
    {
        return FormList::select($columns)->where('menu_id',$menu_id)->orderBy('item_order','desc')->first();
    }

    public function insert(array $data)
    {
        return FormList::insert($data);
    }

    public function createAttach(array $data,array $form_format_ids)
    {
        $form_list = FormList::create($data);
        $r = $form_list->form_format()->attach($form_format_ids);
        return $form_list;
    }

    public function updateAttach(array $data, $id,array $form_format_ids)
    {
        $form_list = FormList::findOrFail($id);
        $r_update = $form_list->update($data);
        $r = $form_list->form_format()->sync($form_format_ids);
        return $form_list;
    }

    public function deleteAttach($id)
    {
        $form_list = FormList::findOrFail($id);
        $r = $form_list->form_format()->detach();
        $form_list->delete();
        return $r;
    }

    public function findAttach($id, $columns = ['*'])
    {
        $form_list = FormList::select($columns)->where('id',$id)->first();
        $form_format = $form_list->form_format;
        return $form_list;
    }

    public function formSystemFieldList($columns = ['*'])
    {
        return FormSystemField::get();
    }

    public function findAttachWhere($where, $columns = ['*'])
    {
        //$where['type'] = 'form';
        $form_list = FormList::select($columns)->with('form_format')->where($where)->get();
        return $form_list;
    }

    public function findLogIdByMenuId($menu_id)
    {
        $form_list = FormList::select(['id'])->where(array('menu_id'=>$menu_id))->get();
        $list_ids = $form_list->isNotEmpty() ? array_column($form_list->toArray(),'id') : array();
        $form_logs_list = FormFormatFormList::join('form_logs', 'form_format_form_list.form_format_id', '=', 'form_logs.form_format_id')
            ->select('form_logs.id as form_log_id')
            ->whereIn('form_format_form_list.form_list_id', $list_ids)
            ->groupBy('form_logs.id')
            ->get();
        $form_logs_ids = $form_logs_list->isNotEmpty() ? array_column($form_logs_list->toArray(),'form_log_id') : array();
        return $form_logs_ids;
    }


    public function findFieldNoByMenuId($menu_id)
    {
        $form_list = FormList::select([
            'form_lists.*',
            'form_format_form_list.form_list_id',
            'form_format_form_list.form_format_id',
            'form_format_form_list.field_no',
            'form_format_form_list.field_label',
            'form_format_form_list.form_name_cn',
            'form_system_fields.system_field_name'
        ])
            ->leftJoin('form_format_form_list', 'form_format_form_list.form_list_id', '=', 'form_lists.id')
            ->leftJoin('form_system_fields', 'form_system_fields.id', '=', 'form_lists.system_field_id')
            ->where(array('form_lists.menu_id'=>$menu_id))->orderBy('form_lists.item_order', 'asc')->get();
        $array = array();
        foreach($form_list->toArray() as $k => $v){
            $m = $v['item_order'];
            if ($v['type'] == 'form'){
                $array[$m]['field_no'][] = $v['field_no'];
            }
            else{
                $array[$m]['system_field_name'][] = $v['system_field_name'];
            }
        }
        return $array;
    }

    public function findSearchFormFieldByMenuId($menu_id)
    {
        $form_field_list = FormList::select([
            'form_lists.*',
            'form_format_form_list.form_list_id',
            'form_format_form_list.form_format_id',
            'form_format_form_list.field_no',
            'form_format_form_list.field_label',
            'form_format_form_list.form_name_cn'
        ])
            ->leftJoin('form_format_form_list', 'form_format_form_list.form_list_id', '=', 'form_lists.id')
            ->where(array('form_lists.menu_id'=>$menu_id))
            ->where(array('form_lists.searchable'=>1))
            ->where(array('form_lists.system_field_id'=>2))
            ->orderBy('form_lists.item_order', 'asc')
            ->groupBy('form_format_form_list.field_no')
            ->get();

        $array_form = array_column($form_field_list->toArray(),'field_no');
        return $array_form;
    }

    public function findSearchSystemFieldByMenuId($menu_id)
    {
        $system_field_list = FormList::select([
            'form_lists.*',
            'form_format_form_list.form_list_id',
            'form_format_form_list.form_format_id',
            'form_format_form_list.field_no',
            'form_format_form_list.field_label',
            'form_format_form_list.form_name_cn',
            'form_system_fields.system_field_name'
        ])
            ->leftJoin('form_format_form_list', 'form_format_form_list.form_list_id', '=', 'form_lists.id')
            ->leftJoin('form_system_fields', 'form_system_fields.id', '=', 'form_lists.system_field_id')
            ->where(array('form_lists.menu_id'=>$menu_id))
            ->where(array('form_lists.searchable'=>1))
            ->where(array('form_lists.system_field_id'=>1))
            ->orderBy('form_lists.item_order', 'asc')
            ->groupBy('form_system_fields.system_field_name')
            ->get();


        $array_system = array_column($system_field_list->toArray(),'system_field_name');
        return $array_system;
    }

    public function findFormatIdsByListIds($search_ids)
    {
        $form_format_id_array = FormFormatFormList::select('form_format_id')
            ->whereIn('form_list_id', $search_ids)
            ->groupBy('form_format_id')
            ->get()->toArray();


        $result = array_column($form_format_id_array,'form_format_id');
        return $result;
    }

    public function findSearchFormField($format_ids,$search_data)
    {
        ksort($search_data);
        $search_ids = array_keys($search_data);
        $search_data_value = array_values($search_data);

        $result_array = array();
        //dump($format_ids);
        foreach($format_ids as $k => $v){
            $field_nos = FormFormatFormList::select('form_list_id','form_format_id','field_no')
                ->where('form_format_id', $v)
                ->whereIn('form_list_id',$search_ids)
                ->get();
            $field_no_array = $field_nos->toArray();
            if ($field_nos->count() == count($search_data)){
                foreach ($field_no_array as $m => $n){
                    $field_no_array[$m]['search_value'] = $search_data_value[$m] ? $search_data_value[$m] : '';
                }
                $result_array[] = $field_no_array;
            }
            else{
                unset($format_ids[$k]);
            }

            //dump($field_no_array);

        }

        //dump($result_array);
        return $result_array;
    }

    private function format_map($data)
    {
        $array = array(
            $data['form_format_id']
        );

        return($num*$num);
    }
}