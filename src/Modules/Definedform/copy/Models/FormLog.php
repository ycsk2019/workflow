<?php

namespace App\Modules\Definedform\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FormLog extends Model
{
    use SoftDeletes;

    protected $fillable = ['form_name','form_name_cn','form_no','form_info','node_id','order_id','form_format_id','menu_id','updated_at','created_at'];

    protected $guarded = ['id'];

    protected $dates = ['deleted_at'];
}
