<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Helpers\Helper;
use App\Models\User;

class HelperAndModelTest extends TestCase
{
    /**
     * Test helper format date functionality.
     */
    public function test_helper_format_date(): void
    {
        $this->assertEquals('Empty Date', Helper::formatDate(null));

        $formatted = Helper::formatDate('2026-10-27 12:00:00');
        $this->assertNotEmpty($formatted);
    }

    /**
     * Test helper system modules list does not include register.
     */
    public function test_system_modules_excludes_register(): void
    {
        $modules = Helper::getSystemModules();
        $this->assertIsArray($modules);
        $this->assertNotContains('register', $modules);
    }

    /**
     * Test user model properly hides sensitive authentication tokens and temp passwords.
     */
    public function test_user_model_hides_sensitive_credentials(): void
    {
        $user = new User();
        $hidden = $user->getHidden();

        $this->assertContains('password', $hidden);
        $this->assertContains('remember_token', $hidden);
        $this->assertContains('email_token', $hidden);
        $this->assertContains('temp_pwd', $hidden);
    }

    /**
     * Test that passwords and temporary passwords are never logged into activity log.
     */
    public function test_user_activity_log_never_logs_passwords(): void
    {
        $user = new User();
        $logOptions = $user->getActivitylogOptions();

        // Using reflection to inspect logAttributes
        $reflection = new \ReflectionClass($logOptions);
        $property = $reflection->getProperty('logAttributes');
        $loggedFields = $property->getValue($logOptions);

        $this->assertNotContains('password', $loggedFields);
        $this->assertNotContains('temp_pwd', $loggedFields);
    }
}
