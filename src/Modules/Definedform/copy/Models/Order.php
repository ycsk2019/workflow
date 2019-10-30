<?php

namespace App\Modules\Definedform\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;

    protected $table = 'form_orders';

    protected $fillable = ['order_no','audit_user_id','customer_id','apply_time','form_log_id','company_id','updated_at','created_at'];

    protected $guarded = ['id'];

    protected $dates = ['deleted_at'];

    public function form_logs(){
        //return $this->hasOne(FormLog::class,'id','form_log_id');
        return $this->hasOne(FormLog::class,'order_id','id');
    }
}
