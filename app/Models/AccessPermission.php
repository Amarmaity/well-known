<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccessPermission extends Model
{
    protected $fillable = [
        'user_id',
        'module_id',
    ];

    public function module()
    {
        return $this->belongsTo(AccessModule::class, 'module_id');
    }
}