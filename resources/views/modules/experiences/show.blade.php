@extends('adminlte::page')

@section(config('adminlte.title'), 'AdminLTE')

@section('content_header')
    <h1 class="m-0 text-dark">Experiences</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <x-adminlte-card title="Experience " theme="default" icon="fas fa-sm fa-briefcase" collapsible>
                <x-adminlte-input name="position" label="Position" fgroup-class="col-md-6 row" label-class="col-md-2 control-label" igroup-class="col-md-10" value="{{ $data->position }}" readonly/>
                <x-adminlte-input name="employer" label="Employer" fgroup-class="col-md-6 row" label-class="col-md-2 control-label" igroup-class="col-md-10" value="{{ $data->employer }}" readonly/>
                <x-adminlte-input name="start" label="From" fgroup-class="col-md-6 row" label-class="col-md-2 control-label" igroup-class="col-md-10" value="{{ $data->start }}" readonly/>
                <x-adminlte-input name="end" label="To" fgroup-class="col-md-6 row" label-class="col-md-2 control-label" igroup-class="col-md-10" value="{{ $data->end }}" readonly/>
                <x-adminlte-input name="user" label="User" fgroup-class="col-md-6 row" label-class="col-md-2 control-label" igroup-class="col-md-10" value="{{ $user->name }}" readonly/>              
                <x-adminlte-text-editor id="description" name="description" label="Desription" label-class="col-md-2 control-label" igroup-size="sm" :config="$configTE" disabled>
                    {{ html_entity_decode($data->description) }}
                </x-adminlte-text-editor>
                <x-slot name="footerSlot">
                    <a href="{{ route('experiences') }}">
                        <x-adminlte-button class="btn-flat" label="{{ __('adminlte::adminlte.back') }}" theme="default" icon="fas fa-chevron-left"/>
                    </a>
                </x-slot>
            </x-adminlte-card>
        </div>
    </div>
@stop