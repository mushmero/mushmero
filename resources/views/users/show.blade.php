@extends('adminlte::page')

@section(config('adminlte.title'), 'AdminLTE')

@section('content_header')
    <h1 class="m-0 text-dark">Users Management</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <x-adminlte-card title="User {{ $user->name }}" theme="default" icon="fas fa-sm fa-user-alt" collapsible>
                <x-adminlte-input name="name" label="Name" fgroup-class="col-md-6 row" label-class="col-md-2 control-label" igroup-class="col-md-10" value="{{ $user->name }}" readonly/>
                <x-adminlte-input name="email" type="email" label="Email" fgroup-class="col-md-6 row" label-class="col-md-2 control-label" igroup-class="col-md-10" value="{{ $user->email }}" readonly>
                    <x-slot name="prependSlot">
                        <div class="input-group-text">
                            <i class="fas fa-envelope"></i>
                        </div>
                    </x-slot>
                </x-adminlte-input>
                {{-- <x-adminlte-input name="roles" label="Roles" fgroup-class="col-md-6 row" label-class="col-md-2 control-label" igroup-class="col-md-10" value="{{ implode(',',$user->getRoleNames()->toArray()) }}" readonly/> --}}
                <x-slot name="footerSlot">
                    <a href="{{ route('users') }}">
                        <x-adminlte-button class="btn-flat" label="{{ __('adminlte::adminlte.back') }}" theme="default" icon="fas fa-chevron-left"/>
                    </a>
                </x-slot>
            </x-adminlte-card>
        </div>
    </div>
@stop