@extends('adminlte::page')

@section(config('adminlte.title'), 'AdminLTE')

@section('content_header')
    <h1 class="m-0 text-dark">PDF Settings</h1>
@stop
@section('plugins.Select2', true)

@section('content')
    <div class="row">
        <div class="col-12">
            @include('flash::message')
            <form class="form" action="{{ route('settings_pdf.store') }}" method="post">
                @csrf
                <x-adminlte-card title="Create PDF Setting" theme="default" icon="fas fa-sm fa-wrench" collapsible>
                    <x-adminlte-input name="name" label="Name" fgroup-class="col-md-6 row" label-class="col-md-2 control-label" igroup-class="col-md-10" required enable-old-support/>
                    <div class="row">
                        <x-adminlte-input name="margin_top" label="Margin Top" type="number" fgroup-class="col-md-4" label-class="col-md-12 control-label" igroup-class="col-md-12" igroup-size="sm" min=0 max=100/>
                        <x-adminlte-input name="margin_bottom" label="Margin Bottom" type="number" fgroup-class="col-md-4" label-class="col-md-12 control-label" igroup-class="col-md-12" igroup-size="sm" min=0 max=100/>
                        <x-adminlte-input name="margin_left" label="Margin Left" type="number" fgroup-class="col-md-4" label-class="col-md-12 control-label" igroup-class="col-md-12" igroup-size="sm" min=0 max=100/>
                    </div>
                    <div class="row">
                        <x-adminlte-input name="margin_right" label="Margin Right" type="number" fgroup-class="col-md-4" label-class="col-md-12 control-label" igroup-class="col-md-12" igroup-size="sm" min=0 max=100/>
                        <x-adminlte-input name="header" label="Header" type="number" fgroup-class="col-md-4" label-class="col-md-12 control-label" igroup-class="col-md-12" igroup-size="sm" min=0 max=100/>
                        <x-adminlte-input name="footer" label="Footer" type="number" fgroup-class="col-md-4" label-class="col-md-12 control-label" igroup-class="col-md-12" igroup-size="sm" min=0 max=100/>
                    </div>
                    <x-slot name="footerSlot">
                        <a href="{{ route('settings_pdf') }}">
                            <x-adminlte-button class="btn-flat" label="{{ __('adminlte::adminlte.back') }}" theme="default" icon="fas fa-chevron-left"/>
                        </a>
                        <x-adminlte-button class="btn-flat" type="submit" label="Submit" theme="success" icon="fas fa-lg fa-save"/>
                    </x-slot>
                </x-adminlte-card>
            </form>
        </div>
    </div>
@stop