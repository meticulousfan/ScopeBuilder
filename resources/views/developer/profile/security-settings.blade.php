@extends('developer.layout.main')
@section('page-title')
    Profile
@endsection

@section('page-caption')
    Security Settings
@endsection

@section('content')
    <header class="header">
        <div class="container">
            <div class="header-inner">
                <div class="header-block with-menu-opener">
                    <button class="menu-opener lg-visible-flex" aria-label="Show navigation">
                        <span class="bar"></span>
                        <span class="bar"></span>
                        <span class="bar"></span>
                    </button>
                    <h1 class="page-caption">Security Settings</h1>
                </div>
            </div>
        </div>
    </header>


    <main class="page-main">
        <div class="page-content">
            <section class="default-section with-footer">
                <div class="container">
                    <div class="section-inner">
                        <div class="page-nav">
                            <ul>
                                <li><a href="{{ route('developer.profile') }}">Account Settings</a></li>
                                <li><a href="{{ route('developer.security.settings') }}">Security Settings</a></li>
                                <li><a href="{{ route('developer.payment.settings') }}">Payment Settings</a></li>
                            </ul>
                        </div>
                        @if (session('success'))
                            <div class="card" style="margin-top: 2rem; margin-bottom: 3px;">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="alert alert-success" role="alert">
                                                {{ session('success') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="card" style="margin-top: 2rem; margin-bottom: 3px;">
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
                        @if ($errors->any())
                            <div class="card" style="margin-top: 2rem; margin-bottom: 3px;">
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
                        <!-- form -->
                        <form action="{{ route('developer.profile.update.password') }}" method="POST">
                            @csrf
                            <div class="page-tab">
                                <div class="section-caption">
                                    <h2 class="sc-title">Password</h2>
                                </div>

                                <div class="tab-content">
                                    <div class="column">
                                        <div class="column-section">
                                            <div class="settings-form form">
                                                <div class="form-fields">
                                                    <div class="form-field">
                                                        <div class="input-field">
                                                            <div class="field-label">Current Password</div>
                                                            <input type="password" value="" name="password">
                                                        </div>
                                                    </div>

                                                    <div class="form-field">
                                                        <div class="input-field">
                                                            <div class="field-label">New Password</div>
                                                            <input type="password" value="" name="new_password">
                                                        </div>
                                                    </div>

                                                    <div class="form-field">
                                                        <div class="input-field">
                                                            <div class="field-label">Confirm Password</div>
                                                            <input type="password" value="" name="confirm_new_password">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="section-footer single-item">
                                <div class="footer-block">
                                    <button class="btn" type="submit">Save Changes</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </section>
        </div>
    </main>
@endsection
