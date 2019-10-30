<?php

namespace App\Modules\Definedform\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FormMenu extends Model
{
    use SoftDeletes;

    protected $fillable = ['name','type','workflow_info','level','parent_id'];

    protected $guarded = ['id'];

    protected $dates = ['deleted_at'];

    /**
     * 获得此菜单关联的工作流
     */
    public function process(){
        return $this->belongsToMany('Yiche\Workflow\Models\Process');
    }
}
