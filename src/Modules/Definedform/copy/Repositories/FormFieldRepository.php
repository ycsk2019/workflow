<?php


namespace App\Modules\Definedform\Repositories;

use App\Modules\Definedform\Models\FormField;

class FormFieldRepository implements FormFieldRepositoryInterface
{

    public function all($columns = ['*'])
    {
        return FormField::get();
    }

    public function paginate($perPage = 15, $columns = ['*'])
    {
        // TODO: Implement paginate() method.
    }

    public function create(array $data)
    {
        // TODO: Implement create() method.
    }

    public function save(array $data)
    {
        // TODO: Implement save() method.
    }

    public function delete($id)
    {
        // TODO: Implement delete() method.
    }

    public function update(array $data, $id)
    {
        // TODO: Implement update() method.
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
        return FormField::select($columns)->where('id',$id)->first();
    }

    public function findBy($field, $value, $columns = ['*'])
    {
        // TODO: Implement findBy() method.
    }

    public function findWhere($where, $columns = ['*'])
    {
        // TODO: Implement findWhere() method.
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