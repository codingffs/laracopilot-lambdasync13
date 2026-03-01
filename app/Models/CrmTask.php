<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CrmTask extends Model
{
    use SoftDeletes;

    protected $table = 'crm_tasks';

    protected $fillable = [
        'title', 'description', 'assigned_to', 'created_by',
        'priority', 'status', 'due_date', 'completed_at',
        'related_type', 'related_id',
    ];

    protected $casts = [
        'due_date'     => 'date',
        'completed_at' => 'datetime',
    ];

    public function assignedTo()
    {
        return $this->belongsTo(CrmUser::class, 'assigned_to');
    }

    public function createdBy()
    {
        return $this->belongsTo(CrmUser::class, 'created_by');
    }

    public function isOverdue()
    {
        return $this->status !== 'Completed' && $this->due_date < today();
    }

    public function getPriorityColorAttribute()
    {
        return match($this->priority) {
            'Urgent' => 'red',
            'High'   => 'orange',
            'Medium' => 'yellow',
            'Low'    => 'gray',
            default  => 'gray',
        };
    }
}