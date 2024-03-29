@extends('auth.layout.main')
@section('page-title')
    Log In
@endsection

@section('page-caption')
    Login To Scope Builder
@endsection

@section('content')
    <div class="auth-page">
        <div class="page-logo">
            <img src="{{ asset('ui_assets/img/logo.svg') }}" alt="Scopebuilder">
        </div>
        <div class="auth-block">
            <div class="page-nav">
                <ul>
                    <li><a href="javascript:void(0);">Sign In</a></li>
                    <li><a href="{{ route('developer.register.form', ['waID' => $waID]) }}">Create An Account</a></li>
                </ul>
            </div>

            <div class="block-caption">
                <div class="bc-icon">
                    <img src="{{ asset('ui_assets/img/icons/user.svg') }}" alt="">
                </div>
                <h3 class="bc-title">Sign In</h3>
                <p class="bc-subtitle">Welcome back! Please enter your credentials <br class="sm-hidden">to sign in to
                    your account.</p>
            </div>
            @if (session('error'))
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
            @if ($errors->any())
                <div class="card" style="margin-top: 2rem; margin-bottom: 2rem;">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                @if ($errors->any())
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        <div class="alert-body">
                                            <i style="top: 0px;" class="fas fa-exclamation-circle"></i>
                                            <span style="margin-left: 3px;">Oops! You've entered invalid data.
                                                Please
                                                try again.</span>
                                            @foreach ($errors->all() as $error)
                                                <div style="margin-top: 5px; margin-left: 15px;">
                                                    <i style="top: 0px;" class="fas fa-exclamation-circle"></i>
                                                    <span style="margin-left: 3px;"> {{ $error }} </span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            <form method="POST" action="{{ route('developer.login') }}">
                @csrf
                <div class="block-form form">
                    <input type="hidden" id="waID" name="waID" value="{{ $waID ?? '' }}">

                    <div class="form-fields">
                        <div class="form-field">
                            <div class="input-field">
                                <div class="field-label">Email Address</div>
                                <input id="email" type="email" class="@error('email') is-invalid @enderror"
                                    name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-field">
                            <div class="input-field password-field">
                                <div class="field-label">Password</div>
                                <a href="{{ route('developer.forgot.form') }}" class="forgot-link">Forgot Password?</a>
                                <input id="password" type="password" class="@error('password') is-invalid @enderror"
                                    name="password" required autocomplete="current-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>

                </div>

                <div class="block-footer">
                    <button class="btn" type="submit">Sign In</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('ui.script')
    <script type="text/javascript">
        if($('#waID').val()) {
            localStorage.setItem("waID", $('#waID').val());
        }else if(localStorage.getItem("waID")){
            $('#waID').val(localStorage.getItem("waID"));
        }
    </script>
@endpush
