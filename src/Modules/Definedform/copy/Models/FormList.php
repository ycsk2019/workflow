<?php

namespace App\Modules\Definedform\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FormList extends Model
{
    use SoftDeletes;

    protected $fillable = ['menu_id','title','form_name_cn','field_label','field_no','form_format_id','item_order','system_field_id','type','searchable'];

    protected $guarded = ['id'];

    protected $dates = ['deleted_at'];

    /**
     * 获得此列表项关联的表单模板
     */
    public function form_format(){
        return $this->belongsToMany('App\Modules\Definedform\Models\FormFormat')->select('form_formats.id')->withPivot('field_no', 'field_label', 'form_name_cn');
    }
}
