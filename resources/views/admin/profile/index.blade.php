@extends('admin.layout.main')
@section('page-title')
    Profile
@endsection

@section('page-caption')
    My Profile
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
                    <h1 class="page-caption">My Profile</h1>
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
                                <li><a href="{{ route('admin.profile') }}">Account Settings</a></li>
                                <li><a href="{{ route('admin.security.settings') }}">Security Settings</a></li>
                                {{-- <li><a href="{{ route('admin.payment.settings') }}">Payment Settings</a></li> --}}
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
                        <form action="{{ route('admin.profile.update') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="page-tab">
                                <div class="tab-content">
                                    <div class="column">
                                        <div class="column-section">
                                            <div class="section-caption">
                                                <h2 class="sc-title">Profile Picture</h2>
                                            </div>
                                            <div class="select-avatar-block">
                                                <div class="block-image">
                                                    <img src="{{ !empty(Auth::user()->getFirstMediaUrl('avatar', 'thumb')) ? Auth::user()->getFirstMediaUrl('avatar', 'thumb') : asset('ui_assets/img/content-images/user-avatar.jpg') }}"
                                                        alt="{{ Auth::user()->name }}">
                                                </div>
                                                <label class="block-button" for="profile-image" style="cursor: pointer;"
                                                    aria-label="Choose image">
                                                    <svg class="btn-icon">
                                                        <use
                                                            xlink:href="{{ asset('ui_assets/img/icons-sprite.svg#camera') }}">
                                                        </use>
                                                    </svg>
                                                </label>
                                                <input type="file" name="image" id="profile-image" style="display: none;">
                                            </div>
                                            @error('image')
                                                <span class="invalid-feedback" style="color: red;" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="column-section">
                                            <div class="section-caption">
                                                <h2 class="sc-title">Account Information</h2>
                                            </div>

                                            <div class="settings-form form">
                                                <div class="form-fields">
                                                    <div class="form-field">
                                                        <div class="input-field">
                                                            <div class="field-label">Full Name</div>
                                                            <input type="text" class="@error('name') is-invalid @enderror"
                                                                value="{{ Auth::user()->name }}" name="name">
                                                        </div>
                                                        @error('name')
                                                            <span class="invalid-feedback" style="color: red;" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                    <div class="form-field">
                                                        <div class="input-field">
                                                            <div class="field-label">Email Address</div>
                                                            <input type="email" disabled
                                                                value="{{ Auth::user()->email }}" name="email">
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
