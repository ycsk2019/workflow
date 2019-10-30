<?php


namespace App\Modules\Definedform\Services;

use App\Modules\Definedform\Repositories\FormListHeadRepositoryInterface;

class FormListHeadService implements FormListHeadServiceInterface
{
    protected $formListHeadRepository;

    public function __construct(FormListHeadRepositoryInterface $formListHeadRepository)
    {
        $this->formListHeadRepository = $formListHeadRepository;
    }

    public function all(){
        return $this->formListHeadRepository->all();
    }

    public function find($id, $columns = ['*'])
    {
        return $this->formListHeadRepository->find($id, $columns);
    }

    public function create(array $data)
    {
        return $this->formListHeadRepository->create($data);
    }

    public function update(array $data, $id)
    {
        return $this->formListHeadRepository->update($data, $id);
    }

    public function findBy($field, $value, $columns = ['*'])
    {
        return $this->formListHeadRepository->findBy($field, $value, $columns);
    }

    public function findWhere($where, $columns = ['*'])
    {
        return $this->formListHeadRepository->findWhere($where, $columns);
    }

    public function delete($id)
    {
        return $this->formListHeadRepository->delete($id);
    }

    public function findByMenuId($menu_id)
    {
        return $this->formListHeadRepository->findBy('menu_id', $menu_id);
    }
}