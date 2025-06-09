<?php


if (! function_exists('getClassesList')) {
    function getClassesList($dir)
    {
        $classes = \File::allFiles($dir);
        foreach ($classes as $class) {
            $class->classname = str_replace(
                [app_path(), '/', '.php'],
                ['App', '\\', ''],
                $class->getRealPath()
            );
        }

        return $classes;
    }
}
if (! function_exists('getAllModelNames')) {
    function getAllModelNames($dir)
    {
        $modelNames = [];
        $models = getClassesList($dir);
        foreach ($models as $model) {
            $model_name = explode('\\', $model->classname);
            $modelNames[] = end($model_name);
        }

        return $modelNames;
    }
}

if (!function_exists('getAllUniqueModelNames')) {
    function getAllUniqueModelNames($role = null)
    {
        $modules = getAllModelNames(app_path('Models'));
        if(!is_null($role)){
            $role_models = $role->permissions->pluck('model')->toArray();
            $remaining_modules = array_diff($modules, $role_models ?? []);
        }else{
            $remaining_modules = $modules;
        }

        // Transform to array of label/value objects
        $moduleOptions = collect($remaining_modules)->values()->map(function ($item) {
            return [
                'label' => $item,
                'value' => $item
            ];
        });
        return $moduleOptions;
    }
}
