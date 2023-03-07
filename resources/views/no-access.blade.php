@extends('adminlte::page')

@section(config('adminlte.title'), 'AdminLTE')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <section class="lockscreen-wrapper">
                <div class="error-page">
                    <h2><i class="fas fa-exclamation-triangle text-warning"></i> No active persona. Please activate one.</h2>
                </div>
            </section>
        </div>
    </div>
@stop