@extends('adminlte::page')

@section(config('adminlte.title'), 'AdminLTE')

@section('content_header')
    <h1 class="m-0 text-dark">New Request</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <x-adminlte-card title="Registration - {{ $data['name'] }}" theme="default" icon="fas fa-sm fa-file-alt" collapsible>
                <div class="row">
                    <div class="col-6 row">
                        <label class="col-md-2 control-label">Name</label>
                        <div class="col-md-10">
                            {{ $data->name }}
                        </div>
                    </div>
                    <div class="col-6 row">
                        <label class="col-md-2 control-label">Email</label>
                        <div class="col-md-10">
                            {{ $data->email }}
                        </div>
                    </div>
                    <div class="col-6 row">
                        <label class="col-md-2 control-label">Activate</label>
                        <div class="col-md-9">
                            {{ $data->activate == 1 ? 'Yes' : 'No' }}
                        </div>
                    </div>
                    <div class="col-6 row">
                        <label class="col-md-2 control-label">Activate Timestamp</label>
                        <div class="col-md-10">
                            {{  $data->activate_time }}
                        </div>
                    </div>
                    <div class="col-6 row">
                        <label class="col-md-2 control-label">Proposed link</label>
                        <div class="col-md-10">
                            {{ $data->subdomain ?? 'N/A' }}
                        </div>
                    </div>
                    <div class="col-6 row">
                        <label class="col-md-2 control-label">Deactivate Timestamp</label>
                        <div class="col-md-10">
                            {{  $data->deactivate_time }}
                        </div>
                    </div>
                    <div class="col-6 row">
                        <label class="col-md-2 control-label">Reason</label>
                        <div class="col-md-10">
                            {{ $data->reason ?? 'N/A' }}
                        </div>
                    </div>
                </div>
                <x-slot name="footerSlot">
                    <a href="{{ route('leads') }}">
                        <x-adminlte-button class="btn-flat" label="{{ __('adminlte::adminlte.back') }}" theme="default" icon="fas fa-chevron-left"/>
                    </a>
                </x-slot>
            </x-adminlte-card>
        </div>
    </div>
@stop