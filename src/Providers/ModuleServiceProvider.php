<?php

namespace Touge\AdminAliyunLive\Providers;

use Illuminate\Support\ServiceProvider;
use Touge\AdminAliyunLive\Supports\AdminAliyunLive;
use Illuminate\Support\Facades\Route;
class ModuleServiceProvider extends ServiceProvider
{
    /**
     * {@inheritdoc}
     */
    public function boot(AdminAliyunLive $extension)
    {
        if (! AdminAliyunLive::boot()) {
            return ;
        }

        if ($views = $extension->views()) {
            $this->loadViewsFrom($views, 'touge-aliyun-live');
        }

        if ($this->app->runningInConsole() && $assets = $extension->assets()) {
            $this->publishes([
                $assets => public_path('vendor/touge/admin-aliyun-live'),
                AdminAliyunLive::config_path() => config_path()
            ],'touge-admin-aliyun-live'
            );
        }

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
}