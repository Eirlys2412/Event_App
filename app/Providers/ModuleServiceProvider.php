<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\File; // Use the File facade
use Laravel\Passport\Passport;
class ModuleServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    protected function registerPolicies()
    {
        // Define your policy registration logic here
    }

    public function boot()
    {
        // $this->loadModuleRoutes();
        $this->loadModuleViews();
        $this->loadMigration();
        $this->registerPolicies();

        Route::group(['prefix' => 'api', 'middleware' => ['api']], function () {
            Passport::loadKeysFrom(base_path('storage/oauth-public.key'), base_path('storage/oauth-private.key'));
        });
         
    }
    
    protected function loadMigration()
    {
        $modulesPath = base_path('app/Modules');

        if (is_dir($modulesPath)) {
            foreach (scandir($modulesPath) as $module) {
                if ($module === '.' || $module === '..') {
                    continue;
                }

                $viewPath = $modulesPath."/". $module."/Migrations";

               
                if (is_dir($viewPath)) {
                 
                    $this->loadMigrationsFrom([
                        $modulesPath.'/'. $module .'/Migrations' ,
                        
                    ]);
                   
                }
            }
        }
    }
    protected function loadModuleRoutes()
    {
        $modulesPath = base_path('app/Modules');

        if (is_dir($modulesPath)) {
            foreach (scandir($modulesPath) as $module) {
                if ($module === '.' || $module === '..') {
                    continue;
                }

                $routeFile = "$modulesPath/$module/Routes/web.php";
                if (file_exists($routeFile)) {
                    Route::group(['namespace' => "App\\Modules\\$module\\Controllers"], function () use ($routeFile) {
                        require $routeFile;
                    });
                }
            }
        }
    }

    protected function loadModuleViews()
    {
        $modulesPath = base_path('app\Modules');

        if (is_dir($modulesPath)) {
            foreach (scandir($modulesPath) as $module) {
                if ($module === '.' || $module === '..') {
                    continue;
                }

                $viewPath = "$modulesPath/$module/Views/";

                // Check if the views directory exists for the module
                if (is_dir($viewPath)) {
                    // Add the views path to Laravel's view finder
                   
                    $this->loadViewsFrom($viewPath, $module);
                }
            }
        }
    }
}
