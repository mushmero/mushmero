@extends('welcome.partials.layout', ['author' => $data['author'], 'description' => $data['description']])

@section('content')
    <!-- Demo Login Bar -->
    @if (config('app.is_demo'))
        <div class="demo-login-widget">
            <a href="{{ route('login') }}" class="btn-tech btn-tech-emerald btn-tech-sm">
                <i class="fas fa-sign-in-alt mr-1"></i> Demo Login
            </a>
        </div>
    @endif

    <!-- Modern Sticky Tech Navbar -->
    <header class="tech-navbar">
        <div class="tech-nav-container">
            <a href="#about" class="tech-brand">
                <span class="tech-brand-icon">&lt;/&gt;</span>
                <span class="tech-brand-text">{{ Str::upper(substr($data['name'], 0, 2)) }}.DEV</span>
            </a>

            <nav class="tech-nav-menu" id="techNavMenu">
                <a href="#about" class="nav-link-tech active">About</a>
                <a href="#experience" class="nav-link-tech">Timeline</a>
                <a href="#skills" class="nav-link-tech">Skills</a>
                @if(($data['educations'] && $data['educations']->count() > 0) || ($data['languages'] && $data['languages']->count() > 0))
                    <a href="#academics" class="nav-link-tech">Academics</a>
                @endif
                @if(!empty($data['projects']) && count($data['projects']) > 0)
                    <a href="#projects" class="nav-link-tech">Projects</a>
                @endif
                <a href="#contact" class="nav-link-tech">Contact</a>
            </nav>

            <div class="tech-nav-actions">
                @if (!empty($data['email']))
                    <a href="mailto:{{ $data['email'] }}" class="btn-tech btn-tech-emerald btn-tech-sm">
                        <i class="fas fa-paper-plane mr-1"></i> Let's Talk
                    </a>
                @endif
                <button class="tech-mobile-toggle" id="techMobileToggle" aria-label="Toggle menu">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </div>
    </header>

    <!-- Flash message container -->
    <div class="tech-flash-container">
        @include('flash::message')
    </div>

    <!-- HERO SECTION -->
    <section id="about" class="tech-hero-section">
        <div class="tech-container">
            <div class="hero-grid">
                <!-- Left: Profile & Description -->
                <div class="hero-content">
                    @if (config('app.show_status_indicator', true))
                        <div class="hero-status-pill {{ config('app.available_for_hire', true) ? '' : 'hero-status-neutral' }}">
                            <span class="{{ config('app.available_for_hire', true) ? 'status-pulse-dot' : 'status-neutral-dot' }}"></span>
                            <span class="font-mono text-xs">
                                @if (config('app.available_for_hire', true))
                                    ONLINE // AVAILABLE FOR HIRE
                                @else
                                    SYSTEM ONLINE // VERIFIED PROFILE
                                @endif
                            </span>
                        </div>
                    @endif

                    <div class="hero-title-group">
                        <div class="font-mono text-emerald-400 text-sm mb-1">// FULL-STACK SOFTWARE ENGINEER</div>
                        <h1 class="hero-name">{{ $data['name'] }}</h1>
                        <h2 class="hero-position">{{ $data['position'] }}</h2>
                    </div>

                    <p class="hero-bio">
                        Engineering resilient backends, distributed systems, and modern, performant web applications with a focus on code quality, scalability, and seamless user experience.
                    </p>

                    <!-- Contact & Metadata Badges -->
                    <div class="hero-meta-badges">
                        @if (!empty($data['email']))
                            <div class="meta-badge-item" title="Click to copy email" onclick="copyToClipboard('{{ $data['email'] }}', 'Email copied to clipboard!')">
                                <i class="fas fa-envelope text-emerald-400"></i>
                                <span class="badge-text">{{ $data['email'] }}</span>
                                <i class="fas fa-copy copy-icon"></i>
                            </div>
                        @endif

                        @if (!empty($data['phone']))
                            <div class="meta-badge-item" title="Click to copy phone" onclick="copyToClipboard('{{ $data['phone'] }}', 'Phone number copied!')">
                                <i class="fas fa-phone text-cyan-400"></i>
                                <span class="badge-text">{{ $data['phone'] }}</span>
                                <i class="fas fa-copy copy-icon"></i>
                            </div>
                        @endif

                        @if (!empty($data['website']))
                            <a href="{{ $data['website'] }}" target="_blank" class="meta-badge-item">
                                <i class="fas fa-globe text-indigo-400"></i>
                                <span class="badge-text">{{ preg_replace('#^https?://#', '', $data['website']) }}</span>
                                <i class="fas fa-external-link-alt copy-icon"></i>
                            </a>
                        @endif
                    </div>

                    <!-- Quick Action CTAs -->
                    <div class="hero-action-row">
                        <a href="#experience" class="btn-tech btn-tech-emerald">
                            <i class="fas fa-route mr-2"></i> View Career Timeline
                        </a>
                        <a href="#contact" class="btn-tech btn-tech-outline">
                            <i class="fas fa-envelope mr-2"></i> Get In Touch
                        </a>
                    </div>
                </div>

                <!-- Right: Profile Avatar Frame -->
                <div class="hero-avatar-wrapper">
                    <div class="tech-avatar-card">
                        <div class="avatar-glow"></div>
                        <div class="avatar-frame">
                            <img src="{!! url($data['profile-image']) !!}" alt="{{ $data['name'] }}" class="avatar-img" />
                        </div>
                        <div class="avatar-terminal-badge font-mono">
                            <span class="badge-dot"></span> {{ Str::slug($data['name'], '-') }}.profile
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION 01: CAREER STORY TIMELINE -->
    <section id="experience" class="tech-section">
        <div class="tech-container">
            <div class="section-header">
                <div class="section-tag font-mono">// 01. CAREER_STORY</div>
                <h2 class="section-title">Professional Experience & Milestones</h2>
                <p class="section-desc">An unbroken chronological timeline of engineering roles, architectural contributions, and production achievements.</p>
            </div>

            <div class="career-timeline wide-timeline">
                <div class="timeline-spine"></div>

                @forelse ($data['experiences'] as $index => $experience)
                    @php
                        $formattedStart = $experience->start;
                        $formattedEnd = $experience->end;

                        try {
                            if (!empty($experience->start) && preg_match('#^\d{2}/\d{2}/\d{4}$#', $experience->start)) {
                                $formattedStart = \Carbon\Carbon::createFromFormat('d/m/Y', $experience->start)->format('M Y');
                            }
                        } catch (\Throwable $e) {}

                        if (empty($experience->end) || strtolower(trim($experience->end)) === 'current') {
                            $formattedEnd = 'Present';
                        } else {
                            try {
                                if (preg_match('#^\d{2}/\d{2}/\d{4}$#', $experience->end)) {
                                    $formattedEnd = \Carbon\Carbon::createFromFormat('d/m/Y', $experience->end)->format('M Y');
                                }
                            } catch (\Throwable $e) {}
                        }
                    @endphp
                    <div class="timeline-item timeline-item-wide">
                        <!-- Milestone Node on the Spine -->
                        <div class="timeline-marker">
                            <span class="marker-dot"></span>
                        </div>

                        <!-- Content Card with Expanded Width -->
                        <div class="timeline-card">
                            <div class="timeline-card-header">
                                <div class="timeline-role-group">
                                    <div class="timeline-company-badge font-mono">
                                        <i class="fas fa-building text-cyan-400 mr-2"></i>
                                        <span>{{ $experience->employer }}</span>
                                    </div>
                                    <h3 class="timeline-role">{{ $experience->position }}</h3>
                                </div>
                                <div class="timeline-date-chip font-mono" title="{{ $experience->start }} - {{ $experience->end }}">
                                    <i class="fas fa-calendar-day mr-2 text-emerald-400"></i>
                                    <span>{{ $formattedStart }} &ndash; {{ $formattedEnd }}</span>
                                </div>
                            </div>

                            <div class="timeline-body">
                                {!! $experience->description !!}
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="tech-empty-card font-mono text-center">
                        <i class="fas fa-terminal text-2xl text-slate-500 mb-2"></i>
                        <p class="text-slate-400">No experience records found in current profile.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- SECTION 02: SKILLS & TECH STACK -->
    <section id="skills" class="tech-section tech-section-alt">
        <div class="tech-container">
            <div class="section-header">
                <div class="section-tag font-mono">// 02. SYSTEM_CAPABILITIES</div>
                <h2 class="section-title">Core Tech Stack & Engineering Forte</h2>
                <p class="section-desc">Tools, languages, frameworks, and architectural proficiencies utilized across projects.</p>
            </div>

            <div class="skills-grid">
                @forelse ($data['skills'] as $skill)
                    <div class="skill-card">
                        <div class="skill-icon-wrapper">
                            <i class="{{ $skill->allicons->fullname ?? 'fas fa-code' }}"></i>
                        </div>
                        <span class="skill-name">{{ $skill->name }}</span>
                    </div>
                @empty
                    <div class="tech-empty-card font-mono text-center col-span-full">
                        <p class="text-slate-400">No skills listed yet.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- SECTION 03: EDUCATION & LANGUAGES -->
    @if(($data['educations'] && $data['educations']->count() > 0) || ($data['languages'] && $data['languages']->count() > 0))
    <section id="academics" class="tech-section">
        <div class="tech-container">
            <div class="section-header">
                <div class="section-tag font-mono">// 03. ACADEMICS_&_COMMUNICATION</div>
                <h2 class="section-title">Education & Language Proficiencies</h2>
                <p class="section-desc">Academic background and verbal/written communication fluencies.</p>
            </div>

            <div class="academics-grid">
                <!-- Education Column -->
                <div class="academics-col">
                    <div class="academics-col-header font-mono">
                        <i class="fas fa-graduation-cap text-emerald-400 mr-2"></i> EDUCATION
                    </div>
                    <div class="space-y-4">
                        @foreach ($data['educations'] as $education)
                            <div class="academic-card">
                                <div class="academic-header">
                                    <h4 class="academic-title">{{ $education->name }}</h4>
                                    <span class="academic-period font-mono">{{ $education->start }} - {{ $education->end }}</span>
                                </div>
                                <div class="academic-institution">
                                    <i class="fas fa-university text-slate-400 mr-1.5"></i> {{ $education->institution }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Languages Column -->
                <div class="academics-col">
                    <div class="academics-col-header font-mono">
                        <i class="fas fa-comments text-cyan-400 mr-2"></i> LANGUAGES
                    </div>
                    <div class="space-y-4">
                        @foreach ($data['languages'] as $language)
                            <div class="language-card">
                                <div class="language-info">
                                    <span class="language-name font-semibold">{{ $language->name }}</span>
                                    <span class="language-level font-mono text-emerald-400">
                                        {{ $language->allproficiency->proficiency ?? 'Fluent' }}
                                    </span>
                                </div>
                                <div class="tech-meter-track">
                                    <div class="tech-meter-bar" style="width: 90%;"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif

    <!-- SECTION 04: PROJECTS & HOBBIES -->
    @if (!empty($data['projects']) && count($data['projects']) > 0)
    <section id="projects" class="tech-section tech-section-alt">
        <div class="tech-container">
            <div class="section-header">
                <div class="section-tag font-mono">// 04. LABS_&_PROJECTS</div>
                <h2 class="section-title">Featured Projects & Practical Work</h2>
                <p class="section-desc">Selected builds, applications, open-source initiatives, and leisure pursuits.</p>
            </div>

            <div class="projects-grid">
                @foreach ($data['projects'] as $project)
                    <div class="project-card">
                        <div class="project-card-header">
                            <div class="project-icon">
                                <i class="fas fa-code-branch text-emerald-400"></i>
                            </div>
                            <h3 class="project-title">{{ $project['name'] }}</h3>
                        </div>
                        @if (!empty($project['description']))
                            <div class="project-desc">
                                {!! $project['description'] !!}
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif
@endsection

@section('footer')
    @include('welcome.partials.footer')
@endsection
