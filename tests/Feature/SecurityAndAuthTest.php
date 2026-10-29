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

    /**
     * Verify delete operations and show views across modules.
     */
    public function test_module_delete_and_show_operations(): void
    {
        $user = \App\Models\User::create([
            'name' => 'Owner Delete',
            'email' => 'owner_del@resumed.com',
            'password' => bcrypt('password123'),
        ]);

        $this->actingAs($user);

        $persona = \App\Models\Personal::create([
            'fullname' => 'Owner Del Persona',
            'email' => 'owner_del@resumed.com',
            'user_id' => $user->id,
            'status' => 1,
        ]);

        $exp = \App\Models\Experiences::create([
            'position' => 'Temporary Position',
            'employer' => 'Short Corp',
            'user_id' => $user->id,
            'personal_id' => $persona->id,
        ]);

        // Test show
        $showRes = $this->get('/experiences/' . $exp->id . '/show');
        $showRes->assertStatus(200);

        // Test delete
        $delRes = $this->get('/experiences/' . $exp->id . '/delete');
        $delRes->assertRedirect('/experiences');
        $this->assertDatabaseMissing('experiences', ['id' => $exp->id]);
    }

    /**
     * Verify CRUD operations on Settings lookups (education, language, project, social, pdf, template).
     */
    public function test_settings_lookups_crud(): void
    {
        $user = \App\Models\User::create([
            'name' => 'Settings Admin',
            'email' => 'settings_admin@resumed.com',
            'password' => bcrypt('password123'),
        ]);

        $this->actingAs($user);

        // 1. Settings Education
        $res = $this->post('/settings_education', ['name' => 'Master Degree', 'level' => 'Postgraduate']);
        $res->assertSessionHasNoErrors();
        $eduLevel = \App\Models\SettingsEducations::where('name', 'Master Degree')->first();
        $this->assertNotNull($eduLevel);
        $res = $this->put('/settings_education/' . $eduLevel->id, ['name' => 'Master of Science', 'level' => 'Postgraduate']);
        $res->assertSessionHasNoErrors();
        $this->assertDatabaseHas('settings_educations', ['id' => $eduLevel->id, 'name' => 'Master of Science']);

        // 2. Settings Language
        $res = $this->post('/settings_language', ['proficiency' => 'C2 Native']);
        $res->assertSessionHasNoErrors();
        $langLevel = \App\Models\SettingsLanguage::where('proficiency', 'C2 Native')->first();
        $this->assertNotNull($langLevel);
        $res = $this->put('/settings_language/' . $langLevel->id, ['proficiency' => 'C2 Fluent']);
        $res->assertSessionHasNoErrors();
        $this->assertDatabaseHas('settings_languages', ['id' => $langLevel->id, 'proficiency' => 'C2 Fluent']);

        // 3. Settings Project Status
        $res = $this->post('/settings_project', ['status' => 'Shipped']);
        $res->assertSessionHasNoErrors();
        $projStatus = \App\Models\SettingsProject::where('status', 'Shipped')->first();
        $this->assertNotNull($projStatus);
        $res = $this->put('/settings_project/' . $projStatus->id, ['status' => 'Production Live']);
        $res->assertSessionHasNoErrors();
        $this->assertDatabaseHas('settings_projects', ['id' => $projStatus->id, 'status' => 'Production Live']);

        // 4. Settings Social Icon
        $res = $this->post('/settings_social', ['name' => 'GitLab', 'fullname' => 'fab fa-gitlab', 'type' => 'brand']);
        $res->assertSessionHasNoErrors();
        $socialIcon = \App\Models\SettingsSocial::where('name', 'GitLab')->first();
        $this->assertNotNull($socialIcon);
        $res = $this->put('/settings_social/' . $socialIcon->id, ['name' => 'GitLab CE', 'fullname' => 'fab fa-gitlab', 'type' => 'brand']);
        $res->assertSessionHasNoErrors();
        $this->assertDatabaseHas('settings_socials', ['id' => $socialIcon->id, 'name' => 'GitLab CE']);

        // 5. Settings PDF
        $res = $this->post('/settings_pdf', ['name' => 'Custom Executive PDF', 'margin_top' => 10, 'margin_bottom' => 10]);
        $res->assertSessionHasNoErrors();
        $pdfSetting = \App\Models\SettingsPDF::where('name', 'Custom Executive PDF')->first();
        $this->assertNotNull($pdfSetting);
        $res = $this->put('/settings_pdf/' . $pdfSetting->id, ['name' => 'Custom Minimalist PDF', 'margin_top' => 15]);
        $res->assertSessionHasNoErrors();
        $this->assertDatabaseHas('settings_pdfs', ['id' => $pdfSetting->id, 'name' => 'Custom Minimalist PDF']);

        // 6. Settings Template
        $res = $this->post('/settings_template', [
            'name' => 'Modern Minimalist',
            'design' => '<div class="main-color profile-name profile-tagline"></div>',
            'custom_css' => '',
            'primary_colour' => 'bg-dark',
            'secondary_colour' => 'bg-light',
        ]);
        if ($res->getSession() && $res->getSession()->get('errors')) {
            fwrite(STDERR, "TEMPLATE ERRORS: " . json_encode($res->getSession()->get('errors')->all()) . "\n");
        }
        $template = \App\Models\SettingsTemplate::where('name', 'Modern Minimalist')->first();
        $this->assertNotNull($template);
        $res = $this->put('/settings_template/' . $template->id, [
            'name' => 'Modern Dark',
            'design' => '<div class="main-color profile-name profile-tagline"></div>',
            'primary_colour' => 'bg-black',
            'secondary_colour' => 'bg-light',
        ]);
        $res->assertSessionHasNoErrors();
        $this->assertDatabaseHas('settings_templates', ['id' => $template->id, 'name' => 'Modern Dark']);
    }

    /**
     * Verify public landing page renders and leads submission endpoint works.
     */
    public function test_public_landing_and_leads_submission(): void
    {
        $user = \App\Models\User::create([
            'name' => 'Public Creator',
            'email' => 'creator@resumed.com',
            'password' => bcrypt('password123'),
        ]);

        $persona = \App\Models\Personal::create([
            'fullname' => 'Alex Developer',
            'tagline' => 'Full-Stack Engineer',
            'email' => 'creator@resumed.com',
            'user_id' => $user->id,
            'status' => 1,
        ]);

        // Public page loads active persona
        $response = $this->get('/');
        $response->assertStatus(200);

        // Submit leads interest form
        $leadResponse = $this->postJson('/', [
            'name' => 'Jane Recruiter',
            'email' => 'jane.recruiter@hiring.com',
        ]);

        $leadResponse->assertStatus(200);
        $leadResponse->assertJson(['success' => 'Thank you for your interest']);
        $this->assertDatabaseHas('leads', ['email' => 'jane.recruiter@hiring.com']);
    }

    /**
     * Verify DemoDataSeeder executes cleanly and renders the demo resume on the public page.
     */
    public function test_demo_data_seeder_executes_cleanly_and_renders_public_page(): void
    {
        $seeder = new \Database\Seeders\DemoDataSeeder();
        $seeder->run();

        // 1. Verify Demo User
        $user = \App\Models\User::where('email', 'demo@resumed.com')->first();
        $this->assertNotNull($user);
        $this->assertTrue(\Illuminate\Support\Facades\Hash::check('demo123', $user->password));

        // 2. Verify Demo Persona
        $persona = \App\Models\Personal::where('email', 'demo.alex@resumed.com')->first();
        $this->assertNotNull($persona);
        $this->assertEquals(1, $persona->status);
        $this->assertEquals($user->id, $persona->user_id);

        // 3. Verify Seeded Modules
        $this->assertGreaterThanOrEqual(3, \App\Models\Experiences::where('personal_id', $persona->id)->count());
        $this->assertGreaterThanOrEqual(5, \App\Models\Skills::where('personal_id', $persona->id)->count());
        $this->assertGreaterThanOrEqual(2, \App\Models\Projects::where('personal_id', $persona->id)->count());
        $this->assertGreaterThanOrEqual(1, \App\Models\Educations::where('personal_id', $persona->id)->count());
        $this->assertGreaterThanOrEqual(3, \App\Models\Socials::where('personal_id', $persona->id)->count());
        $this->assertGreaterThanOrEqual(2, \App\Models\Languages::where('personal_id', $persona->id)->count());
        $this->assertNotNull(\App\Models\Objectives::where('personal_id', $persona->id)->first());

        // 4. Verify Public Page Renders Demo Persona
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Alex Morgan');
        $response->assertSee('Senior Full-Stack &amp; Cloud Architect', false);

        // 5. Verify Demo User Can Log In and Access Dashboard
        $this->actingAs($user);
        $homeRes = $this->get('/home');
        $homeRes->assertStatus(200);

        // 6. Verify Template Preview Renders with Seeded Template
        $template = \App\Models\SettingsTemplate::where('name', 'Modern Professional')->first();
        $this->assertNotNull($template);
        $previewRes = $this->get('/settings_template/' . $template->id . '/preview');
        $previewRes->assertStatus(200);
        $previewRes->assertSee('Alex Morgan');
    }

    /**
     * Verify that IS_DEMO controls login link on public page and credentials banner on login page.
     */
    public function test_is_demo_controls_login_link_and_credentials_visibility(): void
    {
        // 1. By default (is_demo = false)
        config(['app.is_demo' => false]);
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertDontSee('Demo Access');

        // 2. In Demo Mode (is_demo = true)
        config(['app.is_demo' => true]);
        $loginRes = $this->get('/login');
        $loginRes->assertStatus(200);
        $loginRes->assertSee('Demo Access');
        $loginRes->assertSee('demo@resumed.com');
        $loginRes->assertSee('demo123');

        // Verify public landing page renders Demo Login button
        $user = \App\Models\User::firstOrCreate(
            ['email' => 'demo_btn@resumed.com'],
            ['name' => 'Demo Btn', 'password' => bcrypt('password123')]
        );
        \App\Models\Personal::firstOrCreate(
            ['email' => 'demo_btn_persona@resumed.com'],
            ['fullname' => 'Demo User', 'user_id' => $user->id, 'status' => 1]
        );

        $homeRes = $this->get('/');
        $homeRes->assertStatus(200);
        $homeRes->assertSee('Demo Login');
    }
}
