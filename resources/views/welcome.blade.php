@extends('welcome.partials.layout', ['author' => $data['author'], 'description' => $data['description']])

@section('content')
    <div>
        <ul class="nav">
            @if (Route::has('login'))
                <li>
                    @auth
                        <a href="{{ url('/home') }}" class="btn nav-link"><i class="fas fa-fw fa-home"></i> Admin</a>
                    @else
                        <a href="{{ route('login') }}" class="btn nav-link"><i class="fas fa-fw fa-sign-in-alt"></i> Log in</a>
                    @endauth
                </li>
            @endif
        </ul>
    </div>

    <div id="main-welcome-background">
        <div class="content">
            <div class="left">
                <div class="title">
                    {{ Str::upper(config('app.name')) }}
                </div>
                <div class="desc">
                    {{ $data['info'] }}
                </div>
                <div class="content-footer">
                    <a href="#" class="btn btn-link">Register Interest</a>
                    <a href="#" class="btn btn-link">Preview</a>
                </div>
            </div>
        </div>    
    </div>
    
@endsection

@section('footer')
    <div class="welcome-footer">
        <div class="footer-content">
            <strong>
                {{ date('Y') .' '.config('app.name') }}
            </strong> by <a href="https://mushmero.com" target="_blank">Mushmero</a>
            <br>
            <div class="text-xs">
                Version {{ Helper::appVer() }}
            </div>
        </div>
    </div>
@endsection