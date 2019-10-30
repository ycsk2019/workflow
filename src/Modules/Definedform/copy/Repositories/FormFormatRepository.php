<?php


namespace App\Modules\Definedform\Repositories;

use App\Modules\Definedform\Models\FormFormat;


class FormFormatRepository implements FormFormatRepositoryInterface
{
    /**
     * @var FormFormat 注入的 FormFormat Model
     */
    protected $formFormat;

    public function __construct(FormFormat $formFormat)
    {
        $this->formFormat = $formFormat;
    }

    public function all($columns = ['*'])
    {
        return FormFormat::get();
    }

    public function paginate($perPage = 15, $columns = ['*'])
    {
        // TODO: Implement paginate() method.
    }

    public function create(array $data)
    {
        return FormFormat::create($data);
    }

    public function save(array $data)
    {
        // TODO: Implement save() method.
    }

    public function delete($id)
    {
        return FormFormat::where('id',$id)->delete();
    }

    public function update(array $data, $id)
    {
        return FormFormat::where('id',$id)->update($data);
    }

    public function updateBy(array $data, $field, $value, $columns = ['*'])
    {
        return FormFormat::select($columns)->where($field,$value)->update($data);
    }

    public function updateWhere(array $data, $where, $columns = ['*'])
    {
        return FormFormat::select($columns)->where($where)->update($data);
    }

    public function find($id, $columns = ['*'])
    {
        return FormFormat::select($columns)->where('id',$id)->first();
    }

    public function findBy($field, $value, $columns = ['*'])
    {
        return FormFormat::select($columns)->where($field,$value)->get();
    }

    public function findWhere($data, $columns = ['*'],$orderBy = '',$groupBy = '')
    {
        if ($orderBy){
            if ($groupBy){
                $formFormat = FormFormat::select($columns)->where($data)->orderBy($orderBy)->groupBy($groupBy)->get();
            }
            else{
                $formFormat = FormFormat::select($columns)->where($data)->orderBy($orderBy)->get();
            }
        }
        else{
            if ($groupBy){
                $formFormat = FormFormat::select($columns)->where($data)->groupBy($groupBy)->get();
            }
            else{
                $where = array();
                if (isset($data['company_id']) && !empty($data['company_id'])){
                    $where[] = ['company_id', '=', $data['company_id']];
                }
                if (isset($data['form_no']) && !empty($data['form_no'])){
                    $where[] = ['form_no', '=', $data['form_no']];
                }
                if (isset($data['form_name_cn']) && !empty($data['form_name_cn'])){
                    $where[] = ['form_name_cn', 'like', '%'.$data['form_name_cn'].'%'];
                }
                $formFormat = FormFormat::select($columns)->where($where)->get();
            }
        }
        return $formFormat;
    }

    public function findFirstBy($field, $value, $columns = ['*'])
    {
        return FormFormat::select($columns)->where($field,$value)->first();
    }

    public function findFirstWhere($where, $columns = ['*'])
    {
        return FormFormat::select($columns)->where($where)->first();
    }

    public function findProcessIdsByMenuId(array $process_ids,$company_id, $columns = ['*'])
    {
        return FormFormat::select($columns)->whereIn('process_id', $process_ids)->where('company_id',$company_id)->get();
    }
}