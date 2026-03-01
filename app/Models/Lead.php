<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lead extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'lead_number', 'name', 'email', 'phone', 'company',
        'source', 'campaign', 'status', 'priority',
        'assigned_to', 'created_by', 'estimated_value',
        'follow_up_date', 'notes', 'tags',
    ];

    protected $casts = [
        'estimated_value' => 'decimal:2',
        'follow_up_date'  => 'date',
    ];

    public function assignedTo()
    {
        return $this->belongsTo(CrmUser::class, 'assigned_to');
    }

    public function createdBy()
    {
        return $this->belongsTo(CrmUser::class, 'created_by');
    }

    public function history()
    {
        return $this->hasMany(LeadHistory::class);
    }

    public function tasks()
    {
        return $this->hasMany(CrmTask::class, 'related_id')->where('related_type', 'Lead');
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'New'       => 'blue',
            'Contacted' => 'yellow',
            'Qualified' => 'indigo',
            'Lost'      => 'red',
            'Converted' => 'green',
            default     => 'gray',
        };
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