<?php

namespace App\Modules\Definedform\Models;

use Illuminate\Database\Eloquent\Model;

class ProcessNodeLink extends Model
{
    protected $table = 'process_node_link';

    public function next_node()
    {
        return $this->hasOne(ProcessNode::class, 'id', 'next_id');
    }

    public function prev_node()
    {
        return $this->hasOne(ProcessNode::class, 'id', 'prev_id');
    }
}
