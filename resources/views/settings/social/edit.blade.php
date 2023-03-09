@extends('adminlte::page')

@section(config('adminlte.title'), 'AdminLTE')

@section('content_header')
    <h1 class="m-0 text-dark">Social Settings</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            @include('flash::message')
            <form class="form" action="{{ route('settings_social.update',$data->id) }}" method="post">
                @method('put')
                @csrf
                <x-adminlte-card title="Edit Icon {{ $data->name }}" theme="default" icon="fas fa-sm fa-user-alt" collapsible>
                    <x-adminlte-input name="fullname" label="Fullname" fgroup-class="col-md-6 row" label-class="col-md-2 control-label" igroup-class="col-md-10" value="{{ $data->fullname }}" enable-old-support/>
                    <x-adminlte-input name="name" label="Name" fgroup-class="col-md-6 row" label-class="col-md-2 control-label" igroup-class="col-md-10" value="{{ $data->name }}" enable-old-support/>
                    <x-adminlte-input name="type" label="Type" fgroup-class="col-md-6 row" label-class="col-md-2 control-label" igroup-class="col-md-10" value="{{ $data->type }}" enable-old-support/>
                    <x-slot name="footerSlot">
                        <a href="{{ route('settings_social') }}">
                            <x-adminlte-button class="btn-flat" label="{{ __('adminlte::adminlte.back') }}" theme="default" icon="fas fa-chevron-left"/>
                        </a>
                        <x-adminlte-button class="btn-flat" type="submit" label="Submit" theme="success" icon="fas fa-lg fa-save"/>
                    </x-slot>
                </x-adminlte-card>
            </form>
        </div>
    </div>
@stop