<?php


namespace App\Modules\Definedform\Repositories;


use App\Modules\Definedform\Models\FormLog;

class FormLogRepository implements FormLogRepositoryInterface
{
    /**
     * @var FormLog 注入的 FormLog Model
     */
    protected $formLog;

    public function __construct(FormLog $formLog)
    {
        $this->formLog = $formLog;
    }

    public function all($columns = ['*'])
    {
        // TODO: Implement all() method.
    }

    public function paginate($perPage = 15, $columns = ['*'])
    {
        // TODO: Implement paginate() method.
    }

    public function create(array $data)
    {
        return FormLog::create($data);
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
        return FormLog::where('id',$id)->update($data);
    }

    public function updateBy(array $data, $field, $value, $columns = ['*'])
    {
        return FormLog::select($columns)->where($field,$value)->update($data);
    }

    public function updateWhere(array $data, $where, $columns = ['*'])
    {
        return FormLog::select($columns)->where($where)->update($data);
    }

    public function find($id, $columns = ['*'])
    {
        return FormLog::select($columns)->where('id',$id)->first();
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
        // TODO: Implement findBy() method.
    }

    public function findFirstWhere($where, $columns = ['*'])
    {
        // TODO: Implement findWhere() method.
    }
}