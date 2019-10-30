<?php


namespace App\Modules\Definedform\Services;


use App\Modules\Definedform\Repositories\FormLogRepositoryInterface;
use Illuminate\Support\Facades\DB;

class FormLogService implements FormLogServiceInterface
{
    protected $formLogRepository;

    public function __construct(FormLogRepositoryInterface $formLogRepository)
    {
        $this->formLogRepository = $formLogRepository;
    }

    public function create(array $data)
    {
        return $this->formLogRepository->create($data);
    }

    public function update(array $data, $id)
    {
        return $this->formLogRepository->update($data, $id);
    }

    public function updateBy(array $data,$field, $value, $columns = ['*'])
    {
        return $this->formLogRepository->updateBy($data,$field, $value, $columns);
    }

    public function updateWhere(array $data, $where, $columns = ['*'])
    {
        return $this->formLogRepository->updateWhere($data, $where, $columns);
    }
}