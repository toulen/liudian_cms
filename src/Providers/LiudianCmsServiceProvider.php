<?php
namespace Liudian\Cms\Providers;

use Illuminate\Support\ServiceProvider;
use Liudian\Admin\Http\Middleware\AdminAuth;
use Liudian\Admin\Logic\AdminAuthLogic;
use Liudian\Admin\Repositories\AdminRbacPermissionRepository;

class LiudianCmsServiceProvider extends ServiceProvider
{

    protected $routeMiddleware = [];

    protected $middlewareGroups = [];

    public function register(){
    }

    public function boot(){
        $this->loadLiudianAdminConfig();
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
        $this->loadViewsFrom(__DIR__ . '/../../resources/views/', 'cms');

        if($this->app->runningInConsole()){
            $this->publishes([
                __DIR__ . '/../../config/liudian_cms.php' => config_path('liudian_cms.php'),
                __DIR__ . '/../Http/Controllers/Admin/READNE.md' => base_path('liudian/cms/Http/Controllers/Admin/README.md')
            ]);
        }
    }

    protected function loadLiudianAdminConfig(){

//        $this->mergeConfigFrom(__DIR__ . '/../../config/liudian_cms.php', 'liudian_cms');
    }

    protected function registerRouteMiddleware()
    {
        // register route middleware.
        foreach ($this->routeMiddleware as $key => $middleware) {
            app('router')->aliasMiddleware($key, $middleware);
        }
        // register middleware group.
        foreach ($this->middlewareGroups as $key => $middleware) {
            app('router')->middlewareGroup($key, $middleware);
        }
    }
}