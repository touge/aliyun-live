<?php

namespace Touge\AdminAliyunLive\Providers;

use Illuminate\Support\ServiceProvider;
use Touge\AdminAliyunLive\Supports\AdminAliyunLive;
use Illuminate\Support\Facades\Route;
class ModuleServiceProvider extends ServiceProvider
{
    protected $config_file= 'touge-aliyun-live.php';
    /**
     * {@inheritdoc}
     */
    public function boot(AdminAliyunLive $extension)
    {
        if (! AdminAliyunLive::boot()) {
            return ;
        }

        if( !file_exists(config_path($this->config_file))){
            $this->loadConfig();
        }

        if ($views = $extension->views()) {
            $this->loadViewsFrom($views, 'touge-aliyun-live');
        }

        if ($this->app->runningInConsole() && $assets = $extension->assets()) {
            $this->publishes([__DIR__.'/../../resources/assets' => public_path('vendor/touge/admin-aliyun-live')], 'touge-admin-aliyun-live-assets');
            $this->publishes([__DIR__.'/../../config' => config_path()], 'touge-admin-aliyun-live-config');
        }

        $this->loadTranslationsFrom(__DIR__ . '/../../resources/lang', 'touge-aliyun');

        $this->app->booted(function () {
            AdminAliyunLive::routes(__DIR__.'/../../routes/web.php');
            static::api_routes(__DIR__ . '/../../routes/api.php');
        });


    }



    /**
     * Set routes for this extension.
     *
     * @param $callback
     */
    public static function api_routes($callback)
    {
        $attributes = array_merge(
            [
                'prefix'=> 'api',
                'namespace'     => '\Touge\AdminAliyunLive\Http\Controllers\Api',
                'as'=> 'api.',
                'middleware'=> ['api', 'jwt.auth:api'],
            ],
            AdminAliyunLive::config('route', [])
        );

        Route::group($attributes, $callback);
    }


    protected function loadConfig(){
        $key = substr($this->config_file, 0, -4);
        $full_path= __DIR__ . '/../../config/' . $this->config_file;
        $this->app['config']->set($key, array_merge_recursive(config($key, []), require $full_path));
    }
}