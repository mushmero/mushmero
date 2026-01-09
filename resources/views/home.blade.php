@extends('adminlte::page')

@section(config('adminlte.title'), 'AdminLTE')

@section('content_header')
    <h1 class="m-0 text-dark">Dashboard</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="row">
                <div class="col-md-3">
                    <x-adminlte-small-box title="{{ $social['count'] }}" text="Socials" icon="fas fa-fw fa-user-friends text-dark" theme="gray" url="{{ route('socials') }}" url-text="Socials list" id="sbUpdatable"/>
                    <x-adminlte-small-box title="{{ $education['count'] }}" text="Educations" icon="fas fa-fw fa-university text-dark" theme="gray" url="{{ route('educations') }}" url-text="Educations list" id="sbUpdatable"/>
                </div>
                <div class="col-md-3">
                    <x-adminlte-small-box title="{{ $language['count'] }}" text="Languages" icon="fas fa-fw fa-language text-dark" theme="gray" url="{{ route('languages') }}" url-text="Languages list" id="sbUpdatable"/>
                    <x-adminlte-small-box title="{{ $interest['count'] }}" text="Interests" icon="fas fa-fw fa-thumbs-up text-dark" theme="gray" url="{{ route('interests') }}" url-text="Interests list" id="sbUpdatable"/>
                </div>
                <div class="col-md-3">
                    <x-adminlte-small-box title="{{ $objective['count'] }}" text="Objectives" icon="fas fa-fw fa-bullseye text-dark" theme="gray" url="{{ route('objectives') }}" url-text="Objectives list" id="sbUpdatable"/>
                    <x-adminlte-small-box title="{{ $experience['count'] }}" text="Experiences" icon="fas fa-fw fa-briefcase text-dark" theme="gray" url="{{ route('experiences') }}" url-text="Experiences list" id="sbUpdatable"/>
                </div>
                <div class="col-md-3">
                    <x-adminlte-small-box title="{{ $project['count'] }}" text="Projects" icon="fas fa-fw fa-archive text-dark" theme="gray" url="{{ route('projects') }}" url-text="Projects list" id="sbUpdatable"/>
                    <x-adminlte-small-box title="{{ $skill['count'] }}" text="Skills" icon="fas fa-fw fa-rocket text-dark" theme="gray" url="{{ route('skills') }}" url-text="Skills list" id="sbUpdatable"/>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <x-adminlte-callout theme="info" title="Wisdom words" class="dashboard-quote">
                        <blockquote>
                            {{ explode(" - ", $inspiration)[0] }}
                            <cite>
                                {{ explode(" - ", $inspiration)[1] }}
                            </cite>
                        </blockquote>
                    </x-adminlte-callout>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="row">
                <div class="col-md-12">
                    <x-adminlte-profile-widget class="elevation-4" name="{{ $profile['fullname'] }}" desc="{{ $profile['tagline'] }}"
                        img="{{ $profile['profile_image'] }}" cover="{{ asset('assets/images/resumed-cover.png') }}"
                        header-class="text-white text-right" footer-class='bg-gradient-dark'>
                        <x-adminlte-profile-row-item title="{{ $profile['phone'] }}&nbsp;|&nbsp;{{ $profile['email'] }} " class="text-center"/>
                        <x-adminlte-profile-row-item title="{{ $experience['length'] }} year(s) of experience(s) with" class="text-center border-bottom border-secondary"/>
                        @for ($i = 0; $i < $skill['count']; $i++)
                            <x-adminlte-profile-col-item title="{{ $skill['name'][$i] }}" icon="fas fa-fw fa-code text-blue" size=3/>                    
                        @endfor
                    </x-adminlte-profile-widget>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <x-adminlte-card title="Visitor Map" theme="gray" icon="fas fa-lg fa-moon" collapsible>
            <div class="container visitor_map">
                <div class="worldmap">
                    <div class="map"></div>
                    <div class="plotLegend"></div>
                </div>
            </div>
                <x-slot name="toolsSlot"></x-slot>
                <x-slot name="footerSlot"></x-slot>
            </x-adminlte-card>
        </div>
    </div>
@stop
