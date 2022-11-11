@extends('adminlte::page')

@section(config('adminlte.title'), 'AdminLTE')

@section('content_header')
    <h1 class="m-0 text-dark">PDF Settings</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <x-adminlte-card title="PDF {{ $data->name }}" theme="default" icon="fas fa-sm fa-wrench" collapsible>
                <x-adminlte-input name="name" label="Name" fgroup-class="col-md-6 row" label-class="col-md-2 control-label" igroup-class="col-md-10" value="{{ $data->name }}" readonly/>
                <div class="row">
                    <x-adminlte-input name="margin_top" label="Margin Top" type="number" fgroup-class="col-md-4" label-class="col-md-12 control-label" igroup-class="col-md-12" igroup-size="sm" value="{{ $data->margin_top }}" readonly/>
                    <x-adminlte-input name="margin_bottom" label="Margin Bottom" type="number" fgroup-class="col-md-4" label-class="col-md-12 control-label" igroup-class="col-md-12" igroup-size="sm" value="{{ $data->margin_bottom }}" readonly/>
                    <x-adminlte-input name="margin_left" label="Margin Left" type="number" fgroup-class="col-md-4" label-class="col-md-12 control-label" igroup-class="col-md-12" igroup-size="sm" value="{{ $data->margin_left }}" readonly/>
                </div>
                <div class="row">
                    <x-adminlte-input name="margin_right" label="Margin Right" type="number" fgroup-class="col-md-4" label-class="col-md-12 control-label" igroup-class="col-md-12" igroup-size="sm" value="{{ $data->margin_right }}" readonly/>
                    <x-adminlte-input name="header" label="Header" type="number" fgroup-class="col-md-4" label-class="col-md-12 control-label" igroup-class="col-md-12" igroup-size="sm" value="{{ $data->header }}" readonly/>
                    <x-adminlte-input name="footer" label="Footer" type="number" fgroup-class="col-md-4" label-class="col-md-12 control-label" igroup-class="col-md-12" igroup-size="sm" value="{{ $data->footer }}" readonly/>
                </div>
                <x-adminlte-input name="user" label="User" fgroup-class="col-md-6 row" label-class="col-md-2 control-label" igroup-class="col-md-10" value="{{ $user->name }}" readonly/>
                <x-slot name="footerSlot">
                    <a href="{{ route('settings_pdf') }}">
                        <x-adminlte-button class="btn-flat" label="{{ __('adminlte::adminlte.back') }}" theme="default" icon="fas fa-chevron-left"/>
                    </a>
                </x-slot>
            </x-adminlte-card>
        </div>
    </div>
@stop