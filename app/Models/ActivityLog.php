<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $table = 'activity_logs';

    protected $fillable = ['user_id', 'action', 'module', 'description', 'ip_address'];

    public $updatedAt = false;

    public function user()
    {
        return $this->belongsTo(CrmUser::class, 'user_id');
    }

    public function getActionColorAttribute()
    {
        return match($this->action) {
            'created' => 'green',
            'updated' => 'blue',
            'deleted' => 'red',
            default   => 'gray',
        };
    }
}