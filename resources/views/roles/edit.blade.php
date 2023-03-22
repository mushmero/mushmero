@extends('adminlte::page')

@section(config('adminlte.title'), 'AdminLTE')

@section('content_header')
    <h1 class="m-0 text-dark">Roles Management</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            @include('flash::message')
            <form class="form" action="{{ route('roles.update',$role->id) }}" method="post">
                @method('put')
                @csrf
                <x-adminlte-card title="Edit Role {{ $role->name }}" theme="default" icon="fas fa-sm fa-user-cog" collapsible>
                    <x-adminlte-input name="name" label="Name" fgroup-class="col-md-12 row" label-class="col-md-2 control-label" igroup-class="col-md-10" value="{{ $role->name }}" enable-old-support/>
                    <div class="form-group col-md-12 row">
                        <label for="permissions" class="col-md-2 control-label">Permissions</label>
                        <div class="input-group col-md-10">
                            <label>Sytem</label>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Module</th>
                                        <th>Permissions</th>
                                    </tr>
                                </thead>
                                @foreach ($systemPermissions as $key => $value)
                                    <tbody>
                                        <tr>
                                            <td>{{ __('adminlte::permission.'.$key) }}</td>
                                            <td>
                                                @foreach ($value as $v)
                                                        <div class="icheck-red">
                                                            <input type="checkbox" name="systemPermissions[]" id="{{ $v }}" value="{{ $v }}" {{ (in_array($v, $permittedPermissions)) ? "checked" : "" }}>

                                                            <label for="{{ $v }}">
                                                                {{ $v }}
                                                            </label>
                                                        </div>
                                                @endforeach
                                            </td>
                                        </tr>
                                    </tbody>
                                @endforeach
                            </table>
                            <label>Modules</label>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Module</th>
                                        <th colspan="{{ count(array_values($permissions)) }}">Permissions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach ($permissions as $key => $value)
                                    <tr>
                                        <td>{{ __('adminlte::permission.'.$key) }}</td>
                                        @foreach ($value as $v)
                                            <td>
                                                <div class="icheck-greensea">
                                                    <input type="checkbox" name="permissions[]" id="{{ $key.'_'.$v }}" value="{{ $key.'.'.$v }}" {{ (in_array($key.'.'.$v, $permittedPermissions) || in_array($v, $permittedPermissions)) ? "checked" : "" }}>

                                                    <label for="{{ $key.'_'.$v }}">
                                                        @if ($v == $key)
                                                            {{ __('adminlte::permission.index') }}
                                                        @else
                                                            {{ __('adminlte::permission.'.$v) }}
                                                        @endif
                                                    </label>
                                                </div>
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
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