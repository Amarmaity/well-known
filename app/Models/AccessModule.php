<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccessModule extends Model
{
    protected $fillable = [
        'module_name',
        'module_key',
        'parent_id',
        'icon',
        'sort_order',
        'status',
    ];

    /**
     * Parent Module
     */
    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    /**
     * Child Modules
     */
    public function children()
    {
        return $this->hasMany(self::class, 'parent_id')
                    ->orderBy('sort_order');
    }

    /**
     * Designation Permissions
     */
    public function permissions()
    {
        return $this->hasMany(DesignationPermission::class, 'module_id');
    }
}