@extends('adminlte::page')

@section(config('adminlte.title'), 'AdminLTE')

@section('content_header')
    <h1 class="m-0 text-dark">Social Settings</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <x-adminlte-card title="Template {{ $data->name }}" theme="default" icon="fas fa-sm fa-user-alt" collapsible>
                <div>
                    <iframe src="{{ route('settings_template.preview', $id) }}" width="100%" frameborder="0" onload="resizeIframe(this)" loading="lazy" style="border:1px solid rgb(0,0,0,0.2);"></iframe>
                </div>
                <x-slot name="footerSlot">
                    <a href="{{ route('settings_template') }}">
                        <x-adminlte-button class="btn-flat" label="{{ __('adminlte::adminlte.back') }}" theme="default" icon="fas fa-chevron-left"/>
                    </a>
                    <a href="#">
                        <button class="btn bg-gradient-teal"><i class="fas fa-fw fa-print"></i> Download</button>
                    </a>
                </x-slot>
            </x-adminlte-card>
        </div>
    </div>
@stop

@push('js')
<script>
    function resizeIframe(obj) {
        obj.style.height = (obj.contentWindow.document.documentElement.scrollHeight / parseFloat(2.5)) + 'px';
    }
</script>    
@endpush