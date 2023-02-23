@extends('adminlte::page')

@section(config('adminlte.title'), 'AdminLTE')

@section('content_header')
    <h1 class="m-0 text-dark">Languages</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <x-adminlte-card title="Languages " theme="default" icon="fas fa-sm fa-language" collapsible>
                <x-adminlte-input name="name" label="Language" fgroup-class="col-md-6 row" label-class="col-md-2 control-label" igroup-class="col-md-10" value="{{ $data->name }}" readonly/>
                <x-adminlte-input name="proficiency" label="Proficiency" fgroup-class="col-md-6 row" label-class="col-md-2 control-label" igroup-class="col-md-10" value="{{ $data->allproficiency->proficiency }}" readonly/>
                <x-adminlte-input name="user" label="User" fgroup-class="col-md-6 row" label-class="col-md-2 control-label" igroup-class="col-md-10" value="{{ $user->name }}" readonly/>
                <x-slot name="footerSlot">
                    <a href="{{ route('languages') }}">
                        <x-adminlte-button class="btn-flat" label="{{ __('adminlte::adminlte.back') }}" theme="default" icon="fas fa-chevron-left"/>
                    </a>
                </x-slot>
            </x-adminlte-card>
        </div>
    </div>
@stop