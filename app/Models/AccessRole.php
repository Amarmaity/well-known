<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccessRole extends Model
{
    protected $table = 'access_roles';

    protected $fillable = [
        'role_name',
        'status',
    ];
}