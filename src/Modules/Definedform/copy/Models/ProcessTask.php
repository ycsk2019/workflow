<?php

namespace App\Modules\Definedform\Models;

use Illuminate\Database\Eloquent\Model;

class ProcessTask extends Model
{
    protected $table = 'process_task';

    protected $fillable =  ['process_id',
        'process_instance_id',
        'node_instance_id',
        'node_id',
        'node_tag',
        'order_id',
        'process_title',
        'node_title',
        'status',
        'is_completed',
        'is_locked',
        'admin_user_id',
        'company_id',
        'type',
        'remark',
        'claimed_at',
        'stopped_at',
    ];

    protected $guarded = ['id','updated_at','created_at'];
}
