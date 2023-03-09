@extends('adminlte::page')

@section(config('adminlte.title'), 'AdminLTE')

@section('content_header')
    <h1 class="m-0 text-dark">Visitors</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <x-adminlte-card title="Visitors" theme="default" icon="fas fa-sm fa-user-secret" collapsible>
                @include('flash::message')
                <x-adminlte-datatable id="table1" :heads="$heads" :config="$config" head-theme="dark" striped hoverable compressed />
            </x-adminlte-card>
        </div>
    </div>
@stop