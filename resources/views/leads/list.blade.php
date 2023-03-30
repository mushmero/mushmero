@extends('adminlte::page')

@section(config('adminlte.title'), 'AdminLTE')

@section('content_header')
    <h1 class="m-0 text-dark">New Request</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <x-adminlte-card title="Registration" theme="default" icon="fas fa-sm fa-user-plus" collapsible>
                @include('flash::message')
                <x-adminlte-datatable id="table1" :heads="$heads" :config="$config" head-theme="dark" striped hoverable compressed />
            </x-adminlte-card>
        </div>
    </div>
@stop

<script>
function switchAjax(id){
    var toggle = $('#activeSwitch' + id);
    $.ajax({
        url: '/leads/checkLinkExistById',
        data:{'id' : id},
        success: function(data){
            if(data.success){
                Swal.fire({
                    title: 'Are you sure to activate?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes',
                    cancelButtonText: 'No',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: 'POST',
                            url: 'leads/activateLink',
                            data: {
                                '_token': '{{ csrf_token() }}',
                                'activate': 1,
                                'id' : id
                                },
                            success: function() {
                                Swal.fire({
                                icon: 'success',
                                title: 'Link activated.'
                                });
                                toggle.next('.active_switch').text('Yes');
                            },
                        });
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        Swal.fire('Cancelled', 'Record not updated', 'error');
                        toggle.prop('checked',false);
                    }
                });
            }else if(data.error){
                for(var key in data){
                    Swal.fire(
                        key.charAt(0).toUpperCase()
                        + key.slice(1),
                        data[key],
                        key
                    );
                }
                toggle.prop('checked',false);
            }else if(data.activate){                
                Swal.fire({
                    title: 'Are you sure to deactivate?',
                    icon: 'warning',
                    text: 'If deactivate will lose the account and unable to access',
                    showCancelButton: true,
                    confirmButtonText: 'Yes',
                    cancelButtonText: 'No',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: 'POST',
                            url: 'leads/activateLink',
                            data: {
                                '_token': '{{ csrf_token() }}',
                                'activate': 0,
                                'id' : id
                                },
                            success: function() {
                                Swal.fire({
                                icon: 'success',
                                title: 'Link deactivated.'
                                });
                                toggle.next('.active_switch').text('No');
                            },
                        });
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        Swal.fire('Cancelled', 'Record not updated', 'error');
                        toggle.prop('checked',true);
                    }
                });
            }
        },
    });
}
</script>