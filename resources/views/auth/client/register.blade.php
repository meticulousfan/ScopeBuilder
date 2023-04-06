@extends('auth.layout.main')
@section('page-title')
    Register
@endsection

@section('page-caption')
    Register On Scope Builder
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
                    <li><a href="javascript:void(0);">Create An Account</a></li>
                </ul>
            </div>

            <div class="block-caption">
                <div class="bc-icon">
                    <img src="{{ asset('ui_assets/img/icons/user.svg') }}" alt="">
                </div>
                <h3 class="bc-title">Create An Account</h3>
                <p class="bc-subtitle">Hello there! Please create a new account to <br class="sm-hidden">continue
                    using the application.</p>
            </div>
            <form method="POST" action="{{ route('client.register') }}">
                @csrf
                <div class="block-form form">

                    <div class="form-fields">
                        <div class="form-field">
                            <div class="input-field">
                                <div class="field-label">Full Name</div>
                                <input id="name" type="text" class="@error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" autocomplete="name" autofocus>

                                @error('name')
                                    <span class="invalid-feedback" style="color: red;" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-field">
                            <div class="input-field">
                                <div class="field-label">Email Address</div>
                                <input id="email" type="email" class="@error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" autocomplete="email">

                                @error('email')
                                    <span class="invalid-feedback" style="color: red;" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-field">
                            <div class="input-field">
                                <div class="field-label">Password</div>
                                <input id="password" type="password" class="@error('password') is-invalid @enderror" name="password" autocomplete="new-password">

                                @error('password')
                                    <span class="invalid-feedback" style="color: red;" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-field">
                            <div class="input-field">
                                <div class="field-label">Confirm Password</div>
                                <input id="password-confirm" type="password" name="password_confirmation" autocomplete="new-password">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="block-footer">
                    <button class="btn" type="submit">Create An Account</button>
                </div>
            </form>
        </div>
    </div>
@endsection
