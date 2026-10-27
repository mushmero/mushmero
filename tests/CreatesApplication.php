<?php

namespace Tests;

use Illuminate\Contracts\Console\Kernel;

trait CreatesApplication
{
    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        putenv('APP_ENV=testing');
        putenv('TELESCOPE_ENABLED=false');
        putenv('DB_CONNECTION=sqlite');
        putenv('DB_DATABASE=:memory:');
        putenv('SESSION_DRIVER=array');
        putenv('CACHE_DRIVER=array');

        $_ENV['APP_ENV'] = 'testing';
        $_ENV['TELESCOPE_ENABLED'] = 'false';
        $_ENV['DB_CONNECTION'] = 'sqlite';
        $_ENV['DB_DATABASE'] = ':memory:';
        $_ENV['SESSION_DRIVER'] = 'array';
        $_ENV['CACHE_DRIVER'] = 'array';

        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();

        return $app;
    }
}
