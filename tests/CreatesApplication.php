<?php

namespace Tests;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Application;
use RuntimeException;

trait CreatesApplication
{
    /**
     * Creates the application.
     *
     * @return Application
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        // A cached config replaces every <server> line in phpunit.xml.
        if (file_exists($app->getCachedConfigPath())) {
            throw new RuntimeException('Tests cannot run with a cached config. Run "php artisan config:clear".');
        }

        $app->make(Kernel::class)->bootstrap();

        return $app;
    }
}
