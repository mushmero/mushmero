@extends('adminlte::page')

@section(config('adminlte.title'), 'AdminLTE')

@section('content_header')
    <h1 class="m-0 text-dark">Logs Management</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <x-adminlte-card title="Log - {{ $data['action'].'_'.$data['timestamp'] }}" theme="default" icon="fas fa-sm fa-file-alt" collapsible>
                <div class="row">
                    <div class="col-4 row">
                        <label class="col-md-2 control-label">User</label>
                        <div class="col-md-10">
                            {{ $data['user'] }}
                        </div>
                    </div>
                    <div class="col-4 row">
                        <label class="col-md-2 control-label">Action</label>
                        <div class="col-md-10">
                            {{ $data['action'] }}
                        </div>
                    </div>
                    <div class="col-4 row">
                        <label class="col-md-3 control-label">Description</label>
                        <div class="col-md-9">
                            {{ $data['description'] }}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-4 row">
                        <label class="col-md-2 control-label">Target</label>
                        <div class="col-md-10">
                            {{ $data['target'] }}
                        </div>
                    </div>
                    <div class="col-4 row">
                        <label class="col-md-2 control-label">Event</label>
                        <div class="col-md-10">
                            {{ $data['event'] }}
                        </div>
                    </div>
                    <div class="col-4 row">
                        <label class="col-md-3 control-label">Timestamp</label>
                        <div class="col-md-9">
                            {{ $data['timestamp'] }}
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-6">
                        <label class="control-label">New Value</label>
                        <div class="">
                            @if (!empty($data['new']))
                                @foreach ($data['new'] as $k => $v)
                                    @if ($k != 'password')
                                        @if (in_array($k, $timestamp))
                                            <label class="control-label col-md-2">{{ ucwords(str_replace('_',' ',$k)) }}</label> <span>{{ \App\Helpers\Helper::formatDate($v) }}</span><br> 
                                        @else
                                            <label class="control-label col-md-2">{{ ucwords(str_replace('_',' ',$k)) }}</label> <span>{{ $v }}</span><br>                             
                                        @endif
                                    @endif
                                @endforeach    
                            @else
                                <label class="control-label">{{ ucwords(str_replace('_',' ',$k)) }}</label> <span>{{ $v }}</span>
                            @endif
                        </div>
                    </div>
                    @if (!empty($data['old']))
                        <div class="col-md-6">
                            <label class="control-label">Previous Value</label>
                            <div class="">
                                @foreach ($data['old'] as $k => $v)
                                    @if ($k != 'password')
                                        @if (in_array($k, $timestamp))
                                            <label class="control-label col-md-2">{{ ucwords(str_replace('_',' ',$k)) }}</label> <span>{{ \App\Helpers\Helper::formatDate($v) }}</span><br> 
                                        @else
                                            <label class="control-label col-md-2">{{ ucwords(str_replace('_',' ',$k)) }}</label> <span>{{ $v }}</span><br>                             
                                        @endif
                                    @endif
                                @endforeach
                            </div>
                        </div>                            
                    @endif
                </div>
                <x-slot name="footerSlot">
                    <a href="{{ route('user_access') }}">
                        <x-adminlte-button class="btn-flat" label="{{ __('adminlte::adminlte.back') }}" theme="default" icon="fas fa-chevron-left"/>
                    </a>
                </x-slot>
            </x-adminlte-card>
        </div>
    </div>
@stop