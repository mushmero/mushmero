<?php

namespace Tests\Feature;

use Tests\TestCase;

class SecurityAndAuthTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        \Illuminate\Support\Facades\Artisan::call('migrate');
    }

    /**
     * Verify that public registration route is completely disabled (returns 404).
     */
    public function test_public_registration_is_disabled(): void
    {
        $response = $this->get('/register');
        $response->assertStatus(404);
    }

    /**
     * Verify that direct POST to /register is also blocked.
     */
    public function test_public_registration_post_is_blocked(): void
    {
        $response = $this->post('/register', [
            'name' => 'Attacker',
            'email' => 'attacker@example.com',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
        ]);

        $response->assertStatus(404);
    }

    /**
     * Verify that the login page loads properly.
     */
    public function test_login_page_is_accessible(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
    }

    /**
     * Verify that OWASP security headers are returned on HTTP responses.
     */
    public function test_security_headers_are_present(): void
    {
        $response = $this->get('/login');

        $response->assertHeader('X-Frame-Options', 'SAMEORIGIN');
        $response->assertHeader('X-Content-Type-Options', 'nosniff');
        $response->assertHeader('X-XSS-Protection', '1; mode=block');
        $response->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
    }

    /**
     * Verify that unauthenticated visitors cannot access protected administration routes.
     */
    public function test_unauthenticated_visitors_redirected_to_login(): void
    {
        $protectedRoutes = ['/home', '/personal', '/settings_users', '/user_access', '/visitors'];

        foreach ($protectedRoutes as $route) {
            $response = $this->get($route);
            $response->assertRedirect('/login');
        }
    }

    /**
     * Verify single-user multiple persona lifecycle and active persona resolution.
     */
    public function test_single_user_multiple_personas_lifecycle(): void
    {
        $user = \App\Models\User::create([
            'name' => 'Site Owner',
            'email' => 'owner@resumed.com',
            'password' => bcrypt('password123'),
        ]);

        $personaA = \App\Models\Personal::create([
            'fullname' => 'John Software Engineer',
            'tagline' => 'Senior Backend Engineer',
            'email' => 'john.dev@resumed.com',
            'status' => 1,
            'user_id' => $user->id,
        ]);

        $personaB = \App\Models\Personal::create([
            'fullname' => 'John Consultant',
            'tagline' => 'Cloud Solutions Architect',
            'email' => 'john.consultant@resumed.com',
            'status' => 0,
            'user_id' => $user->id,
        ]);

        $activePersona = \App\Http\Traits\PersonaTrait::getActivePersonaInformations($user->id);
        $this->assertNotNull($activePersona);
        $this->assertEquals('John Software Engineer', $activePersona->fullname);

        // Switch active persona to Persona B
        $personaA->update(['status' => 0]);
        $personaB->update(['status' => 1]);

        $activePersonaAfterSwitch = \App\Http\Traits\PersonaTrait::getActivePersonaInformations($user->id);
        $this->assertNotNull($activePersonaAfterSwitch);
        $this->assertEquals('John Consultant', $activePersonaAfterSwitch->fullname);
    }
}
