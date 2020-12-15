<?php

namespace Application;

use Artister\DevNet\Configuration\IConfiguration;
use Artister\DevNet\Dependency\IServiceCollection;
use Artister\DevNet\Dispatcher\IApplicationBuilder;
use Artister\DevNet\Extensions\DependencyExtensions;
use Artister\DevNet\Extensions\HostingExtensions;

class Startup
{
    private IConfiguration $Configuration;

    public function __construct(IConfiguration $configuration)
    {
        $this->Configuration = $configuration;
    }

    public function configureServices(IServiceCollection $services)
    {
        $services->addMvc();
        
        $services->addAuthentication();

        $services->addAuthorisation();
    }

    public function configure(IApplicationBuilder $app)
    {
        $app->UseExceptionHandler();

        $app->useRouter();

        $app->useAuthentication();

        $app->useAuthorization();
        
        $app->useEndpoint(function($routes) {
            Routes::registerRoutes($routes);
            $routes->mapRoute("default", "{controller=Home}/{action=Index}/{id?}");
        });
    }
}