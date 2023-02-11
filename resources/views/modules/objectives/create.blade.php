@extends('adminlte::page')

@section(config('adminlte.title'), 'AdminLTE')

@section('content_header')
    <h1 class="m-0 text-dark">Objectives</h1>
@stop
@section('plugins.bootstrapSwitch', true)

@section('content')
    <div class="row">
        <div class="col-12">
            <x-flashmessages-container />
            <form class="form" action="{{ route('objectives.store') }}" method="post">
                @csrf
                <x-adminlte-card title="Create Objective" theme="default" icon="fas fa-sm fa-bullseye" collapsible>
                    <x-adminlte-textarea name="content" label="Content" rows=5 fgroup-class="col-md-6 row" label-class="col-md-2 control-label" igroup-class="col-md-10" required enable-old-support/>
                    <x-adminlte-input-switch name="is_active" label="Active" fgroup-class="col-md-6 row" label-class="col-md-2 control-label" igroup-class="col-md-10" data-on-text="Yes" data-off-text="No" data-on-color="teal" data-off-color="red" required/>
                    <x-slot name="footerSlot">
                        <a href="{{ route('objectives') }}">
                            <x-adminlte-button class="btn-flat" label="{{ __('adminlte::adminlte.back') }}" theme="default" icon="fas fa-chevron-left"/>
                        </a>
                        <x-adminlte-button class="btn-flat" type="submit" label="Submit" theme="success" icon="fas fa-lg fa-save"/>
                    </x-slot>
                </x-adminlte-card>
            </form>
        </div>
    </div>
@stop