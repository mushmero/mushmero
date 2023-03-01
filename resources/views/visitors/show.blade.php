@extends('adminlte::page')

@section(config('adminlte.title'), 'AdminLTE')

@section('content_header')
    <h1 class="m-0 text-dark">Visitors</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <x-adminlte-card title="Visitor {{ $data->userip }}" theme="default" icon="fas fa-sm fa-user-secret" collapsible>
                <div class="row">
                    <div class="col-md-6 row">                        
                        <label class="col-md-2 control-label">User IP</label>
                        <div class="col-md-10">
                            {{ $data->userip }}
                        </div>                        
                        <label class="col-md-2 control-label">User Agent</label>
                        <div class="col-md-10">
                            {{ $data->useragent }}
                        </div>                        
                        <label class="col-md-2 control-label">Country</label>
                        <div class="col-md-10">
                            {{ !empty($data->country) ? $data->country .' ('.$data->countryCode.')' : '' }}
                        </div>                        
                        <label class="col-md-2 control-label">City</label>
                        <div class="col-md-10">
                            {{ $data->city }}
                        </div>                        
                        <label class="col-md-2 control-label">Latitude</label>
                        <div class="col-md-10">
                            {{ $data->latitude }}
                        </div>
                    </div>
                    <div class="col-md-6 row">                        
                        <label class="col-md-2 control-label">Referrer</label>
                        <div class="col-md-10">
                            {{ $data->referrer }}
                        </div>                        
                        <label class="col-md-2 control-label">Datetime</label>
                        <div class="col-md-10">
                            {{ $data->datetime }}
                        </div>                        
                        <label class="col-md-2 control-label">Continent</label>
                        <div class="col-md-10">
                            {{ !empty($data->continent) ? $data->continent .' ('.$data->continentCode.')' : '' }}
                        </div>                        
                        <label class="col-md-2 control-label">Timezone</label>
                        <div class="col-md-10">
                            {{ $data->timezone }}
                        </div>                        
                        <label class="col-md-2 control-label">Longitude</label>
                        <div class="col-md-10">
                            {{ $data->longitude }}
                        </div>
                    </div>
                </div>
                <x-slot name="footerSlot">
                    <a href="{{ route('visitors') }}">
                        <x-adminlte-button class="btn-flat" label="{{ __('adminlte::adminlte.back') }}" theme="default" icon="fas fa-chevron-left"/>
                    </a>
                </x-slot>
            </x-adminlte-card>
        </div>
    </div>
@stop