@extends('adminlte::page')

@section(config('adminlte.title'), 'AdminLTE')

@section('content_header')
    <h1 class="m-0 text-dark">Projects</h1>
@stop
@section('plugins.Summernote', true)

@section('content')
    <div class="row">
        <div class="col-12">
            @include('flash::message')
            <form class="form" action="{{ route('projects.update',$data->id) }}" method="post">
                @method('put')
                @csrf
                <x-adminlte-card title="Edit Project {{ $data->name }}" theme="default" icon="fas fa-sm fa-archive" collapsible>
                    <x-adminlte-input name="name" label="Project Name" fgroup-class="col-md-6 row" label-class="col-md-2 control-label" igroup-class="col-md-10" value="{{ $data->name }}" required enable-old-support/>
                    <x-adminlte-select2 id="status" name="status" label="Status" fgroup-class="col-md-6 row" label-class="col-md-2 control-label"  igroup-class="col-md-10" igroup-size="md" :config="$configStatus" enable-old-support>
                        <option/>
                        @foreach ($status as $st)
                            <option value="{{ $st->id }}"  {{ $data->status_id == $st->id ? "selected" : "" }}>{{ $st->status }}</option>
                        @endforeach
                    </x-adminlte-select2>       
                    <x-adminlte-text-editor id="description" name="description" label="Desription" label-class="col-md-2 control-label" igroup-size="sm" :config="$configTE">
                        {{ html_entity_decode($data->description) }}
                    </x-adminlte-text-editor>
                    <x-slot name="footerSlot">
                        <a href="{{ route('projects') }}">
                            <x-adminlte-button class="btn-flat" label="{{ __('adminlte::adminlte.back') }}" theme="default" icon="fas fa-chevron-left"/>
                        </a>
                        <x-adminlte-button class="btn-flat" type="submit" label="Submit" theme="success" icon="fas fa-lg fa-save"/>
                    </x-slot>
                </x-adminlte-card>
            </form>
        </div>
    </div>
@stop