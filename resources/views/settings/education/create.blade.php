@extends('adminlte::page')

@section(config('adminlte.title'), 'AdminLTE')

@section('content_header')
    <h1 class="m-0 text-dark">Education Settings</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            @include('flash::message')
            <form class="form" action="{{ route('settings_education.store') }}" method="post">
                @csrf
                <x-adminlte-card title="Create Icon" theme="default" icon="fas fa-sm fa-wrench" collapsible>
                    <x-adminlte-input name="name" label="Name" fgroup-class="col-md-6 row" label-class="col-md-2 control-label" igroup-class="col-md-10" placeholder="eg: Bachelor Degree" required enable-old-support/>
                    <x-adminlte-input name="level" label="Level" fgroup-class="col-md-6 row" label-class="col-md-2 control-label" igroup-class="col-md-10" placeholder="eg: Tertiary" required enable-old-support/>
                    <x-slot name="footerSlot">
                        <a href="{{ route('settings_education') }}">
                            <x-adminlte-button class="btn-flat" label="{{ __('adminlte::adminlte.back') }}" theme="default" icon="fas fa-chevron-left"/>
                        </a>
                        <x-adminlte-button class="btn-flat" type="submit" label="Submit" theme="success" icon="fas fa-lg fa-save"/>
                    </x-slot>
                </x-adminlte-card>
            </form>
        </div>
    </div>
@stop