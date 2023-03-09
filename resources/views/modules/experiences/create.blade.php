@extends('adminlte::page')

@section(config('adminlte.title'), 'AdminLTE')

@section('content_header')
    <h1 class="m-0 text-dark">Experiences</h1>
@stop
@section('plugins.Summernote', true)
@section('plugins.TempusDominusBs4', true)

@section('content')
    <div class="row">
        <div class="col-12">
            @include('flash::message')
            <form class="form" action="{{ route('experiences.store') }}" method="post">
                @csrf
                <x-adminlte-card title="Create Experience" theme="default" icon="fas fa-sm fa-briefcase" collapsible>
                    <x-adminlte-input name="position" label="Position" fgroup-class="col-md-6 row" label-class="col-md-2 control-label" igroup-class="col-md-10" required enable-old-support/>
                    <x-adminlte-input name="employer" label="Employer" fgroup-class="col-md-6 row" label-class="col-md-2 control-label" igroup-class="col-md-10" required enable-old-support/>
                    <x-adminlte-input-date name="startDate" label="From" fgroup-class="col-md-6 row" label-class="col-md-2 control-label" igroup-class="col-md-10" :config="$startDate" placeholder="Choose start date...">
                        <x-slot name="appendSlot">
                            <div class="input-group-text bg-gradient-info">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input-date>
                    <x-adminlte-input-date name="endDate" label="To" fgroup-class="row col-md-6" label-class="col-md-2 control-label" igroup-class="col-md-10" :config="$endDate" placeholder="Choose end date...">
                        <x-slot name="appendSlot">
                            <div class="input-group-text bg-gradient-info">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input-date>
                    <div class="form-group col-md-6">
                        <div class="col-md-2">&nbsp;</div>
                        <div class="input-group col-md-10">
                            <div class="icheck-blue">
                                <input type="checkbox" name="current" id="current" value="current">
                                <label for="current">
                                    Currently working here
                                </label>
                            </div>
                        </div>
                    </div>
                    <x-adminlte-text-editor id="description" name="description" label="Desription" label-class="col-md-2 control-label" igroup-size="sm" placeholder="" :config="$configTE"/>
                    <x-slot name="footerSlot">
                        <a href="{{ route('experiences') }}">
                            <x-adminlte-button class="btn-flat" label="{{ __('adminlte::adminlte.back') }}" theme="default" icon="fas fa-chevron-left"/>
                        </a>
                        <x-adminlte-button class="btn-flat" type="submit" label="Submit" theme="success" icon="fas fa-lg fa-save"/>
                    </x-slot>
                </x-adminlte-card>
            </form>
        </div>
    </div>
@stop