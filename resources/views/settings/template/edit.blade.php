@extends('adminlte::page')

@section(config('adminlte.title'), 'AdminLTE')

@section('content_header')
    <h1 class="m-0 text-dark">Template Settings</h1>
@stop
@section('plugins.Select2', true)
@section('plugins.Summernote', true)

@section('content')
    <div class="row">
        <div class="col-12">
            @include('flash::message')
            <form class="form" action="{{ route('settings_template.update',$data->id) }}" method="post">
                @method('put')
                @csrf
                <x-adminlte-card title="Edit Template {{ $data->name }}" theme="default" icon="fas fa-sm fa-user-alt" collapsible>
                    <div class="row">
                        <x-adminlte-input name="name" label="Name" fgroup-class="col-md-6" label-class="col-md-12 control-label" igroup-class="col-md-12" value="{{ $data->name }}" required enable-old-support/>
                        <x-adminlte-select2 id="fields" name="fields" label="Fields" fgroup-class="col-md-6" label-class="col-md-12 control-label"  igroup-class="col-md-12" igroup-size="md" :config="$configFields" enable-old-support>
                            <option/>
                            @foreach ($fields as $field)
                                <option value="{{ $field }}">{{ $field }}</option>
                            @endforeach
                        </x-adminlte-select2>
                    </div>
                    <div class="row">
                        <x-adminlte-select2 id="primary_colour" name="primary_colour" label="Primary Colour" fgroup-class="col-md-6" label-class="col-md-12 control-label"  igroup-class="col-md-12" igroup-size="md" :config="$configColor" required enable-old-support>
                            <option/>
                            @foreach ($colors as $color)
                                <option value="{{ $color }}" class="{{ $color }}" {{ $data->primary_colour == $color ? "selected" : "" }}>{{ $color }}</option>
                            @endforeach
                        </x-adminlte-select2>
                        <x-adminlte-select2 id="secondary_colour" name="secondary_colour" label="Secondary Colour" fgroup-class="col-md-6" label-class="col-md-12 control-label"  igroup-class="col-md-12" igroup-size="md" :config="$configColor" required enable-old-support>
                            <option/>
                            @foreach ($colors as $color)
                                <option value="{{ $color }}" class="{{ $color }}" {{ $data->secondary_colour == $color ? "selected" : "" }}>{{ $color }}</option>
                            @endforeach
                        </x-adminlte-select2>
                    </div>
                    <x-adminlte-text-editor id="design" name="design" label="Design" label-class="col-md-2" igroup-size="sm" :config="$configTE" >
                        {{ $data->design }}
                    </x-adminlte-text-editor>
                    <x-slot name="footerSlot">
                        <a href="{{ route('settings_template') }}">
                            <x-adminlte-button class="btn-flat" label="{{ __('adminlte::adminlte.back') }}" theme="default" icon="fas fa-chevron-left"/>
                        </a>
                        <x-adminlte-button class="btn-flat" type="submit" label="Submit" theme="success" icon="fas fa-lg fa-save"/>
                    </x-slot>
                </x-adminlte-card>
            </form>
        </div>
    </div>
@stop