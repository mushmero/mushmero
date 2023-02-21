@extends('adminlte::page')

@section(config('adminlte.title'), 'AdminLTE')

@section('content_header')
    <h1 class="m-0 text-dark">Projects</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <x-adminlte-card title="Projects " theme="default" icon="fas fa-sm fa-archive" collapsible>
                <x-adminlte-input name="name" label="Project Name" fgroup-class="col-md-6 row" label-class="col-md-2 control-label" igroup-class="col-md-10" value="{{ $data->name }}" readonly/>
                <x-adminlte-input name="status" label="Status" fgroup-class="col-md-6 row" label-class="col-md-2 control-label" igroup-class="col-md-10" value="{{ $data->allstatus->status }}" readonly/>
                <x-adminlte-input name="user" label="User" fgroup-class="col-md-6 row" label-class="col-md-2 control-label" igroup-class="col-md-10" value="{{ $user->name }}" readonly/>              
                <x-adminlte-text-editor id="description" name="description" label="Desription" label-class="col-md-2 control-label" igroup-size="sm" :config="$configTE" disabled>
                    {{ html_entity_decode($data->description) }}
                </x-adminlte-text-editor>
                <x-slot name="footerSlot">
                    <a href="{{ route('projects') }}">
                        <x-adminlte-button class="btn-flat" label="{{ __('adminlte::adminlte.back') }}" theme="default" icon="fas fa-chevron-left"/>
                    </a>
                </x-slot>
            </x-adminlte-card>
        </div>
    </div>
@stop