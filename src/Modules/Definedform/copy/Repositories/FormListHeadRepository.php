<?php


namespace App\Modules\Definedform\Repositories;

use App\Modules\Definedform\Models\FormListHead;

class FormListHeadRepository implements FormListHeadRepositoryInterface
{

    public function all($columns = ['*'])
    {
        return FormListHead::get();
    }

    public function paginate($perPage = 15, $columns = ['*'])
    {
        // TODO: Implement paginate() method.
    }

    public function create(array $data)
    {
        return FormListHead::create($data);
    }

    public function save(array $data)
    {
        // TODO: Implement save() method.
    }

    public function delete($id)
    {
        return FormListHead::where('id',$id)->delete();
    }

    public function update(array $data, $id)
    {
        return FormListHead::where('id',$id)->update($data);
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
        return FormListHead::select($columns)->where('id',$id)->first();
    }

    public function findBy($field, $value, $columns = ['*'])
    {
        return FormListHead::select($columns)->where($field,$value)->get();
    }

    public function findWhere($where, $columns = ['*'])
    {
        return FormListHead::select($columns)->where($where)->get();
    }

    public function findFirstBy($field, $value, $columns = ['*'])
    {
        // TODO: Implement findFirstBy() method.
    }

    public function findFirstWhere($where, $columns = ['*'])
    {
        // TODO: Implement findFirstWhere() method.
    }
}