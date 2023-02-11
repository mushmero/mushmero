@extends('adminlte::page')

@section(config('adminlte.title'), 'AdminLTE')

@section('content_header')
    <h1 class="m-0 text-dark">Objective</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <x-adminlte-card title="Objective " theme="default" icon="fas fa-sm fa-bullseye" collapsible>
                <x-adminlte-textarea name="content" label="Content" rows=5 fgroup-class="col-md-6 row" label-class="col-md-2 control-label" igroup-class="col-md-10" style="resize:none" readonly>
                    {{ $data->content }}
                </x-adminlte-textarea>
                <x-adminlte-input name="user" label="User" fgroup-class="col-md-6 row" label-class="col-md-2 control-label" igroup-class="col-md-10" value="{{ $user->name }}" readonly/>
                @if($data->is_active == 1)
                    <x-adminlte-input-switch name="is_active" label="Active" fgroup-class="col-md-6 row" label-class="col-md-2 control-label" igroup-class="col-md-10" data-on-text="Yes" data-off-text="No" data-on-color="teal" data-off-color="red" checked readonly/>
                @else
                    <x-adminlte-input-switch name="is_active" label="Active" fgroup-class="col-md-6 row" label-class="col-md-2 control-label" igroup-class="col-md-10" data-on-text="Yes" data-off-text="No" data-on-color="teal" data-off-color="red" readonly/>
                @endif
                <x-slot name="footerSlot">
                    <a href="{{ route('objectives') }}">
                        <x-adminlte-button class="btn-flat" label="{{ __('adminlte::adminlte.back') }}" theme="default" icon="fas fa-chevron-left"/>
                    </a>
                </x-slot>
            </x-adminlte-card>
        </div>
    </div>
@stop