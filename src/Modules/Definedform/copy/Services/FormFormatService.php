<?php


namespace App\Modules\Definedform\Services;


use App\Modules\Definedform\Repositories\FormFormatRepositoryInterface;
use App\Modules\Definedform\Repositories\FormMenuRepositoryInterface;
use Illuminate\Support\Facades\DB;
use App\Modules\Definedform\Helpers\Util;

class FormFormatService implements FormFormatServiceInterface
{
    protected $formFormatRepository;
    protected $flag_is_new = 1;
    protected $flag_is_not_new = 2;

    public function __construct(FormFormatRepositoryInterface $formFormatRepository,FormMenuRepositoryInterface $formMenuRepository)
    {
        $this->formFormatRepository = $formFormatRepository;
        $this->formMenuRepository = $formMenuRepository;
    }

    public function all($data){
        return $this->formFormatRepository->all($data);
    }

    public function find($id, $columns = ['*'])
    {
        return $this->formFormatRepository->find($id, $columns);
    }

    public function create(array $data)
    {
        $data['form_no'] = Util::random_order_id();
        $data['is_new'] = $this->flag_is_new;
        return $this->formFormatRepository->create($data);
    }

    public function update(array $data, $id)
    {
        return $this->formFormatRepository->update($data, $id);
    }

    public function updateByFormNo(array $data,$form_no)
    {
        DB::beginTransaction();
        try{
            $data['is_new'] = $this->flag_is_new;
            $lastest_form_format = $this->getLastestByFormNo($form_no);
            $data['version'] = $lastest_form_format->version + 1;
            $data['process_id'] = $lastest_form_format->process_id;
            $data['company_id'] = $lastest_form_format->company_id;
            $update_param = array('is_new'=>$this->flag_is_not_new);
            //将原有该node_id的表单设计记录设置成非new
            if($form_no && $this->formFormatRepository->updateBy($update_param,'form_no', $form_no)){
                $result = $this->formFormatRepository->create($data);
                DB::commit();
                $result = array(
                    'error_no' => 200,
                    'msg' => '更新成功'
                );
                return $result;
            }
            else{
                DB::rollBack();
                $result = array(
                    'error_no' => 1000010,
                    'msg' => '更新失败'
                );
                return $result;
            }
        }catch (\Exception $e){
            DB::rollBack();
            $result = array(
                'error_no' => 1000011,
                'msg' => '更新失败'
            );
            return $result;
        }
    }

    public function updateWhere(array $data, $where, $columns = ['*'])
    {
        return $this->formFormatRepository->updateWhere($data, $where, $columns);
    }

    public function findBy($field, $value, $columns = ['*'])
    {
        return $this->formFormatRepository->findBy($field, $value, $columns);
    }

    public function findFirstBy($field, $value, $columns = ['*'])
    {
        return $this->formFormatRepository->findFirstBy($field, $value, $columns);
    }

    public function findWhere($where, $columns = ['*'])
    {
        return $this->formFormatRepository->findWhere($where, $columns);
    }

    public function delete($id)
    {
        return $this->formFormatRepository->delete($id);
    }

    /**
     * 查询最新表单设计详情
     *
     * @return list
     */
    public function getLastestByFormNo($form_no){
        $where = array(
            "is_new" => $this->flag_is_new,
            "form_no" => $form_no
        );
        return $this->formFormatRepository->findFirstWhere($where);
    }

    /**
     * 查询最新表单设计详情列表
     *
     * @return list
     */
    public function getLastestList($input){
        $where = array(
            "is_new" => $this->flag_is_new
        );
        if (isset($input['form_no'])){
            $where['form_no'] = $input['form_no'];
        }
        if (isset($input['form_name_cn'])){
            $where['form_name_cn'] = $input['form_name_cn'];
        }
        $orderBy = 'form_no';
        $groupBy = 'form_no';
        $result = $this->formFormatRepository->findWhere($where,['*'],$orderBy,$groupBy);
        foreach ($result as $k=>$v){
            $version_array = $this->findBy('form_no', $v->form_no, $columns = ['version']);
            $version = array_column($version_array->toArray(),'version');
            $result[$k]->version_array = $version;
        }
        return $result;
    }

    public function findByFormNoVersion($input)
    {
        $where = array(
            "form_no" => $input['form_no'],
            "version" => $input['version']
        );
        return $this->formFormatRepository->findFirstWhere($where);
    }

    public function findByCompanyIdMenuId($company_id,$menu_id, $columns = ['*'])
    {
        $process_ids = $this->formMenuRepository->findProcessIdsByMenuId($menu_id);
        return $this->formFormatRepository->findProcessIdsByMenuId($process_ids,$company_id,$columns);
    }
}