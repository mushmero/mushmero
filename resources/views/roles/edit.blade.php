@extends('adminlte::page')

@section(config('adminlte.title'), 'AdminLTE')

@section('content_header')
    <h1 class="m-0 text-dark">Roles Management</h1>
@stop
@section('plugins.Select2', true)

@section('content')
    <div class="row">
        <div class="col-12">
            <x-flashmessages-container />
            <form class="form" action="{{ route('roles.update',$role->id) }}" method="post">
                @method('put')
                @csrf
                <x-adminlte-card title="Edit Role {{ $role->name }}" theme="default" icon="fas fa-sm fa-user-cog" collapsible>
                    <x-adminlte-input name="name" label="Name" fgroup-class="col-md-6 row" label-class="col-md-2 control-label" igroup-class="col-md-10" value="{{ $role->name }}" enable-old-support/>
                    <x-adminlte-select2 id="permissions" name="permissions[]" label="Permissions" fgroup-class="col-md-6 row" label-class="col-md-2 control-label"  igroup-class="col-md-10" igroup-size="md" :config="$config" multiple required enable-old-support>
                        @foreach ($permissions as $permission)
                            <option value="{{ $permission->id }}" {{ (in_array($permission->id, $rolePermissions)) ? "selected" : "" }}>{{ $permission->name }}</option>
                        @endforeach
                    </x-adminlte-select2>
                    <x-slot name="footerSlot">
                        <a href="{{ route('roles') }}">
                            <x-adminlte-button class="btn-flat" label="{{ __('adminlte::adminlte.back') }}" theme="default" icon="fas fa-chevron-left"/>
                        </a>
                        <x-adminlte-button class="btn-flat" type="submit" label="Submit" theme="success" icon="fas fa-lg fa-save"/>
                    </x-slot>
                </x-adminlte-card>
            </form>
        </div>
    </div>
@stop