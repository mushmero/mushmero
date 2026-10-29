@extends('adminlte::page')

@section(config('adminlte.title'), 'AdminLTE')

@section('content_header')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center pb-2 border-bottom">
        <div>
            <h1 class="m-0 text-dark font-weight-bold">Dashboard</h1>
            <small class="text-muted">Welcome to your personal resume and portfolio management panel</small>
        </div>
        <div class="mt-2 mt-md-0 d-flex align-items-center">
            @if(isset($allPersonas) && $allPersonas->count() > 0)
                <div class="mr-2 d-flex align-items-center bg-white border rounded px-2 py-1 shadow-sm">
                    <span class="badge badge-success badge-pill mr-2">●</span>
                    <label for="persona-select-header" class="mb-0 text-xs font-weight-bold mr-1 text-muted">Active:</label>
                    <select id="persona-select-header" class="form-control form-control-sm border-0 font-weight-bold text-dark py-0" style="height: auto; width: auto; cursor: pointer;">
                        @foreach($allPersonas as $persona)
                            <option value="{{ $persona->id }}" {{ ($activePersona && $activePersona->id == $persona->id) ? 'selected' : '' }}>
                                {{ $persona->fullname }} ({{ $persona->tagline ?: 'Persona #'.$persona->id }})
                            </option>
                        @endforeach
                    </select>
                </div>
            @endif
            <a href="{{ url('/') }}" target="_blank" class="btn btn-sm btn-outline-primary shadow-sm">
                <i class="fas fa-external-link-alt mr-1"></i> View Live Site
            </a>
        </div>
    </div>
@stop

@section('content')
    <!-- Active Persona Status Banner & Quick Action Toolbar -->
    <div class="card card-primary card-outline shadow-sm mb-3">
        <div class="card-body p-3">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <div class="d-flex align-items-center mb-1">
                        <span class="badge badge-success px-2 py-1 mr-2">
                            <i class="fas fa-check-circle mr-1"></i> Active on Public Portfolio
                        </span>
                        <small class="text-muted">
                            Persona ID: #{{ $activePersona->id ?? '1' }} &bull; Linked to Landing Page (<code>/</code>)
                        </small>
                    </div>
                    <h4 class="mb-1 text-dark font-weight-bold">
                        {{ $profile['fullname'] }} 
                        <span class="text-primary font-weight-normal">&mdash; {{ $profile['tagline'] ?: 'Professional Portfolio' }}</span>
                    </h4>
                    <p class="text-muted mb-0 small">
                        All public landing page visitors and exported PDF resumes currently render this active persona's bio, credentials, and projects.
                    </p>
                </div>
                <div class="col-lg-4 text-lg-right mt-3 mt-lg-0">
                    <div class="btn-group shadow-sm">
                        <a href="{{ route('experiences.create') }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-plus mr-1"></i> Add Experience
                        </a>
                        <a href="{{ route('projects.create') }}" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-plus mr-1"></i> Add Project
                        </a>
                        <a href="{{ route('settings_template') }}" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-file-pdf mr-1"></i> Export PDF
                        </a>
                    </div>
                </div>
            </div>

            <!-- Quick Persona Switch Pill Tabs -->
            @if(isset($allPersonas) && $allPersonas->count() > 1)
                <div class="mt-3 pt-2 border-top d-flex align-items-center flex-wrap">
                    <small class="text-muted font-weight-bold mr-2">Switch Persona:</small>
                    @foreach($allPersonas as $p)
                        <button type="button" 
                                class="btn btn-xs mr-2 mb-1 btn-switch-persona {{ ($activePersona && $activePersona->id == $p->id) ? 'btn-primary' : 'btn-outline-secondary' }}" 
                                data-id="{{ $p->id }}" 
                                data-name="{{ $p->fullname }}">
                            {{ $p->tagline ?: $p->fullname }}
                        </button>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <!-- 4 High-Signal Executive KPI Cards -->
    <div class="row">
        <!-- KPI 1: Visitors Count -->
        <div class="col-12 col-sm-6 col-xl-3 mb-3">
            <div class="card h-100 shadow-sm border-left-primary mb-0">
                <div class="card-body p-3 d-flex flex-column justify-content-between">
                    <div>
                        <div class="d-flex justify-content-between align-items-center text-muted mb-1">
                            <span class="text-xs font-weight-bold text-uppercase">Total Site Visitors</span>
                            <i class="fas fa-globe text-primary"></i>
                        </div>
                        <div class="d-flex align-items-baseline justify-content-between">
                            <h2 class="font-weight-bold text-dark mb-0">{{ number_format($visitorStats['total'] ?? 0) }}</h2>
                            <span class="badge badge-success font-weight-normal">{{ $visitorStats['countries_count'] ?? 0 }} Countries</span>
                        </div>
                        <small class="text-muted d-block mt-1">Real-time visitor telemetry</small>
                    </div>
                    <div class="pt-2 mt-2 border-top text-right">
                        <a href="{{ route('visitors') }}" class="small text-primary font-weight-bold">
                            View Visitors Log <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- KPI 2: Career Experience -->
        <div class="col-12 col-sm-6 col-xl-3 mb-3">
            <div class="card h-100 shadow-sm border-left-info mb-0">
                <div class="card-body p-3 d-flex flex-column justify-content-between">
                    <div>
                        <div class="d-flex justify-content-between align-items-center text-muted mb-1">
                            <span class="text-xs font-weight-bold text-uppercase">Career Experience</span>
                            <i class="fas fa-briefcase text-info"></i>
                        </div>
                        <div class="d-flex align-items-baseline justify-content-between">
                            <h2 class="font-weight-bold text-dark mb-0">{{ $experience['length'] ?? 0 }} Years</h2>
                            <span class="badge badge-info font-weight-normal">{{ $experience['count'] ?? 0 }} Roles</span>
                        </div>
                        <small class="text-muted d-block mt-1 truncate">{{ $profile['tagline'] ?: 'Professional History' }}</small>
                    </div>
                    <div class="pt-2 mt-2 border-top text-right">
                        <a href="{{ route('experiences') }}" class="small text-info font-weight-bold">
                            Manage Experience <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- KPI 3: Projects Showcase -->
        <div class="col-12 col-sm-6 col-xl-3 mb-3">
            <div class="card h-100 shadow-sm border-left-success mb-0">
                <div class="card-body p-3 d-flex flex-column justify-content-between">
                    <div>
                        <div class="d-flex justify-content-between align-items-center text-muted mb-1">
                            <span class="text-xs font-weight-bold text-uppercase">Featured Projects</span>
                            <i class="fas fa-rocket text-success"></i>
                        </div>
                        <div class="d-flex align-items-baseline justify-content-between">
                            <h2 class="font-weight-bold text-dark mb-0">{{ $project['count'] ?? 0 }} Projects</h2>
                            <span class="badge badge-light border text-muted">{{ $project['completed'] ?? 0 }} Live &bull; {{ $project['in_progress'] ?? 0 }} WIP</span>
                        </div>
                        <small class="text-muted d-block mt-1">Showcased portfolio works</small>
                    </div>
                    <div class="pt-2 mt-2 border-top text-right">
                        <a href="{{ route('projects') }}" class="small text-success font-weight-bold">
                            Manage Projects <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- KPI 4: Profile Readiness -->
        <div class="col-12 col-sm-6 col-xl-3 mb-3">
            <div class="card h-100 shadow-sm border-left-warning mb-0">
                <div class="card-body p-3 d-flex flex-column justify-content-between">
                    <div>
                        <div class="d-flex justify-content-between align-items-center text-muted mb-1">
                            <span class="text-xs font-weight-bold text-uppercase">Profile Readiness</span>
                            <i class="fas fa-check-circle text-warning"></i>
                        </div>
                        <div class="d-flex align-items-baseline justify-content-between">
                            <h2 class="font-weight-bold text-dark mb-0">{{ $readiness['score'] ?? 0 }}%</h2>
                            <span class="badge {{ ($readiness['score'] ?? 0) >= 80 ? 'badge-success' : 'badge-warning' }} font-weight-normal">
                                {{ $readiness['label'] ?? 'Review' }}
                            </span>
                        </div>
                        <div class="progress progress-xs mt-2">
                            <div class="progress-bar {{ ($readiness['score'] ?? 0) >= 80 ? 'bg-success' : 'bg-warning' }}" 
                                 role="progressbar" 
                                 style="width: {{ $readiness['score'] ?? 0 }}%" 
                                 aria-valuenow="{{ $readiness['score'] ?? 0 }}" 
                                 aria-valuemin="0" 
                                 aria-valuemax="100">
                            </div>
                        </div>
                        <small class="text-muted d-block mt-1 text-truncate" title="{{ $readiness['first_missing'] ?? '' }}">
                            {{ $readiness['first_missing'] ?? 'Ready to share' }}
                        </small>
                    </div>
                    <div class="pt-2 mt-2 border-top text-right">
                        <a href="#checklist-card" class="small text-warning font-weight-bold">
                            View Checklist <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Career Module Assets Ribbon -->
    <div class="card shadow-sm mb-3">
        <div class="card-body p-2 px-3">
            <div class="d-flex align-items-center justify-content-between flex-wrap text-xs">
                <span class="font-weight-bold text-dark mr-2"><i class="fas fa-layer-group text-primary mr-1"></i> Career Module Assets:</span>
                <div class="d-flex flex-wrap align-items-center text-muted">
                    <a href="{{ route('experiences') }}" class="text-dark font-weight-bold hover-primary mr-3">
                        <span class="badge badge-light border">{{ $experience['count'] ?? 0 }}</span> Experiences
                    </a>
                    <a href="{{ route('projects') }}" class="text-dark font-weight-bold hover-primary mr-3">
                        <span class="badge badge-light border">{{ $project['count'] ?? 0 }}</span> Projects
                    </a>
                    <a href="{{ route('skills') }}" class="text-dark font-weight-bold hover-primary mr-3">
                        <span class="badge badge-light border">{{ $skill['count'] ?? 0 }}</span> Skills
                    </a>
                    <a href="{{ route('educations') }}" class="text-dark font-weight-bold hover-primary mr-3">
                        <span class="badge badge-light border">{{ $education['count'] ?? 0 }}</span> Educations
                    </a>
                    <a href="{{ route('languages') }}" class="text-dark font-weight-bold hover-primary mr-3">
                        <span class="badge badge-light border">{{ $language['count'] ?? 0 }}</span> Languages
                    </a>
                    <a href="{{ route('socials') }}" class="text-dark font-weight-bold hover-primary mr-3">
                        <span class="badge badge-light border">{{ $social['count'] ?? 0 }}</span> Socials
                    </a>
                    <a href="{{ route('interests') }}" class="text-dark font-weight-bold hover-primary">
                        <span class="badge badge-light border">{{ $interest['count'] ?? 0 }}</span> Interests
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Workspace Grid: 2 Columns (8 cols / 4 cols) -->
    <div class="row">
        <!-- Left Column (8 cols): Visitor Map + Persona Featured Projects -->
        <div class="col-lg-8">
            <!-- Visitor Geographic Telemetry Card -->
            <div class="card card-outline card-info shadow-sm mb-3" id="visitor-map">
                <div class="card-header bg-white py-2">
                    <h3 class="card-title text-sm font-weight-bold">
                        <i class="fas fa-globe-americas mr-1 text-info"></i> Global Visitor Reach (GeoIP Telemetry)
                    </h3>
                    <div class="card-tools">
                        <span class="badge badge-light border">Mapael Visualizer</span>
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body p-3">
                    <!-- Mapael Container -->
                    <div class="visitor_map" style="min-height: 220px;">
                        <div class="worldmap">
                            <div class="map"></div>
                            <div class="plotLegend"></div>
                        </div>
                    </div>

                    <!-- Top Countries Telemetry Grid -->
                    @if(isset($visitorStats['top_countries']) && count($visitorStats['top_countries']) > 0)
                        <div class="row mt-3 pt-2 border-top">
                            @foreach($visitorStats['top_countries'] as $topCountry)
                                <div class="col-6 col-md-3 mb-2">
                                    <div class="p-2 border rounded bg-light">
                                        <small class="text-muted d-block text-truncate">{{ $topCountry->country ?? 'Unknown' }} ({{ $topCountry->countryCode ?? '' }})</small>
                                        <strong class="text-dark">{{ number_format($topCountry->count) }}</strong>
                                        <small class="text-success font-weight-bold ml-1">
                                            ({{ ($visitorStats['total'] ?? 0) > 0 ? round(($topCountry->count / $visitorStats['total']) * 100, 1) : 0 }}%)
                                        </small>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <!-- Featured Projects Table -->
            <div class="card card-outline card-primary shadow-sm mb-3">
                <div class="card-header bg-white py-2">
                    <h3 class="card-title text-sm font-weight-bold">
                        <i class="fas fa-rocket mr-1 text-primary"></i> Showcase Projects
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('projects.create') }}" class="btn btn-xs btn-primary shadow-sm">
                            <i class="fas fa-plus mr-1"></i> New Project
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped mb-0 text-sm">
                            <thead class="thead-light">
                                <tr>
                                    <th class="py-2">Project Name</th>
                                    <th class="py-2">Status</th>
                                    <th class="py-2 text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($project['items']) && $project['items']->count() > 0)
                                    @foreach($project['items']->take(5) as $proj)
                                        <tr>
                                            <td class="py-2 align-middle font-weight-bold text-dark">
                                                {{ $proj->name }}
                                                @if(!empty($proj->description))
                                                    <small class="text-muted d-block font-weight-normal text-truncate" style="max-width: 400px;">
                                                        {{ strip_tags($proj->description) }}
                                                    </small>
                                                @endif
                                            </td>
                                            <td class="py-2 align-middle">
                                                <span class="badge {{ stripos($proj->allstatus?->status, 'complete') !== false ? 'badge-success' : 'badge-info' }} px-2 py-1">
                                                    {{ $proj->allstatus?->status ?? 'Active' }}
                                                </span>
                                            </td>
                                            <td class="py-2 align-middle text-right">
                                                <a href="{{ url('projects/'.$proj->id.'/edit') }}" class="btn btn-xs btn-outline-secondary">
                                                    <i class="fas fa-edit mr-1"></i> Edit
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="3" class="text-center py-4 text-muted">
                                            <i class="fas fa-archive fa-2x mb-2 text-muted"></i>
                                            <p class="mb-1">No projects added yet.</p>
                                            <a href="{{ route('projects.create') }}" class="btn btn-xs btn-primary">Add your first project</a>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column (4 cols): Persona Card + Activity Audit Log + Completeness -->
        <div class="col-lg-4">
            <!-- Active Persona Profile Card -->
            <div class="card card-widget widget-user shadow-sm mb-3 overflow-hidden">
                <div class="widget-user-header bg-primary text-white" style="background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);">
                    <h3 class="widget-user-username font-weight-bold">{{ $profile['fullname'] }}</h3>
                    <h5 class="widget-user-desc text-light opacity-90">{{ $profile['tagline'] ?: 'Professional Persona' }}</h5>
                </div>
                <div class="widget-user-image">
                    @if(!empty($profile['profile_image']))
                        <img class="img-circle elevation-2" src="{{ $profile['profile_image'] }}" alt="User Avatar" style="width: 90px; height: 90px; object-fit: cover;">
                    @else
                        <div class="img-circle elevation-2 bg-light d-flex align-items-center justify-content-center text-primary font-weight-bold" style="width: 90px; height: 90px; font-size: 28px; margin: 0 auto; border: 3px solid #fff;">
                            {{ substr($profile['fullname'] ?: 'A', 0, 1) }}
                        </div>
                    @endif
                </div>
                <div class="card-body pt-5 px-3 pb-3">
                    <div class="text-center text-muted small mb-3">
                        @if(!empty($profile['email']))
                            <div class="mb-1"><i class="fas fa-envelope mr-1 text-primary"></i> {{ $profile['email'] }}</div>
                        @endif
                        @if(!empty($profile['phone']))
                            <div><i class="fas fa-phone mr-1 text-primary"></i> {{ $profile['phone'] }}</div>
                        @endif
                    </div>

                    @if(isset($skill['items']) && $skill['items']->count() > 0)
                        <div class="border-top pt-2">
                            <small class="font-weight-bold text-dark d-block mb-1">Top Skills &amp; Proficiencies:</small>
                            <div class="d-flex flex-wrap">
                                @foreach($skill['items']->take(6) as $s)
                                    <span class="badge badge-light border text-dark mr-1 mb-1 p-1 px-2 font-weight-normal">
                                        {{ $s->name }} <strong class="text-primary">{{ $s->percentage }}%</strong>
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="border-top pt-2 mt-2 text-center">
                        <a href="{{ route('personal') }}" class="btn btn-xs btn-outline-primary btn-block">
                            <i class="fas fa-user-edit mr-1"></i> Edit Persona Profiles
                        </a>
                    </div>
                </div>
            </div>

            <!-- Recent System Activity Audit Log -->
            <div class="card card-outline card-warning shadow-sm mb-3">
                <div class="card-header bg-white py-2">
                    <h3 class="card-title text-sm font-weight-bold">
                        <i class="fas fa-history mr-1 text-warning"></i> Recent System Activity
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('user_access') }}" class="small text-muted font-weight-bold">All Logs &rarr;</a>
                    </div>
                </div>
                <div class="card-body p-3">
                    @if(isset($recentActivities) && count($recentActivities) > 0)
                        <div class="timeline timeline-inverse mb-0">
                            @foreach($recentActivities as $act)
                                <div class="mb-2 pb-2 border-bottom">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <span class="badge badge-light border text-muted px-1.5">{{ $act['log_name'] }}</span>
                                        <small class="text-muted">{{ $act['created_at'] }}</small>
                                    </div>
                                    <div class="small text-dark font-weight-normal mt-1">
                                        {{ $act['description'] }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted small text-center my-2">No recent audit activity.</p>
                    @endif
                </div>
            </div>

            <!-- Resume Readiness Checklist -->
            <div class="card shadow-sm mb-3" id="checklist-card">
                <div class="card-header bg-white py-2">
                    <h3 class="card-title text-sm font-weight-bold">
                        <i class="fas fa-clipboard-check mr-1 text-success"></i> Resume Readiness Checklist
                    </h3>
                </div>
                <div class="card-body p-3">
                    <ul class="list-unstyled mb-0 text-sm">
                        <li class="mb-2 d-flex align-items-center">
                            @if(!empty($profile['profile_image']))
                                <i class="fas fa-check-circle text-success mr-2"></i>
                                <span class="text-dark">Profile photo uploaded</span>
                            @else
                                <i class="far fa-circle text-warning mr-2"></i>
                                <a href="{{ route('personal') }}" class="text-muted">Profile photo missing</a>
                            @endif
                        </li>
                        <li class="mb-2 d-flex align-items-center">
                            @if(!empty($profile['tagline']))
                                <i class="fas fa-check-circle text-success mr-2"></i>
                                <span class="text-dark">Professional tagline active</span>
                            @else
                                <i class="far fa-circle text-warning mr-2"></i>
                                <a href="{{ route('personal') }}" class="text-muted">Add professional tagline</a>
                            @endif
                        </li>
                        <li class="mb-2 d-flex align-items-center">
                            @if(($experience['count'] ?? 0) >= 2)
                                <i class="fas fa-check-circle text-success mr-2"></i>
                                <span class="text-dark">Work experience documented ({{ $experience['count'] }})</span>
                            @else
                                <i class="far fa-circle text-warning mr-2"></i>
                                <a href="{{ route('experiences.create') }}" class="text-muted">Add at least 2 roles</a>
                            @endif
                        </li>
                        <li class="mb-2 d-flex align-items-center">
                            @if(($skill['count'] ?? 0) >= 3)
                                <i class="fas fa-check-circle text-success mr-2"></i>
                                <span class="text-dark">Skills rated &amp; categorized ({{ $skill['count'] }})</span>
                            @else
                                <i class="far fa-circle text-warning mr-2"></i>
                                <a href="{{ route('skills.create') }}" class="text-muted">Add 3+ skills</a>
                            @endif
                        </li>
                        <li class="mb-2 d-flex align-items-center">
                            @if(($project['count'] ?? 0) >= 1)
                                <i class="fas fa-check-circle text-success mr-2"></i>
                                <span class="text-dark">Showcase projects attached ({{ $project['count'] }})</span>
                            @else
                                <i class="far fa-circle text-warning mr-2"></i>
                                <a href="{{ route('projects.create') }}" class="text-muted">Showcase at least 1 project</a>
                            @endif
                        </li>
                        <li class="d-flex align-items-center">
                            @if(($social['count'] ?? 0) >= 1)
                                <i class="fas fa-check-circle text-success mr-2"></i>
                                <span class="text-dark">Social profiles linked</span>
                            @else
                                <i class="far fa-circle text-warning mr-2"></i>
                                <a href="{{ route('socials.create') }}" class="text-muted">Link social profiles (LinkedIn)</a>
                            @endif
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Quote of the Day Strip -->
    @if(!empty($inspiration))
        <div class="card shadow-sm mb-0">
            <div class="card-body p-2 px-3 d-flex flex-column flex-sm-row justify-content-between align-items-center text-xs text-muted">
                <div class="d-flex align-items-center">
                    <span class="mr-2">💡</span>
                    <blockquote class="blockquote mb-0 text-xs">
                        <span class="font-italic text-dark">{{ explode(" - ", $inspiration)[0] }}</span>
                        @if(isset(explode(" - ", $inspiration)[1]))
                            <footer class="blockquote-footer d-inline font-weight-bold text-dark ml-1">{{ explode(" - ", $inspiration)[1] }}</footer>
                        @endif
                    </blockquote>
                </div>
                <div class="mt-1 mt-sm-0 text-muted">
                    Resumed Platform &bull; Single-User Edition
                </div>
            </div>
        </div>
    @endif
@stop

@section('js')
    <script>
        $(document).ready(function() {
            // Function to perform AJAX active persona switch
            function executePersonaSwitch(personaId, personaName) {
                Swal.fire({
                    title: 'Switch Active Persona?',
                    text: 'Activating "' + personaName + '" will update the live public portfolio (/) and resume exports.',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#007bff',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, activate',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Switching persona...',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        $.ajax({
                            url: "{{ url('personal/switch') }}/" + personaId,
                            type: "POST",
                            data: {
                                _token: "{{ csrf_token() }}",
                                id: personaId
                            },
                            success: function(response) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Persona Activated',
                                    text: response.message || 'Active persona updated successfully!',
                                    timer: 1500,
                                    showConfirmButton: false
                                }).then(() => {
                                    window.location.reload();
                                });
                            },
                            error: function(xhr) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Switch Failed',
                                    text: (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'Unable to switch persona.'
                                });
                            }
                        });
                    } else {
                        // Revert header selector if user cancelled
                        location.reload();
                    }
                });
            }

            // Quick persona switch from header dropdown
            $('#persona-select-header').on('change', function() {
                var selectedId = $(this).val();
                var selectedName = $(this).find('option:selected').text().trim();
                executePersonaSwitch(selectedId, selectedName);
            });

            // Quick persona switch from pill buttons
            $('.btn-switch-persona').on('click', function() {
                var selectedId = $(this).data('id');
                var selectedName = $(this).data('name');
                executePersonaSwitch(selectedId, selectedName);
            });
        });
    </script>
@stop
