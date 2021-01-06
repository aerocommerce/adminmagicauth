<?php

namespace Aerocargo\Adminmagicauth;

use Aero\Admin\AdminSlot;
use Aero\Common\Providers\ModuleServiceProvider;
use Illuminate\Routing\Router;

/**
 * Class ServiceProvider
 * @package Aerocargo\Adminmagicauth
 */
class ServiceProvider extends ModuleServiceProvider
{
    public function register()
    {
        if (! $this->app->configurationIsCached()) {
            $this->mergeConfigFrom(__DIR__ . '/../config/adminmagicauth.php', 'adminmagicauth');
        }

        $this->app->bind('adminmagicauth', function() {
            return new AdminMagicAuth;
        });
    }

    public function setup()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'adminmagicauth');
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        Router::addStoreRoutes(__DIR__.'/../routes.php');

        AdminSlot::inject('login.footer', function() {
            return AdminMagicAuthFacade::button();
        });
    }
}
