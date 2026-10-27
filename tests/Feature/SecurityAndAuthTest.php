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

    /**
     * Verify saving and updating an experience module item.
     */
    public function test_save_and_update_experience_module(): void
    {
        $user = \App\Models\User::create([
            'name' => 'Owner',
            'email' => 'owner2@resumed.com',
            'password' => bcrypt('password123'),
        ]);

        $this->actingAs($user);

        $persona = \App\Models\Personal::create([
            'fullname' => 'Owner Persona',
            'email' => 'owner2@resumed.com',
            'user_id' => $user->id,
            'status' => 1,
        ]);

        $response = $this->post('/experiences', [
            'position' => 'Lead Architect',
            'employer' => 'Tech Corp',
            'startDate' => '01/01/2021',
            'current' => 'current',
            'description' => 'Working on cloud systems.',
        ]);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('experiences', [
            'position' => 'Lead Architect',
            'user_id' => $user->id,
        ]);

        $exp = \App\Models\Experiences::where('position', 'Lead Architect')->first();
        $this->assertNotNull($exp);

        $updateResponse = $this->put('/experiences/' . $exp->id, [
            'position' => 'Principal Architect',
            'employer' => 'Tech Corp Inc',
            'startDate' => '01/01/2021',
            'description' => 'Updated description.',
        ]);

        if ($updateResponse->status() !== 302) {
            fwrite(STDERR, "UPDATE STATUS: " . $updateResponse->status() . "\n" . $updateResponse->getContent() . "\n");
        }

        $updateResponse->assertSessionHasNoErrors();
        $this->assertDatabaseHas('experiences', [
            'id' => $exp->id,
            'position' => 'Principal Architect',
        ]);
    }

    /**
     * Verify saving and updating all other resume modules.
     */
    public function test_save_and_update_all_other_modules(): void
    {
        $user = \App\Models\User::create([
            'name' => 'Owner All Modules',
            'email' => 'modules_owner@resumed.com',
            'password' => bcrypt('password123'),
        ]);

        $this->actingAs($user);

        $persona = \App\Models\Personal::create([
            'fullname' => 'Owner Persona 3',
            'email' => 'modules_owner@resumed.com',
            'user_id' => $user->id,
            'status' => 1,
        ]);

        // 1. Educations
        $res = $this->post('/educations', ['name' => 'BSc Computer Science', 'institution' => 'University A']);
        $res->assertSessionHasNoErrors();
        $edu = \App\Models\Educations::where('name', 'BSc Computer Science')->first();
        $this->assertNotNull($edu);
        $res = $this->put('/educations/' . $edu->id, ['name' => 'MSc Computer Science', 'institution' => 'University B']);
        $res->assertSessionHasNoErrors();
        $this->assertDatabaseHas('educations', ['id' => $edu->id, 'name' => 'MSc Computer Science']);

        // 2. Skills
        $res = $this->post('/skills', ['name' => 'PHP', 'percentage' => 90]);
        $res->assertSessionHasNoErrors();
        $skill = \App\Models\Skills::where('name', 'PHP')->first();
        $this->assertNotNull($skill);
        $res = $this->put('/skills/' . $skill->id, ['name' => 'PHP 8.5', 'percentage' => 95]);
        $res->assertSessionHasNoErrors();
        $this->assertDatabaseHas('skills', ['id' => $skill->id, 'name' => 'PHP 8.5']);

        // 3. Projects
        $res = $this->post('/projects', ['name' => 'Resumed App', 'description' => 'Resume platform']);
        $res->assertSessionHasNoErrors();
        $project = \App\Models\Projects::where('name', 'Resumed App')->first();
        $this->assertNotNull($project);
        $res = $this->put('/projects/' . $project->id, ['name' => 'Resumed v2', 'description' => 'Modernized resume']);
        $res->assertSessionHasNoErrors();
        $this->assertDatabaseHas('projects', ['id' => $project->id, 'name' => 'Resumed v2']);

        // 4. Languages
        $res = $this->post('/languages', ['name' => 'English']);
        $res->assertSessionHasNoErrors();
        $lang = \App\Models\Languages::where('name', 'English')->first();
        $this->assertNotNull($lang);
        $res = $this->put('/languages/' . $lang->id, ['name' => 'English (US)']);
        $res->assertSessionHasNoErrors();
        $this->assertDatabaseHas('languages', ['id' => $lang->id, 'name' => 'English (US)']);

        // 5. Socials
        $res = $this->post('/socials', ['name' => 'GitHub', 'link' => 'https://github.com/myprofile']);
        $res->assertSessionHasNoErrors();
        $social = \App\Models\Socials::where('name', 'GitHub')->first();
        $this->assertNotNull($social);
        $res = $this->put('/socials/' . $social->id, ['name' => 'GitHub Profile', 'link' => 'https://github.com/updated']);
        $res->assertSessionHasNoErrors();
        $this->assertDatabaseHas('socials', ['id' => $social->id, 'name' => 'GitHub Profile']);

        // 6. Interests
        $res = $this->post('/interests', ['name' => 'Open Source']);
        $res->assertSessionHasNoErrors();
        $interest = \App\Models\Interests::where('name', 'Open Source')->first();
        $this->assertNotNull($interest);
        $res = $this->put('/interests/' . $interest->id, ['name' => 'AI & Cloud']);
        $res->assertSessionHasNoErrors();
        $this->assertDatabaseHas('interests', ['id' => $interest->id, 'name' => 'AI & Cloud']);

        // 7. Objectives
        $res = $this->post('/objectives', ['content' => 'Build high-performance web systems', 'is_active' => '1']);
        $res->assertSessionHasNoErrors();
        $obj = \App\Models\Objectives::where('content', 'Build high-performance web systems')->first();
        $this->assertNotNull($obj);
        $res = $this->put('/objectives/' . $obj->id, ['content' => 'Lead architecture teams', 'is_active' => '1']);
        $res->assertSessionHasNoErrors();
        $this->assertDatabaseHas('objectives', ['id' => $obj->id, 'content' => 'Lead architecture teams']);
    }
}
