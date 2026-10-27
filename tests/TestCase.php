<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        putenv('TELESCOPE_ENABLED=false');
        $_ENV['TELESCOPE_ENABLED'] = 'false';
        $_SERVER['TELESCOPE_ENABLED'] = 'false';

        parent::setUp();
    }
}
