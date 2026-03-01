<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Deal extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title', 'customer_id', 'assigned_to', 'stage',
        'value', 'probability', 'expected_close_date', 'description',
    ];

    protected $casts = [
        'value'               => 'decimal:2',
        'probability'         => 'integer',
        'expected_close_date' => 'date',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function assignedTo()
    {
        return $this->belongsTo(CrmUser::class, 'assigned_to');
    }

    public function tasks()
    {
        return $this->hasMany(CrmTask::class, 'related_id')->where('related_type', 'Deal');
    }

    public function getWeightedValueAttribute()
    {
        return $this->value * ($this->probability / 100);
    }

    public function getStageColorAttribute()
    {
        return match($this->stage) {
            'Prospecting'   => 'gray',
            'Qualification' => 'blue',
            'Proposal'      => 'indigo',
            'Negotiation'   => 'yellow',
            'Won'           => 'green',
            'Lost'          => 'red',
            default         => 'gray',
        };
    }
}