<?php


namespace App\Modules\Definedform\Repositories;


interface WorkflowRepositoryInterface
{
    public function all($columns = ['*']);    //获取所有记录

    public function paginate($perPage = 15, $columns = ['*']);    //分页，默认每页15条

    public function create(array $data);    //创建一条记录

    public function save(array $data);    //保存

    public function delete($id);    //删除一条记录

    public function update(array $data);    //更新记录

    public function updateBy(array $data, $field, $value, $columns = ['*']);     //按指定字段更新记录

    public function updateWhere(array $data, $where, $columns = ['*']);      //按多个条件更新记录

    public function find($id, $columns = ['*']);    //按id查找

    public function findBy($field, $value, $columns = ['*']);    //按指定字段查找

    public function findWhere($where, $columns = ['*']);    //按多个条件查找

    public function findFirstBy($field, $value, $columns = ['*']);    //按指定字段查找第一条

    public function findFirstWhere($where, $columns = ['*']);    //按多个条件查找第一条
}