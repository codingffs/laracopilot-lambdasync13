<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CrmUser extends Model
{
    use SoftDeletes;

    protected $table = 'crm_users';

    protected $fillable = [
        'name', 'email', 'phone', 'password', 'role',
        'department', 'status', 'last_login',
    ];

    protected $hidden = ['password'];

    protected $casts = [
        'last_login' => 'datetime',
    ];

    public function assignedLeads()
    {
        return $this->hasMany(Lead::class, 'assigned_to');
    }

    public function assignedTasks()
    {
        return $this->hasMany(CrmTask::class, 'assigned_to');
    }

    public function assignedDeals()
    {
        return $this->hasMany(Deal::class, 'assigned_to');
    }

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class, 'user_id');
    }

    public function getRoleBadgeColorAttribute()
    {
        return match($this->role) {
            'Super Admin' => 'red',
            'Admin'       => 'orange',
            'Manager'     => 'blue',
            'Executive'   => 'green',
            default       => 'gray',
        };
    }
}