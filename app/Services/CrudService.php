<?php

namespace App\Services;

use App\Services\Helper\CommandHelper;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

class CrudService extends CommandHelper
{
    public static function makeCrud($name, $console)
    {
        Self::createFolderIfNotExists(app_path('Models/Admin'));
        Self::createFolderIfNotExists(app_path('Http/Controllers/Admin'));
        Self::createFolderIfNotExists(resource_path('js/pages/admin/' . ucfirst($name)));
        Self::createFolderIfNotExists(app_path("Policies"));
        Self::createFolderIfNotExists(app_path("Contracts"));
        Self::createFolderIfNotExists(app_path("Repositories"));




        Self::makeModel($name, $console);
        Self::makeMigration($name, $console);
        Self::makeController($name, $console);
        Self::makeViews($name, $console);
        Self::makeSeeder($name, $console);
        Self::addRouteContent($name, $console);
        Self::addFileContent($name, $console);
        Self::makePolicy($name, $console);
        Self::addMenu($name, $console);

        RepositoryPatternService::repoPattern($name, true);
        $console->info('Repo pattern created for model: ' . $name);
    }

    protected static function makeModel($name, $console)
    {
        $file = app_path("Models/Admin/" . $name . ".php");
        file_put_contents($file, Self::generateContent('Model', $name));
        $console->info('Model Created Successfully');
    }

    protected static function makeMigration($name, $console)
    {
        Artisan::call('make:migration create_' . strtolower(Str::plural($name)) . '_table --create=' . strtolower(Str::plural($name)));
        $console->info('Migration Created Successfully');
    }

    protected static function makeController($name, $console)
    {
        $file = app_path("Http/Controllers/Admin/" . $name . "Controller.php");
        file_put_contents($file, Self::generateContent('Controller', $name));
        $console->info('Controller Created Successfully');
    }

    protected static function makeViews($name, $console)
    {
        $views = ['Index', 'Form', 'Show'];
        foreach ($views as $view) {
            $file = resource_path("js/pages/admin/" . ucfirst($name) . "/{$view}.vue");
            file_put_contents($file, self::generateContent(ucfirst($view) . 'Page', $name));
            $console->info(ucfirst($view) . ' Page Created Successfully');
        }
    }


    protected static function makeSeeder($name, $console)
    {
        Artisan::call('make:seeder ' . $name . 'Seeder');
        $console->info('Seeder file Created Successfully');
    }


    protected static function makePolicy($name, $console)
    {
        $file = app_path("Policies/" . $name . "Policy.php");
        file_put_contents($file, Self::generateContent('Policy', $name));
        $console->info('Policy Created Successfully');
    }

    protected static function addMenu($name, $console)
    {
        $lowercased_name = strtolower(Str::plural($name));
        $file_path = resource_path('js/layouts/admin/AppSidebar.vue'); // Specify the file path
        $content = file_get_contents($file_path); // Read the file content
        // Construct the new item
        $new_item = <<<EOD
    {
        title: '$name',
        href: route('$lowercased_name.index'),
        icon: LayoutGrid,
    },
EOD;

        // Use regex to find the mainNavItems array and inject just before the closing ];
        $pattern = '/const mainNavItems: NavItem\[\] = \[(.*?)\n\];/s';
        $replacement = "const mainNavItems: NavItem[] = [\n$1\n    $new_item\n];";
        $new_content = preg_replace($pattern, $replacement, $content);

        if ($new_content !== null) {
            file_put_contents($file_path, $new_content);
            $console->info('Menu Added Successfully');
        } else {
            $console->error('Failed to add menu item.');
        }
        $console->info('Menu Added Successfully');
    }

    protected static function addRouteContent($name, $console)
    {
        // Adding Route
        $lowercased_name = strtolower(Str::plural($name));
        $route = "Route::resource('{$lowercased_name}',\App\Http\Controllers\Admin\\{$name}Controller::class);\n";

        $filePath = base_path('routes/web.php'); // Specify the // Read the file content
        $content = file_get_contents($filePath);

        // Search for the symbol
        $position = strpos($content, '});');

        if ($position !== false) {
            // Add content before the symbol
            $content = substr_replace($content, $route, $position, 0);
            // Write the updated content back to the file
            file_put_contents($filePath, $content);

            $console->info('Route added Successfully');
        } else {
            $console->info('Route couldnot be added');
        }
    }

    protected static function addFileContent($name, $console)
    {
        // Adding Route Interface Binding
        $repository_interface_binding = '$this->app->bind(\App\Contracts\\' . $name . 'RepositoryInterface::class, \App\Repositories\\' . $name . 'Repository::class);';

        $provider_path = app_path('Providers/AppServiceProvider.php');
        Self::putContentToClassFunction($provider_path, 'protected function repos', $repository_interface_binding);
    }
}
