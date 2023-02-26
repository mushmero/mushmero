@extends('adminlte::page')

@section(config('adminlte.title'), 'AdminLTE')

@section('content_header')
    <h1 class="m-0 text-dark">Personal</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <x-adminlte-card title="Personal " theme="default" icon="fas fa-sm fa-user" collapsible>
                <x-adminlte-input name="fullname" label="Full Name" fgroup-class="col-md-6 row" label-class="col-md-2 control-label" igroup-class="col-md-10" value="{{ $data->fullname }}" readonly/>
                <x-adminlte-input name="tagline" label="Tagline" fgroup-class="col-md-6 row" label-class="col-md-2 control-label" igroup-class="col-md-10" value="{{ $data->tagline }}" readonly/>
                <x-adminlte-input name="email" label="Email" type="email" fgroup-class="col-md-6 row" label-class="col-md-2 control-label" igroup-class="col-md-10" value="{{ $data->email }}" readonly>
                    <x-slot name="prependSlot">
                        <div class="input-group-text">
                            <i class="fas fa-at text-red"></i>
                        </div>
                    </x-slot>
                </x-adminlte-input>
                <x-adminlte-input name="phone" label="Phone" fgroup-class="col-md-6 row" label-class="col-md-2 control-label" igroup-class="col-md-10" value="{{ $data->phone }}" readonly>
                    <x-slot name="prependSlot">
                        <div class="input-group-text">
                            <i class="fas fa-phone text-lightblue"></i>
                        </div>
                    </x-slot>
                </x-adminlte-input>
                <x-adminlte-input name="website" label="Website" fgroup-class="col-md-6 row" label-class="col-md-2 control-label" igroup-class="col-md-10" value="{{ $data->website }}" readonly>
                    <x-slot name="prependSlot">
                        <div class="input-group-text">
                            <i class="fas fa-globe text-yellow"></i>
                        </div>
                    </x-slot>
                </x-adminlte-input>
                @if($data->status == 1)
                    <x-adminlte-input-switch name="status" label="Status" fgroup-class="col-md-6 row" label-class="col-md-2 control-label" igroup-class="col-md-10" data-on-text="Active" data-off-text="Inactive" data-on-color="teal" data-off-color="red" checked readonly/>
                @else
                    <x-adminlte-input-switch name="status" label="Status" fgroup-class="col-md-6 row" label-class="col-md-2 control-label" igroup-class="col-md-10" data-on-text="Active" data-off-text="Inactive" data-on-color="teal" data-off-color="red" readonly/>
                @endif
                <div class="form-group col-md-6 row">
                    <label for="image" class="col-md-2 control-label">Uploaded Image</label>
                    <div class="col-md-10">
                        <img id="image" src="{{ url($attachment->path.'/'.$attachment->filename) }}" alt="{{ $attachment->altname }}" height="200" width="200">
                    </div>
                </div>
                <x-adminlte-input name="user" label="User" fgroup-class="col-md-6 row" label-class="col-md-2 control-label" igroup-class="col-md-10" value="{{ $user->name }}" readonly/>
                <x-slot name="footerSlot">
                    <a href="{{ route('personal') }}">
                        <x-adminlte-button class="btn-flat" label="{{ __('adminlte::adminlte.back') }}" theme="default" icon="fas fa-chevron-left"/>
                    </a>
                </x-slot>
            </x-adminlte-card>
        </div>
    </div>
@stop