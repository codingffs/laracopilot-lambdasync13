<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'email', 'phone', 'company', 'industry',
        'website', 'address', 'source', 'status', 'notes', 'lead_id',
    ];

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    public function deals()
    {
        return $this->hasMany(Deal::class);
    }

    public function tasks()
    {
        return $this->hasMany(CrmTask::class, 'related_id')->where('related_type', 'Customer');
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'Active'   => 'green',
            'Inactive' => 'yellow',
            'Churned'  => 'red',
            default    => 'gray',
        };
    }
}