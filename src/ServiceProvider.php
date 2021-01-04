<?php

namespace Aerocargo\Aeroauth;

use Aero\Common\Providers\ModuleServiceProvider;
use Aero\Store\Pipelines\ContentForBody;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\URL;

class ServiceProvider extends ModuleServiceProvider
{
    public function register()
    {
        if (! $this->app->configurationIsCached()) {
            $this->mergeConfigFrom(__DIR__.'/../config/aeroauth.php', 'aeroauth');
        }
    }

    public function setup()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'aeroauth');
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        Router::addStoreRoutes(__DIR__.'/../routes.php');

        $whitelistedIps = config('aeroauth')['whitelisted_ips'];

        if (collect($whitelistedIps)->contains(request()->ip())) {
            ContentForBody::extend(function (&$content)  {
                $url = URL::signedRoute('aeroauth.index');
                $content .= view('aeroauth::authheader', ['url' => $url])->render();
            });
        }
    }
}
