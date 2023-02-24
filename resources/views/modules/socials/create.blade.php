@extends('adminlte::page')

@section(config('adminlte.title'), 'AdminLTE')

@section('content_header')
    <h1 class="m-0 text-dark">Socials</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <x-flashmessages-container />
            <form class="form" action="{{ route('socials.store') }}" method="post">
                @csrf
                <x-adminlte-card title="Create Social" theme="default" icon="fas fa-sm fa-user-friends" collapsible>
                    <x-adminlte-input name="name" label="Social Name" fgroup-class="col-md-6 row" label-class="col-md-2 control-label" igroup-class="col-md-10" placeholder="Enter social name" required enable-old-support/>
                    <x-adminlte-input name="link" label="Social Link" fgroup-class="col-md-6 row" label-class="col-md-2 control-label" igroup-class="col-md-10" placeholder="Enter social link including http:// or https://" enable-old-support/>
                    <x-adminlte-select2 id="icon" name="icon" label="Icon" fgroup-class="col-md-6 row" label-class="col-md-2 control-label"  igroup-class="col-md-10" igroup-size="md" class="icon_select" enable-old-support>
                        <option/>
                        @foreach ($icons as $icon)
                            <option value="{{ $icon->id }}" data-icon="{{ $icon->fullname }}"> {{ $icon->name }}</option>
                        @endforeach
                    </x-adminlte-select2>
                    <x-slot name="footerSlot">
                        <a href="{{ route('socials') }}">
                            <x-adminlte-button class="btn-flat" label="{{ __('adminlte::adminlte.back') }}" theme="default" icon="fas fa-chevron-left"/>
                        </a>
                        <x-adminlte-button class="btn-flat" type="submit" label="Submit" theme="success" icon="fas fa-lg fa-save"/>
                    </x-slot>
                </x-adminlte-card>
            </form>
        </div>
    </div>
@stop