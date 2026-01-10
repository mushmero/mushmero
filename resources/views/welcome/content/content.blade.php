@extends('welcome.partials.layout', ['author' => $data['author'], 'description' => $data['description']])

@section('content')

    <div id="main-background">
        <div class="flash-message">
            @include('flash::message')
        </div>
        <img src="{!! url($data['profile-image']) !!}" alt="{{ $data['name'] }}" />
        <div class="name-desc">
            <h1>{{ $data['name'] }}</h1>
            <h2>{{ $data['position'] }}</h2>
        </div>

        <div class="about">
            <div id="about-header">
                <h1>Know {{ $data['name'] }} more</h1>
            </div>
            <div id="about-me">
                {{-- <figure>
                    <img src="https://picsum.photos/id/373/4896/3264.jpg" alt="" />
                    <figcaption>
                        Reach me <br>
                        <a href="mailto:{{ $data['email'] }}" target="_blank"><i class="fas fa-fw fa-at"></i> {{ $data['email'] }}</a>
                        <a href="tel:{{ $data['phone'] }}" target="_blank"><i class="fas fa-fw fa-phone"></i> tel:{{ $data['phone'] }}</a>
                    </figcaption>
                </figure>
                <figure>
                    <img src="https://picsum.photos/id/389/4562/3042.jpg" alt="" />
                    <figcaption>
                        I study <br>
                        @foreach($data['educations'] as $education)
                            {{ $education->name.' '.$education->institution.' ('.$education->start.'-'.$education->end.')' }}
                        @endforeach
                    </figcaption>
                </figure>
                <figure>
                    <img src="https://picsum.photos/id/1060/536/354.jpg" alt="" />
                    <figcaption>
                        I speak and listen to <br>
                        @foreach ($data['languages'] as $language)
                            {{ $language->name.' ('.$language->allproficiency->proficiency.')' }} <br>
                        @endforeach
                    </figcaption>
                </figure>
                <figure>
                    <img src="https://picsum.photos/id/668/4133/2745.jpg" alt="" />
                    <figcaption>
                        Visit me 
                        <a href="{{ $data['website'] }}" target="_blank">{{ $data['website'] }}</a>
                    </figcaption>
                </figure> --}}
            </div>

        </div>
    </div>

    <div id="experiences">
        <div id="experiences-header">
            <h1>Who I have been worked for?</h1>
        </div>
        <div id="experiences-me">
            @foreach ($data['experiences'] as $experience)
                <figure>
                    <div>{{ $experience->employer }} ({{ $experience->position }})</div>
                    <figcaption>
                        <center><i class="fas fa-fw fa-calendar-day"></i> {{ $experience->start }} - {{ $experience->end }} </center>
                        <br>
                        {!! $experience->description !!}
                    </figcaption>
                </figure>
            @endforeach
        </div>
    </div>

    <div id="skills">
        <div id="skills-header">
            <h1>Skills forte</h1>
        </div>
        <div id="skills-me">
            @foreach ($data['skills'] as $skill)
                <skill-block>
                    <i class="{{ $skill->allicons->fullname }} fa-8x"></i> <span>{{ $skill->name }}</span>
                </skill-block>                
            @endforeach
        </div>
    </div>

    <div id="projects">
        <div id="projects-header">
            <h1>Hobbies & Projects</h1>
            <h3>Leisure time bobbies & projects</h3>
        </div>
        <div id="projects-me">
            <figure>
                <figcaption>
                    @foreach ($data['projects'] as $project)
                        <div class="item">
                            <h4>{{ $project['name'] }}</h4>
                            @if (!empty($project['description']))
                                {!! $project['description'] !!}
                            @endif
                        </div>       
                    @endforeach
                </figcaption>
            </figure>  
        </div>
    </div>
@endsection

@section('footer')
    @include('welcome.partials.footer')
@endsection