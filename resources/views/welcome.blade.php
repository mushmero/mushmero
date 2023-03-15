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
        <div class="flash-message">
            @include('flash::message')
        </div>
        <div class="content">
            <div class="left">
                <div class="title">
                    {{ Str::upper(config('app.name')) }}
                </div>
                <div class="desc">
                    {{ $data['info'] }}
                </div>
                <div class="content-footer">
                    <button id="interestTrigger" class="btn btn-link" data-toggle="modal" data-target="#interestModal">Register Interest</button>
                </div>
            </div>
        </div>    
    </div>
    <div class="modal fade" id="interestModal" tabindex="-1" aria-labelledby="interestModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header bg-gray-dark">
                    <h5 class="modal-title" id="interestModalLabel">Register Interest</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i class="fas fa-fw fa-times"></i>
                    </button>
                </div>
                <form id="interestForm" class="form needs-validation" action="{{ route('welcome.contact.store') }}" method="post" novalidate>
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <label for="name">Name</label>
                                <input name="name" id="name" type="text" class="form-control" required />
                                <div class="invalid-feedback" role="alert">
                                    <strong> Please provide a valid name.</strong>                                
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <label for="email">Email</label>
                                <input name="email" id="email" type="email" class="form-control" required />
                                <div class="invalid-feedback" role="alert">
                                    <strong> Please provide a valid email.</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Submit</button>
                    </div>
                </form>
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