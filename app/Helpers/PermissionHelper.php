<?php

use App\Models\AccessModule;

if (!function_exists('canAccess')) {

    function canAccess($module)
    {
        if (session('user_type') === 'Super User') {
            return true;
        }

        $permissions = session('permissions', []);

        if (empty($permissions)) {
            return false;
        }

        $permissions = array_map('strval', $permissions);
        $moduleValue = (string) $module;

        if (in_array($moduleValue, $permissions, true)) {
            return true;
        }

        if (!is_numeric($module)) {
            return false;
        }

        static $moduleKeyCache = [];

        if (!array_key_exists($moduleValue, $moduleKeyCache)) {
            $moduleKeyCache[$moduleValue] = AccessModule::where('id', $module)->value('module_key');
        }

        return $moduleKeyCache[$moduleValue]
            ? in_array((string) $moduleKeyCache[$moduleValue], $permissions, true)
            : false;
    }
}