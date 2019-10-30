<?php


namespace App\Modules\Definedform\Services;

use App\Modules\Definedform\Repositories\FormListRepositoryInterface;

class FormListService implements FormListServiceInterface
{
    protected $formListRepository;

    public function __construct(FormListRepositoryInterface $formListRepository)
    {
        $this->formListRepository = $formListRepository;
    }

    public function all(){
        return $this->formListRepository->all();
    }

    public function find($id, $columns = ['*'])
    {
        return $this->formListRepository->find($id, $columns);
    }

    public function create(array $data)
    {
        unset($data['form_format_ids']);
        $form_list = $this->formListRepository->findFirstByMenuId($data['menu_id'],array('item_order'));
        if ($form_list){
            $data['item_order'] = $form_list->item_order + 1;
        }
        else{
            $data['item_order'] = 1;
        }
        return $this->formListRepository->create($data);
    }

    public function update(array $data, $id)
    {
        unset($data['form_format_ids']);
        return $this->formListRepository->update($data, $id);
    }

    public function findBy($field, $value, $columns = ['*'])
    {
        return $this->formListRepository->findBy($field, $value, $columns);
    }

    public function findWhere($where, $columns = ['*'])
    {
        return $this->formListRepository->findWhere($where, $columns);
    }

    public function delete($id)
    {
        return $this->formListRepository->delete($id);
    }

    public function findByMenuId($menu_id, $columns = ['*'])
    {
        return $this->formListRepository->findBy('menu_id', $menu_id, $columns,'item_order');

    }

    public function createMulti(array $data)
    {
        return $this->formListRepository->insert($data);
    }

    public function updateMulti(array $data)
    {
        try{
            $i = 0;
            foreach($data as $k => $v){
                $this->formListRepository->update($v,$v['id']);
                $i++;
            }
            return $i;
        }catch(\Exception $e){
            return $e;
        }
    }

    public function createAttach(array $data,array $form_format_ids)
    {
        $form_list = $this->formListRepository->findFirstByMenuId($data['menu_id'],array('item_order'));
        if ($form_list){
            $data['item_order'] = $form_list->item_order + 1;
        }
        else{
            $data['item_order'] = 1;
        }
        return $this->formListRepository->createAttach($data,$form_format_ids);
    }

    public function updateAttach(array $data, $id,array $form_format_ids)
    {
        return $this->formListRepository->updateAttach($data, $id,$form_format_ids);
    }

    public function deleteAttach($id)
    {
        return $this->formListRepository->deleteAttach($id);
    }

    public function findAttach($id, $columns = ['*'])
    {
        return $this->formListRepository->findAttach($id, $columns);
    }

    public function findAttachByMenuId($menu_id, $columns = ['*'])
    {
        $where = array(
            'menu_id' => $menu_id
        );
        return $this->formListRepository->findAttachWhere($where);
    }

    public function formSystemFieldList(){
        return $this->formListRepository->formSystemFieldList();
    }

    public function findLogIdByMenuId($menu_id){
        return $this->formListRepository->findLogIdByMenuId($menu_id);
    }

    public function findSearchFormFieldByMenuId($menu_id){
        return $this->formListRepository->findSearchFormFieldByMenuId($menu_id);
    }

    public function findSearchSystemFieldByMenuId($menu_id){
        return $this->formListRepository->findSearchSystemFieldByMenuId($menu_id);
    }

    /**
     * 查找搜索字段
     *
     * @return list
     */
    public function findSearchFieldByMenuId($menu_id){
        $array_form = $this->findSearchFormFieldByMenuId($menu_id);
        $array_system = $this->findSearchSystemFieldByMenuId($menu_id);
        $result = array_merge($array_form,$array_system);
        return $result;
    }
}