<?php


namespace App\Modules\Definedform\Services;

use App\Modules\Definedform\Repositories\FormFieldRepositoryInterface;

class FormFieldService implements FormFieldServiceInterface
{
    protected $formFieldRepository;

    public function __construct(FormFieldRepositoryInterface $formFieldRepository)
    {
        $this->formFieldRepository = $formFieldRepository;
    }

    public function all(){
        return $this->formFieldRepository->all();
    }

    public function find($id, $columns = ['*'])
    {
        return $this->formFieldRepository->find($id, $columns);
    }
}