@extends('adminlte::page')

@section(config('adminlte.title'), 'AdminLTE')

@section('content_header')
    <h1 class="m-0 text-dark">Social Settings</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <x-adminlte-card title="Icon Lists" theme="default" icon="fas fa-sm fa-wrench" collapsible>
                <x-slot name="toolsSlot">
                    <a href="{{ route('settings_social.create') }}">
                        <x-adminlte-button class="btn-sm btn-flat" label="{{ __('adminlte::adminlte.add_new') }}" theme="success" icon="fas fa-plus"/>
                    </a>
                </x-slot>
                <x-flashmessages-container />
                <x-adminlte-datatable id="table1" :heads="$heads" head-theme="dark" striped hoverable compressed>
                    @foreach($config['data'] as $row)
                        <tr>
                            @foreach($row as $cell)
                                <td>{!! $cell !!}</td>
                            @endforeach
                        </tr>
                    @endforeach
                </x-adminlte-datatable>
            </x-adminlte-card>
        </div>
    </div>
@stop