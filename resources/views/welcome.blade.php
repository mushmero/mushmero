@extends('welcome.partials.layout', ['author' => $data['author'], 'description' => $data['description']])

@section('content')
    <div>
        <ul class="nav">
            @if (Route::has('login') && (config('app.is_demo') || env('APP_ENV') == 'local'))
                <li>
                    @auth
                        <a class="btn nav-link" href="{{ url('/home') }}"><i class="fas fa-fw fa-home"></i> Admin</a>
                    @else
                        <a class="btn nav-link" href="{{ route('login') }}"><i class="fas fa-fw fa-sign-in-alt"></i> Log in</a>
                    @endauth
                </li>
            @endif
        </ul>
    </div>

    {{-- <div id="main-welcome-background">
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
                    <button class="btn btn-link" id="interestTrigger" data-toggle="modal"
                        data-target="#interestModal">Register Interest</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="interestModal" aria-labelledby="interestModalLabel" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header bg-gray-dark">
                    <h5 class="modal-title" id="interestModalLabel">Register Interest</h5>
                    <button class="close" data-dismiss="modal" type="button" aria-label="Close">
                        <i class="fas fa-fw fa-times"></i>
                    </button>
                </div>
                <form class="form needs-validation" id="interestForm" action="{{ route('welcome.contact.store') }}"
                    method="post" novalidate>
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <label for="name">Name</label>
                                <input class="form-control" id="name" name="name" type="text" required />
                                <div class="invalid-feedback" role="alert">
                                    <strong> Please provide a valid name.</strong>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <label for="email">Email</label>
                                <input class="form-control" id="email" name="email" type="email" required />
                                <div class="invalid-feedback" role="alert">
                                    <strong> Please provide a valid email.</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-success" type="submit">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div> --}}
@endsection

@section('footer')
    <div class="welcome-footer">
        <div class="footer-content">
            <strong>
                {{ date('Y') . ' ' . config('app.name') }}
            </strong> by <a href="https://mushmero.com" target="_blank">Mushmero</a>
            <br>
            <div class="text-xs">
                Version {{ Helper::appVer() }}
            </div>
        </div>
    </div>
@endsection
