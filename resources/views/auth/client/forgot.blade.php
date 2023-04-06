@extends('auth.layout.main')
@section('page-title')
    Forgot Password
@endsection

@section('page-caption')
    Forgot Password To Scope Builder
@endsection

@section('content')
    <div class="auth-page">
        <div class="page-logo">
            <img src="{{ asset('ui_assets/img/logo.svg') }}" alt="Scopebuilder">
        </div>
        <div class="auth-block">
            <div class="page-nav">
                <ul>
                    <li><a href="{{ route('client.login.form') }}">Sign In</a></li>
                    <li><a href="{{ route('client.register.form') }}">Create An Account</a></li>
                </ul>
            </div>

            <div class="block-caption">
                <div class="bc-icon">
                    <img src="{{ asset('ui_assets/img/icons/user.svg') }}" alt="">
                </div>
                <h3 class="bc-title">Forgot Password</h3>
                <p class="bc-subtitle">Welcome back! Please enter your email address <br class="sm-hidden">to recover password.</p>
            </div>
            @if(session('error'))
                <div class="card" style="margin-top: 2rem; margin-bottom: 2rem;">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <div class="alert-body">
                                        <i style="top: 0px;" class="fas fa-exclamation-circle"></i>
                                        <span style="margin-left: 3px;">Oops! You've entered invalid data.
                                            Please try
                                            again.</span>
                                        <div style="margin-top: 5px; margin-left: 15px;">
                                            <i style="top: 0px;" class="fas fa-exclamation-circle"></i>
                                            <span style="margin-left: 3px; color: red;">
                                                {{ session('error') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            @if (session('success'))
                <div class="card" style="margin-top: 2rem; margin-bottom: 2rem;">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <div class="alert-body">
                                        <i style="top: 0px;" class="fas fa-exclamation-circle"></i>
                                        <span style="margin-left: 3px;">Congratulations!</span>
                                        <div style="margin-top: 5px; margin-left: 15px;">
                                            <i style="top: 0px;" class="fas fa-exclamation-circle"></i>
                                            <span style="margin-left: 3px; color: green;">
                                                {{ session('success') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

       
            <form method="POST" action="{{ route('client.forgot') }}">
                @csrf
                <div class="block-form form">

                    <div class="form-fields">
                        <div class="form-field">
                            <div class="input-field">
                                <div class="field-label">Email Address</div>
                                <a href="{{ route('client.login.form') }}" class="login-link" style="right: 16px; top: 16px; color: var(--blue-800); position: absolute;">Login</a>
                                <input id="email" type="email" class="@error('email') is-invalid @enderror" name="email"
                                    value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>

                </div>

                <div class="block-footer">
                    <button class="btn" type="submit">Recover</button>
                </div>
            </form>
        </div>
    </div>
@endsection
