@extends('auth.layout.main')
@section('page-title')
    Register
@endsection

@section('page-caption')
    Register On Scope Builder
@endsection

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <div class="auth-page">
        <div class="page-logo">
            <img src="{{ asset('ui_assets/img/logo.svg') }}" alt="Scopebuilder">
        </div>
        <div class="auth-block">
            <div class="page-nav">
                <ul>
                    <li><a href="{{ route('developer.login.form', ['waID'=>$waID]) }}">Sign In</a></li>
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
            <form method="POST" action="{{ route('developer.register') }}">
                @csrf
                <div class="block-form form">
                    <input type="hidden"  id="waID"  name="waID" value="{{$waID??''}}">

                    <div class="register">
                        <div class="form-field">
                            <div class="input-field">
                                <div class="field-label">User Name</div>
                                <input id="username" type="text" class="@error('username') is-invalid @enderror" name="username" value="{{ old('username') }}" autocomplete="username" autofocus>

                                @error('username')
                                    <span class="invalid-feedback" style="color: red;" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div style="height: 5px;"></div>

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
                        <div style="height: 5px;"></div>

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
                        <div style="height: 5px;"></div>
                        @if (count($skills))
                        <div class="form-field">
                            <div class="form-field input-field select-field">
                                <span>
                                    <label class="field-label">Skills</label>
                                    <select class="select2-multiple form-control"  id="skills" name="skills[]" multiple="multiple">
                                        @foreach ($skills as  $skill)
                                            <option value="{{ $skill->id }}">
                                                {{ $skill->name }}</option>
                                        @endforeach
                                    </select>
                                </span>
                            </div>
                        </div>
                        <div style="height: 5px;"></div>
                        @endif
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
                        <div style="height: 5px;"></div>

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
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
      <script>
        $(document).ready(function() {
            // Select2 Multiple
            $('.select2-multiple').select2({
                placeholder: "Select",
                allowClear: true
            });
            $("select").on("select2:selecting", function (e) {
                if ($(this).val() && $(this).val().length >= {{$maxNumberSkillsDevCanChoose}}) {
                    e.preventDefault();
                    alert("You cannot select more than {{$maxNumberSkillsDevCanChoose}} skills.");
                }
            });

        });

    </script>
@endsection
