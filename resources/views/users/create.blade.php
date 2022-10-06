@extends('adminlte::page')

@section(config('adminlte.title'), 'AdminLTE')

@section('content_header')
    <h1 class="m-0 text-dark">Users Management</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <x-flashmessages-container />
            <form class="form" action="{{ route('users.store') }}" method="post">
                @csrf
                <x-adminlte-card title="Create User" theme="default" icon="fas fa-sm fa-user-alt" collapsible>
                    <x-adminlte-input name="name" label="Name" fgroup-class="col-md-6 row" label-class="col-md-2 control-label" igroup-class="col-md-10" required enable-old-support/>
                    <x-adminlte-input name="email" type="email" label="Email" fgroup-class="col-md-6 row" label-class="col-md-2 control-label" igroup-class="col-md-10" required enable-old-support>
                        <x-slot name="prependSlot">
                            <div class="input-group-text">
                                <i class="fas fa-envelope"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input>
                    <x-adminlte-input name="password" type="password" label="Password" fgroup-class="col-md-6 row" label-class="col-md-2 control-label" igroup-class="col-md-10" required/>
                    <x-adminlte-input name="password_confirmation" type="password" label="Confirm Password" fgroup-class="col-md-6 row" label-class="col-md-2 control-label" igroup-class="col-md-10" required/>
                    <x-slot name="footerSlot">
                        <a href="{{ route('users') }}">
                            <x-adminlte-button class="btn-flat" label="{{ __('adminlte::adminlte.back') }}" theme="default" icon="fas fa-chevron-left"/>
                        </a>
                        <x-adminlte-button class="btn-flat" type="submit" label="Submit" theme="success" icon="fas fa-lg fa-save"/>
                    </x-slot>
                </x-adminlte-card>
            </form>
        </div>
    </div>
@stop