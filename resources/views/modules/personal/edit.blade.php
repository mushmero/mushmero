@extends('adminlte::page')

@section(config('adminlte.title'), 'AdminLTE')

@section('content_header')
    <h1 class="m-0 text-dark">Personal</h1>
@stop
@section('plugins.BsCustomFileInput', true)
@section('plugins.bootstrapSwitch', true)

@section('content')
    <div class="row">
        <div class="col-12">
            @include('flash::message')
            <form class="form" action="{{ route('personal.update',$data->id) }}" method="post" enctype="multipart/form-data">
                @method('put')
                @csrf
                <x-adminlte-card title="Edit Social {{ $data->name }}" theme="default" icon="fas fa-sm fa-user" collapsible>
                    <x-adminlte-input name="fullname" label="Full Name" fgroup-class="col-md-6 row" label-class="col-md-2 control-label" igroup-class="col-md-10" value="{{ $data->fullname }}" required enable-old-support/>
                    <x-adminlte-input name="tagline" label="Tagline" fgroup-class="col-md-6 row" label-class="col-md-2 control-label" igroup-class="col-md-10" value="{{ $data->tagline }}" enable-old-support/>
                    <x-adminlte-input name="email" label="Email" type="email" fgroup-class="col-md-6 row" label-class="col-md-2 control-label" igroup-class="col-md-10" value="{{ $data->email }}" enable-old-support>
                        <x-slot name="prependSlot">
                            <div class="input-group-text">
                                <i class="fas fa-at text-red"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input>
                    <x-adminlte-input name="phone" label="Phone" fgroup-class="col-md-6 row" label-class="col-md-2 control-label" igroup-class="col-md-10" value="{{ $data->phone }}" enable-old-support>
                        <x-slot name="prependSlot">
                            <div class="input-group-text">
                                <i class="fas fa-phone text-lightblue"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input>
                    <x-adminlte-input name="website" label="Website" fgroup-class="col-md-6 row" label-class="col-md-2 control-label" igroup-class="col-md-10" value="{{ $data->website }}" enable-old-support>
                        <x-slot name="prependSlot">
                            <div class="input-group-text">
                                <i class="fas fa-globe text-yellow"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input>
                    <div class="form-group col-md-6 row">
                        <label for="image" class="col-md-2 control-label">Uploaded Image</label>
                        <div class="col-md-10">
                            <img id="image" src="{{ url($attachment->path.'/'.$attachment->filename) }}" alt="{{ $attachment->altname }}" height="200" width="200">
                        </div>
                    </div>
                    <x-adminlte-input-file name="profilephoto" label="Upload image" placeholder="Choose a file..." fgroup-class="col-md-6 row" label-class="col-md-2 control-label" igroup-class="col-md-10" accept="image/jpeg, image/png">
                        <x-slot name="prependSlot">
                            <div class="input-group-text bg-dark">
                                <i class="fas fa-upload"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input-file>
                    <x-slot name="footerSlot">
                        <a href="{{ route('personal') }}">
                            <x-adminlte-button class="btn-flat" label="{{ __('adminlte::adminlte.back') }}" theme="default" icon="fas fa-chevron-left"/>
                        </a>
                        <x-adminlte-button class="btn-flat" type="submit" label="Submit" theme="success" icon="fas fa-lg fa-save"/>
                    </x-slot>
                </x-adminlte-card>
            </form>
        </div>
    </div>
@stop