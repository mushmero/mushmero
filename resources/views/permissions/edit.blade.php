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
            <form class="form" action="{{ route('permissions.update',$permission->id) }}" method="post">
                @method('put')
                @csrf
                <x-adminlte-card title="Edit Permission {{ $permission->name }}" theme="default" icon="fas fa-sm fa-user-cog" collapsible>
                    <x-adminlte-select2 id="modules" name="modules" label="Modules" fgroup-class="col-md-6 row" label-class="col-md-2 control-label"  igroup-class="col-md-10" igroup-size="md" :config="$config" required enable-old-support>
                        <option/>
                        @foreach ($modules as $module)
                            <option value="{{ $module }}" {{ (explode('-',$permission->name)[0] == $module) ? "selected" : "" }}>{{ $module }}</option>
                        @endforeach
                    </x-adminlte-select2>
                    <div class="form-group row">
                    @if (explode('-',$permission->name)[1] == 'create')
                        <div class="icheck-primary col-md-1" title="{{ __('adminlte::adminlte.create') }}">
                            <input type="checkbox" class="" name="create" id="create" {{ (explode('-',$permission->name)[1] == 'create') ? 'checked' : '' }} onclick="return false;" onkeydown="e = e || window.event; if(e.keyCode !== 9) return false;">

                            <label for="create" class="control-label">
                                {{ __('adminlte::adminlte.create') }}
                            </label>
                        </div>                        
                    @endif
                    @if (explode('-',$permission->name)[1] == 'edit')
                        <div class="icheck-primary col-md-1" title="{{ __('adminlte::adminlte.edit') }}">
                            <input type="checkbox" class="" name="edit" id="edit" {{ (explode('-',$permission->name)[1] == 'edit') ? 'checked' : '' }} onclick="return false;" onkeydown="e = e || window.event; if(e.keyCode !== 9) return false;">

                            <label for="edit" class="control-label">
                                {{ __('adminlte::adminlte.edit') }}
                            </label>
                        </div>                        
                    @endif
                    @if (explode('-',$permission->name)[1] == 'index')
                        <div class="icheck-primary col-md-1" title="{{ __('adminlte::adminlte.index') }}">
                            <input type="checkbox" class="" name="index" id="index" {{ (explode('-',$permission->name)[1] == 'index') ? 'checked' : '' }} onclick="return false;" onkeydown="e = e || window.event; if(e.keyCode !== 9) return false;">

                            <label for="index" class="control-label">
                                {{ __('adminlte::adminlte.index') }}
                            </label>
                        </div>                        
                    @endif
                    @if (explode('-',$permission->name)[1] == 'delete')
                        <div class="icheck-primary col-md-1" title="{{ __('adminlte::adminlte.delete') }}">
                            <input type="checkbox" class="" name="delete" id="delete" {{ (explode('-',$permission->name)[1] == 'delete') ? 'checked' : '' }} onclick="return false;" onkeydown="e = e || window.event; if(e.keyCode !== 9) return false;">

                            <label for="delete" class="control-label">
                                {{ __('adminlte::adminlte.delete') }}
                            </label>
                        </div>                        
                    @endif
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