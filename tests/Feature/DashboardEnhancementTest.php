<?php

namespace Tests\Feature;

use App\Models\Experiences;
use App\Models\Personal;
use App\Models\Projects;
use App\Models\Skills;
use App\Models\User;
use App\Models\Visitors;
use Tests\TestCase;

class DashboardEnhancementTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        \Illuminate\Support\Facades\Artisan::call('migrate');
    }

    /**
     * Test dashboard renders successfully with enhanced variables for authenticated user.
     */
    public function test_dashboard_renders_with_enhanced_telemetry_and_personas(): void
    {
        $user = User::create([
            'name' => 'Dashboard Tester',
            'email' => 'dash_test@resumed.com',
            'password' => bcrypt('password123'),
        ]);

        $personaA = Personal::create([
            'fullname' => 'Alice Dev',
            'tagline' => 'Senior Full-Stack Engineer',
            'email' => 'alice@resumed.com',
            'phone' => '+123456789',
            'status' => 1,
            'user_id' => $user->id,
        ]);

        $personaB = Personal::create([
            'fullname' => 'Alice Lead',
            'tagline' => 'Engineering Manager',
            'email' => 'alice_lead@resumed.com',
            'phone' => '+123456789',
            'status' => 0,
            'user_id' => $user->id,
        ]);

        Experiences::create([
            'position' => 'Senior Engineer',
            'employer' => 'Acme Inc',
            'start' => '2020-01-01',
            'user_id' => $user->id,
            'personal_id' => $personaA->id,
        ]);

        Skills::create([
            'name' => 'Laravel',
            'percentage' => 95,
            'user_id' => $user->id,
            'personal_id' => $personaA->id,
        ]);

        Visitors::create([
            'userip' => '127.0.0.1',
            'country' => 'Malaysia',
            'countryCode' => 'MY',
            'latitude' => '3.1390',
            'longitude' => '101.6869',
            'datetime' => now(),
        ]);

        $this->actingAs($user);

        $response = $this->get('/home');
        $response->assertStatus(200);
        $response->assertViewHas('activePersona');
        $response->assertViewHas('allPersonas');
        $response->assertViewHas('visitorStats');
        $response->assertViewHas('recentActivities');
        $response->assertViewHas('readiness');

        $response->assertSee('Alice Dev');
        $response->assertSee('Senior Full-Stack Engineer');
        $response->assertSee('Total Site Visitors');
        $response->assertSee('Career Experience');
        $response->assertSee('Showcase Projects');
        $response->assertSee('Profile Readiness');
    }

    /**
     * Test switching active persona via AJAX endpoint.
     */
    public function test_quick_persona_switching_endpoint(): void
    {
        $user = User::create([
            'name' => 'Persona Switcher',
            'email' => 'switcher@resumed.com',
            'password' => bcrypt('password123'),
        ]);

        $personaA = Personal::create([
            'fullname' => 'Bob Engineer',
            'tagline' => 'Cloud Engineer',
            'email' => 'bob_eng@resumed.com',
            'status' => 1,
            'user_id' => $user->id,
        ]);

        $personaB = Personal::create([
            'fullname' => 'Bob Architect',
            'tagline' => 'Solutions Architect',
            'email' => 'bob_arch@resumed.com',
            'status' => 0,
            'user_id' => $user->id,
        ]);

        $this->actingAs($user);

        // Switch to persona B
        $response = $this->postJson("/personal/switch/{$personaB->id}");
        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
        ]);

        // Verify status in DB: persona A should be 0, persona B should be 1
        $this->assertEquals(0, $personaA->fresh()->status);
        $this->assertEquals(1, $personaB->fresh()->status);

        // Switch back to persona A
        $response = $this->postJson("/personal/switch/{$personaA->id}");
        $response->assertStatus(200);
        $this->assertEquals(1, $personaA->fresh()->status);
        $this->assertEquals(0, $personaB->fresh()->status);
    }

    /**
     * Test switching persona belonging to another user fails with 404.
     */
    public function test_cannot_switch_other_user_persona(): void
    {
        $user1 = User::create([
            'name' => 'User One',
            'email' => 'user1@resumed.com',
            'password' => bcrypt('password123'),
        ]);

        $user2 = User::create([
            'name' => 'User Two',
            'email' => 'user2@resumed.com',
            'password' => bcrypt('password123'),
        ]);

        $personaUser2 = Personal::create([
            'fullname' => 'User Two Persona',
            'email' => 'user2_p@resumed.com',
            'status' => 1,
            'user_id' => $user2->id,
        ]);

        $this->actingAs($user1);

        $response = $this->postJson("/personal/switch/{$personaUser2->id}");
        $response->assertStatus(404);
    }
}
