
<div class="alert alert-{{ $getClass() }} {{ $isDismissable() ? 'alert-dismissible' : '' }}" role="alert">
    @if ($shouldShowIcon())
    {!! $getIcon() !!}
    @endif
    @if ($shouldShowTitle())
    <strong>{{ $getTitle() }}: </strong>
    @endif
    {!! $getMessage() !!}
    @if ($isDismissable())
    <a data-dismiss="alert" aria-label="Close"><i class="fas fa-times"></i></a>
    @endif
</div>
