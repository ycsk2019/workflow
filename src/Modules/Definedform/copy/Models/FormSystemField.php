<?php


namespace App\Modules\Definedform\Models;

use Illuminate\Database\Eloquent\Model;

class FormSystemField extends Model
{
    protected $fillable = ['system_field_name','system_field_name_cn'];

    protected $guarded = ['id'];
}