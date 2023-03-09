@extends('adminlte::page')

@section(config('adminlte.title'), 'AdminLTE')

@section('content_header')
    <h1 class="m-0 text-dark">Languages</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <x-adminlte-card title="List of Languages" theme="default" icon="fas fa-sm fa-language" collapsible>
                <x-slot name="toolsSlot">
                    <a href="{{ route('languages.create') }}">
                        <x-adminlte-button class="btn-sm btn-flat" label="{{ __('adminlte::adminlte.add_new') }}" theme="success" icon="fas fa-plus"/>
                    </a>
                </x-slot>
                @include('flash::message')
                <x-adminlte-datatable id="table1" :heads="$heads" :config="$config" head-theme="dark" striped hoverable compressed />
            </x-adminlte-card>
        </div>
    </div>
@stop