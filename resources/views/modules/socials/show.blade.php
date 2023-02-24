@extends('adminlte::page')

@section(config('adminlte.title'), 'AdminLTE')

@section('content_header')
    <h1 class="m-0 text-dark">Socials</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <x-adminlte-card title="Socials " theme="default" icon="fas fa-sm fa-university" collapsible>
                <x-adminlte-input name="name" label="Social Name" fgroup-class="col-md-6 row" label-class="col-md-2 control-label" igroup-class="col-md-10" value="{{ $data->name }}" readonly/>
                <x-adminlte-input name="link" label="Social Link" fgroup-class="col-md-6 row" label-class="col-md-2 control-label" igroup-class="col-md-10" value="{{ $data->link }}" readonly/>
                <x-adminlte-input name="icon" label="Icon" fgroup-class="col-md-6 row" label-class="col-md-2 control-label" igroup-class="col-md-10" value="{{ $data->allicons->name }}" readonly/>
                <x-adminlte-input name="user" label="User" fgroup-class="col-md-6 row" label-class="col-md-2 control-label" igroup-class="col-md-10" value="{{ $user->name }}" readonly/>
                <x-slot name="footerSlot">
                    <a href="{{ route('socials') }}">
                        <x-adminlte-button class="btn-flat" label="{{ __('adminlte::adminlte.back') }}" theme="default" icon="fas fa-chevron-left"/>
                    </a>
                </x-slot>
            </x-adminlte-card>
        </div>
    </div>
@stop