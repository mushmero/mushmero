<?php

namespace Database\Seeders;

use App\Models\Educations;
use App\Models\Experiences;
use App\Models\Interests;
use App\Models\Languages;
use App\Models\Objectives;
use App\Models\Personal;
use App\Models\Projects;
use App\Models\SettingsEducations;
use App\Models\SettingsLanguage;
use App\Models\SettingsPDF;
use App\Models\SettingsProject;
use App\Models\SettingsSocial;
use App\Models\SettingsTemplate;
use App\Models\Skills;
use App\Models\Socials;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DemoDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->command?->info('Seeding lookup settings...');
        $settings = $this->seedSettings();

        $this->command?->info('Seeding demo user and resume profile...');
        $this->seedDemoUserAndResume($settings);

        $this->command?->info('Demo data seeding completed successfully!');
    }

    /**
     * Seed lookup settings for dropdowns, icons, templates, and PDFs.
     */
    public function seedSettings(): array
    {
        // 1. Social & Technical Icons
        $socials = [
            ['name' => 'GitHub', 'fullname' => 'fab fa-github', 'type' => 'brand'],
            ['name' => 'LinkedIn', 'fullname' => 'fab fa-linkedin', 'type' => 'brand'],
            ['name' => 'Twitter / X', 'fullname' => 'fab fa-twitter', 'type' => 'brand'],
            ['name' => 'Personal Website', 'fullname' => 'fas fa-globe', 'type' => 'solid'],
            ['name' => 'GitLab', 'fullname' => 'fab fa-gitlab', 'type' => 'brand'],
            ['name' => 'PHP', 'fullname' => 'fab fa-php', 'type' => 'brand'],
            ['name' => 'Database', 'fullname' => 'fas fa-database', 'type' => 'solid'],
            ['name' => 'Docker', 'fullname' => 'fab fa-docker', 'type' => 'brand'],
            ['name' => 'JavaScript', 'fullname' => 'fab fa-js', 'type' => 'brand'],
            ['name' => 'Cloud', 'fullname' => 'fas fa-cloud', 'type' => 'solid'],
            ['name' => 'Code', 'fullname' => 'fas fa-code', 'type' => 'solid'],
        ];
        $socialRecords = [];
        foreach ($socials as $item) {
            $socialRecords[$item['name']] = SettingsSocial::firstOrCreate(
                ['name' => $item['name']],
                ['fullname' => $item['fullname'], 'type' => $item['type']]
            );
        }

        // 2. Education Levels
        $educations = [
            ['name' => "Bachelor's Degree", 'level' => 'Undergraduate'],
            ['name' => "Master's Degree", 'level' => 'Postgraduate'],
            ['name' => 'Doctor of Philosophy (PhD)', 'level' => 'Postgraduate'],
            ['name' => 'Diploma', 'level' => 'Undergraduate'],
            ['name' => 'Professional Certification', 'level' => 'Certification'],
        ];
        $eduRecords = [];
        foreach ($educations as $item) {
            $eduRecords[$item['name']] = SettingsEducations::firstOrCreate(
                ['name' => $item['name']],
                ['level' => $item['level']]
            );
        }

        // 3. Language Proficiencies
        $languages = [
            'Native / Bilingual',
            'Full Professional',
            'Professional Working',
            'Limited Working',
            'Elementary',
        ];
        $langRecords = [];
        foreach ($languages as $prof) {
            $langRecords[$prof] = SettingsLanguage::firstOrCreate(['proficiency' => $prof]);
        }

        // 4. Project Statuses
        $statuses = [
            'Production Live',
            'Completed',
            'In Progress',
            'Open Source',
            'Archived',
        ];
        $statusRecords = [];
        foreach ($statuses as $st) {
            $statusRecords[$st] = SettingsProject::firstOrCreate(['status' => $st]);
        }

        // 5. Default Resume Templates
        SettingsTemplate::firstOrCreate(
            ['name' => 'Modern Professional'],
            [
                'primary_colour' => 'bg-dark',
                'secondary_colour' => 'bg-light',
                'design' => '<div class="resume-wrapper main-color text-color-primary p-4">
    <div class="row align-items-center mb-4">
        <div class="col-md-3 text-center profile-image"></div>
        <div class="col-md-9">
            <h1 class="profile-name"></h1>
            <h4 class="profile-tagline text-muted"></h4>
            <p><span class="profile-email"></span> | <span class="profile-phone"></span> | <span class="profile-website"></span></p>
        </div>
    </div>
    <div class="resume-body bg-white text-dark p-4 rounded shadow-sm">
        <div class="mb-4">
            <h3 class="border-bottom pb-2">Career Objective</h3>
            <div class="objective-info"></div>
        </div>
        <div class="mb-4">
            <h3 class="border-bottom pb-2">Work Experience</h3>
            <div class="experience-info"></div>
        </div>
        <div class="mb-4">
            <h3 class="border-bottom pb-2">Education</h3>
            <div class="education-info"></div>
        </div>
        <div class="mb-4">
            <h3 class="border-bottom pb-2">Technical Skills</h3>
            <div class="skill-info"></div>
        </div>
        <div class="mb-4">
            <h3 class="border-bottom pb-2">Key Projects</h3>
            <div class="project-info"></div>
        </div>
        <div class="row mb-4">
            <div class="col-md-6">
                <h3 class="border-bottom pb-2">Languages</h3>
                <div class="language-info"></div>
            </div>
            <div class="col-md-6">
                <h3 class="border-bottom pb-2">Interests</h3>
                <div class="interest-info"></div>
            </div>
        </div>
        <div class="mb-2">
            <h3 class="border-bottom pb-2">Connect</h3>
            <div class="social-info"></div>
        </div>
    </div>
</div>',
                'custom_css' => '.resume-wrapper { font-family: "Source Sans Pro", sans-serif; line-height: 1.6; }',
            ]
        );

        SettingsTemplate::firstOrCreate(
            ['name' => 'Minimalist Slate'],
            [
                'primary_colour' => 'bg-secondary',
                'secondary_colour' => 'bg-white',
                'design' => '<div class="resume-wrapper p-4">
    <div class="header text-center mb-4">
        <h1 class="profile-name"></h1>
        <h4 class="profile-tagline text-muted"></h4>
        <p><span class="profile-email"></span> &bull; <span class="profile-phone"></span> &bull; <span class="profile-website"></span></p>
    </div>
    <div class="mb-4"><h4 class="text-uppercase text-secondary">Summary</h4><div class="objective-info"></div></div>
    <div class="mb-4"><h4 class="text-uppercase text-secondary">Experience</h4><div class="experience-info"></div></div>
    <div class="mb-4"><h4 class="text-uppercase text-secondary">Education</h4><div class="education-info"></div></div>
    <div class="mb-4"><h4 class="text-uppercase text-secondary">Skills</h4><div class="skill-info"></div></div>
    <div class="mb-4"><h4 class="text-uppercase text-secondary">Projects</h4><div class="project-info"></div></div>
</div>',
                'custom_css' => '',
            ]
        );

        return [
            'socials' => $socialRecords,
            'educations' => $eduRecords,
            'languages' => $langRecords,
            'statuses' => $statusRecords,
        ];
    }

    /**
     * Seed Demo User, Persona, and all Resume Modules.
     */
    public function seedDemoUserAndResume(array $settings): void
    {
        // 1. Demo User Account
        $user = User::firstOrCreate(
            ['email' => 'demo@resumed.com'],
            [
                'name' => 'Demo User',
                'password' => Hash::make('demo123'),
                'is_admin' => 1,
                'created_at' => Carbon::now(),
            ]
        );

        // Ensure role exists and is assigned
        try {
            $role = Role::firstOrCreate(['name' => 'Superadmin']);
            $permissions = Permission::pluck('id', 'id')->all();
            if (!empty($permissions)) {
                $role->syncPermissions($permissions);
            }
            if (!$user->hasRole('Superadmin')) {
                $user->assignRole([$role->id]);
            }
        } catch (\Throwable $e) {
            // Role table might not exist or already assigned
        }

        // 2. Default PDF Setting for Demo User
        SettingsPDF::firstOrCreate(
            ['name' => 'Standard Executive PDF', 'user_id' => $user->id],
            [
                'margin_top' => 10,
                'margin_bottom' => 10,
                'margin_left' => 10,
                'margin_right' => 10,
                'header' => 0,
                'footer' => 0,
            ]
        );

        // 3. Demo Persona
        $persona = Personal::firstOrCreate(
            ['email' => 'demo.alex@resumed.com'],
            [
                'fullname' => 'Alex Morgan',
                'tagline' => 'Senior Full-Stack & Cloud Architect',
                'phone' => '+1 (555) 019-2834',
                'website' => 'https://alexmorgan.dev',
                'user_id' => $user->id,
                'status' => 1,
            ]
        );

        // Ensure status is active
        $persona->update(['status' => 1, 'user_id' => $user->id]);

        // 4. Career Objective
        Objectives::firstOrCreate(
            ['user_id' => $user->id, 'personal_id' => $persona->id],
            [
                'content' => 'Accomplished Full-Stack Engineer and Cloud Architect with 8+ years of hands-on expertise building high-throughput microservices, scalable web apps, and automated cloud workflows. Dedicated to clean architecture, test automation, and empowering engineering teams.',
                'is_active' => 1,
            ]
        );

        // 5. Work Experiences
        $experiences = [
            [
                'position' => 'Principal Software Engineer',
                'employer' => 'CloudScale Technologies Inc.',
                'start' => '01/01/2022',
                'end' => 'Present',
                'description' => 'Architected and orchestrated the migration of monolithic Laravel services into containerized Docker/Kubernetes microservices processing over 10M daily requests. Automated CI/CD release cycles and cut cloud operational costs by 28%.',
            ],
            [
                'position' => 'Senior Backend Developer',
                'employer' => 'Apex Digital Solutions',
                'start' => '01/06/2019',
                'end' => '31/12/2021',
                'description' => 'Built mission-critical RESTful and GraphQL APIs with Laravel, Redis, and MySQL. Implemented event-driven queues, third-party payment gateways, and optimized complex SQL queries to lower average response times from 350ms to 45ms.',
            ],
            [
                'position' => 'Full-Stack Web Developer',
                'employer' => 'Nexus Media Lab',
                'start' => '15/08/2016',
                'end' => '31/05/2019',
                'description' => 'Developed robust client-facing SaaS web applications utilizing PHP, modern JavaScript, and Bootstrap. Collaborated closely with product designers to ship responsive, accessible, and high-performance digital experiences.',
            ],
        ];
        foreach ($experiences as $exp) {
            Experiences::firstOrCreate(
                ['position' => $exp['position'], 'employer' => $exp['employer'], 'user_id' => $user->id, 'personal_id' => $persona->id],
                $exp
            );
        }

        // 6. Educations
        $eduLevelId = $settings['educations']["Bachelor's Degree"]?->id ?? 1;
        Educations::firstOrCreate(
            ['name' => 'B.S. in Computer Science', 'institution' => 'University of Science and Technology', 'user_id' => $user->id, 'personal_id' => $persona->id],
            [
                'start' => '2012',
                'end' => '2016',
                'level_id' => $eduLevelId,
            ]
        );

        // 7. Technical Skills
        $skills = [
            ['name' => 'PHP & Laravel', 'percentage' => 95, 'icon_id' => $settings['socials']['PHP']?->id ?? null],
            ['name' => 'MySQL & PostgreSQL', 'percentage' => 90, 'icon_id' => $settings['socials']['Database']?->id ?? null],
            ['name' => 'Docker & Kubernetes', 'percentage' => 85, 'icon_id' => $settings['socials']['Docker']?->id ?? null],
            ['name' => 'JavaScript & Vue.js', 'percentage' => 80, 'icon_id' => $settings['socials']['JavaScript']?->id ?? null],
            ['name' => 'Cloud Architecture (AWS/GCP)', 'percentage' => 85, 'icon_id' => $settings['socials']['Cloud']?->id ?? null],
            ['name' => 'REST & GraphQL APIs', 'percentage' => 95, 'icon_id' => $settings['socials']['Code']?->id ?? null],
            ['name' => 'CI/CD & Automated Testing', 'percentage' => 90, 'icon_id' => $settings['socials']['Code']?->id ?? null],
        ];
        foreach ($skills as $skill) {
            Skills::firstOrCreate(
                ['name' => $skill['name'], 'user_id' => $user->id, 'personal_id' => $persona->id],
                $skill
            );
        }

        // 8. Projects
        $liveStatusId = $settings['statuses']['Production Live']?->id ?? 1;
        $openSourceStatusId = $settings['statuses']['Open Source']?->id ?? 1;
        $projects = [
            [
                'name' => 'Resumed Dynamic CV Platform',
                'description' => 'A comprehensive, single-user multi-persona resume and portfolio management platform built on Laravel 12 and AdminLTE.',
                'status_id' => $liveStatusId,
            ],
            [
                'name' => 'HyperQueue Distributed Broker',
                'description' => 'High-throughput async job broker with zero-downtime worker reloads, dead-letter monitoring, and telemetry dashboard.',
                'status_id' => $openSourceStatusId,
            ],
            [
                'name' => 'CloudSentinel Health Monitor',
                'description' => 'Automated synthetic monitoring daemon tracking Core Web Vitals, API latency, and SSL certificate renewals.',
                'status_id' => $liveStatusId,
            ],
        ];
        foreach ($projects as $proj) {
            Projects::firstOrCreate(
                ['name' => $proj['name'], 'user_id' => $user->id, 'personal_id' => $persona->id],
                $proj
            );
        }

        // 9. Languages
        $nativeProfId = $settings['languages']['Native / Bilingual']?->id ?? 1;
        $profWorkId = $settings['languages']['Professional Working']?->id ?? 1;
        $languages = [
            ['name' => 'English', 'proficiency_id' => $nativeProfId],
            ['name' => 'Malay', 'proficiency_id' => $nativeProfId],
            ['name' => 'German', 'proficiency_id' => $profWorkId],
        ];
        foreach ($languages as $lang) {
            Languages::firstOrCreate(
                ['name' => $lang['name'], 'user_id' => $user->id, 'personal_id' => $persona->id],
                $lang
            );
        }

        // 10. Socials
        $githubIconId = $settings['socials']['GitHub']?->id ?? null;
        $linkedinIconId = $settings['socials']['LinkedIn']?->id ?? null;
        $websiteIconId = $settings['socials']['Personal Website']?->id ?? null;
        $socials = [
            ['name' => 'GitHub', 'link' => 'https://github.com/demo-alex-morgan', 'icon_id' => $githubIconId],
            ['name' => 'LinkedIn', 'link' => 'https://linkedin.com/in/demo-alex-morgan', 'icon_id' => $linkedinIconId],
            ['name' => 'Personal Portfolio', 'link' => 'https://alexmorgan.dev', 'icon_id' => $websiteIconId],
        ];
        foreach ($socials as $soc) {
            Socials::firstOrCreate(
                ['name' => $soc['name'], 'user_id' => $user->id, 'personal_id' => $persona->id],
                $soc
            );
        }

        // 11. Interests
        $interests = [
            'Distributed Cloud Systems',
            'Open-Source Software',
            'AI & Autonomous Agents',
            'High-Performance Databases',
        ];
        foreach ($interests as $interest) {
            Interests::firstOrCreate(
                ['name' => $interest, 'user_id' => $user->id, 'personal_id' => $persona->id]
            );
        }
    }
}
