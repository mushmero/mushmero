@extends('adminlte::page')

@section(config('adminlte.title'), 'AdminLTE')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <section class="lockscreen-wrapper">
                <div class="error-page">
                    @include('errors.inactive')
                </div>
            </section>
        </div>
    </div>
@stop