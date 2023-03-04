@extends('adminlte::page')

@section(config('adminlte.title'), 'AdminLTE')

@section('content_header')
    <h1 class="m-0 text-dark">Skills</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <x-flashmessages-container />
            <form class="form" action="{{ route('skills.store') }}" method="post">
                @csrf
                <x-adminlte-card title="Create Skill" theme="default" icon="fas fa-sm fa-rocket" collapsible>
                    <x-adminlte-input name="name" label="Skill Name" fgroup-class="col-md-6 row" label-class="col-md-2 control-label" igroup-class="col-md-10" required enable-old-support/>
                    <x-adminlte-input name="percentage" label="Percentage" fgroup-class="col-md-6 row" label-class="col-md-2 control-label" igroup-class="col-md-10" placeholder="Percentage" type="number" required enable-old-support igroup-size="md" min=1 max=100>
                        <x-slot name="appendSlot">
                            <div class="input-group-text bg-dark">
                                <i class="fas fa-percent"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input>
                    <x-adminlte-select2 id="icon" name="icon" label="Icon" fgroup-class="col-md-6 row" label-class="col-md-2 control-label"  igroup-class="col-md-10" igroup-size="md" class="icon_select" enable-old-support>
                        <option/>
                        @foreach ($icons as $icon)
                            <option value="{{ $icon->id }}" data-icon="{{ $icon->fullname }}"> {{ $icon->name }}</option>
                        @endforeach
                    </x-adminlte-select2>
                    <x-slot name="footerSlot">
                        <a href="{{ route('skills') }}">
                            <x-adminlte-button class="btn-flat" label="{{ __('adminlte::adminlte.back') }}" theme="default" icon="fas fa-chevron-left"/>
                        </a>
                        <x-adminlte-button class="btn-flat" type="submit" label="Submit" theme="success" icon="fas fa-lg fa-save"/>
                    </x-slot>
                </x-adminlte-card>
            </form>
        </div>
    </div>
@stop