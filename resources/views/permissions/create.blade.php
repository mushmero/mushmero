@extends('adminlte::page')

@section(config('adminlte.title'), 'AdminLTE')

@section('content_header')
    <h1 class="m-0 text-dark">Permissions Management</h1>
@stop
@section('plugins.Select2', true)

@section('content')
    <div class="row">
        <div class="col-12">
            <x-flashmessages-container />
            <form class="form" action="{{ route('permissions.store') }}" method="post">
                @csrf
                <x-adminlte-card title="Create Permission" theme="default" icon="fas fa-sm fa-user-shield" collapsible>
                    <x-adminlte-select2 id="modules" name="modules" label="Modules" fgroup-class="col-md-6 row" label-class="col-md-2 control-label"  igroup-class="col-md-10" igroup-size="md" :config="$config" required enable-old-support>
                        <option/>
                        @foreach ($modules as $module)
                            <option value="{{ $module }}">{{ $module }}</option>
                        @endforeach
                    </x-adminlte-select2>
                    <div class="form-group row">
                        <div class="icheck-primary col-md-1" title="{{ __('adminlte::adminlte.create') }}">
                            <input type="checkbox" class="" name="create" id="create" {{ old('create') ? 'checked' : '' }}>

                            <label for="create" class="control-label">
                                {{ __('adminlte::adminlte.create') }}
                            </label>
                        </div>
                        <div class="icheck-primary col-md-1" title="{{ __('adminlte::adminlte.edit') }}">
                            <input type="checkbox" class="" name="edit" id="edit" {{ old('edit') ? 'checked' : '' }}>

                            <label for="edit" class="control-label">
                                {{ __('adminlte::adminlte.edit') }}
                            </label>
                        </div>
                        <div class="icheck-primary col-md-1" title="{{ __('adminlte::adminlte.index') }}">
                            <input type="checkbox" class="" name="index" id="index" {{ old('index') ? 'checked' : '' }}>

                            <label for="index" class="control-label">
                                {{ __('adminlte::adminlte.index') }}
                            </label>
                        </div>
                        <div class="icheck-primary col-md-1" title="{{ __('adminlte::adminlte.delete') }}">
                            <input type="checkbox" class="" name="delete" id="delete" {{ old('delete') ? 'checked' : '' }}>

                            <label for="delete" class="control-label">
                                {{ __('adminlte::adminlte.delete') }}
                            </label>
                        </div>
                    </div>
                    <x-slot name="footerSlot">
                        <a href="{{ route('permissions') }}">
                            <x-adminlte-button class="btn-flat" label="{{ __('adminlte::adminlte.back') }}" theme="default" icon="fas fa-chevron-left"/>
                        </a>
                        <x-adminlte-button class="btn-flat" type="submit" label="Submit" theme="success" icon="fas fa-lg fa-save"/>
                    </x-slot>
                </x-adminlte-card>
            </form>
        </div>
    </div>
@stop