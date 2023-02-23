@extends('adminlte::page')

@section(config('adminlte.title'), 'AdminLTE')

@section('content_header')
    <h1 class="m-0 text-dark">Languages</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <x-flashmessages-container />
            <form class="form" action="{{ route('languages.store') }}" method="post">
                @csrf
                <x-adminlte-card title="Create Language" theme="default" icon="fas fa-sm fa-language" collapsible>
                    <x-adminlte-input name="name" label="Language" fgroup-class="col-md-6 row" label-class="col-md-2 control-label" igroup-class="col-md-10" required enable-old-support/>
                    <x-adminlte-select2 id="proficiency" name="proficiency" label="Proficiency" fgroup-class="col-md-6 row" label-class="col-md-2 control-label"  igroup-class="col-md-10" igroup-size="md" :config="$configProficiency" enable-old-support>
                        <option/>
                        @foreach ($proficiency as $pf)
                            <option value="{{ $pf->id }}">{{ $pf->proficiency }}</option>
                        @endforeach
                    </x-adminlte-select2>
                    <x-slot name="footerSlot">
                        <a href="{{ route('languages') }}">
                            <x-adminlte-button class="btn-flat" label="{{ __('adminlte::adminlte.back') }}" theme="default" icon="fas fa-chevron-left"/>
                        </a>
                        <x-adminlte-button class="btn-flat" type="submit" label="Submit" theme="success" icon="fas fa-lg fa-save"/>
                    </x-slot>
                </x-adminlte-card>
            </form>
        </div>
    </div>
@stop