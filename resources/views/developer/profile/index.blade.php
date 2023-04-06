@extends('developer.layout.main')
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
                        <form action="{{ route('developer.profile.update') }}" method="POST" enctype="multipart/form-data">
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
                                                <input type="file" name="image" id="profile-image"
                                                    style="display: none;">
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
                                                            <input type="text"
                                                                class="@error('name') is-invalid @enderror"
                                                                value="{{ $user->name }}" name="name">
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
                                                                value="{{ $user->email }}" name="email">
                                                        </div>
                                                    </div>

                                                    <div class="form-field">
                                                        <div class="form-field input-field select-field">
                                                            <span>
                                                                <label class="field-label">Country</label>
                                                                <select id="country_id" name="country_id">
                                                                    @php
                                                                        $ctlist = [];
                                                                        $ctquery = DB::table('countries')
                                                                            ->where(['country_status' => '1'])
                                                                            ->get();
                                                                        foreach ($ctquery as $rs) {
                                                                            $ctlist[$rs->country_id] = $rs->country_name;
                                                                        }
                                                                    @endphp
                                                                    @foreach ($ctlist as $key => $val)
                                                                        <option value="{{ $key }}"
                                                                            @if ($key == $user->country_id) selected @endif>
                                                                            {{ $val }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </span>
                                                        </div>
                                                    </div>

                                                    <div class="form-field">
                                                        <div class="form-field input-field select-field">
                                                            <span>
                                                                <label class="field-label">Timezone</label>
                                                                <select id="timezone" name="timezone">
                                                                    @php
                                                                        $tzlist = timezone_identifiers_list();
                                                                        $selected_tz = 'America/New_York';
                                                                        if (!empty(Auth::user()->timezone)) {
                                                                            $selected_tz = Auth::user()->timezone;
                                                                        }
                                                                    @endphp
                                                                    @foreach ($tzlist as $val)
                                                                        <option value="{{ $val }}"
                                                                            @if ($val == $selected_tz) selected @endif>
                                                                            {{ $val }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </span>
                                                        </div>
                                                    </div>

                                                    <div class="form-field">
                                                        <div class="input-field">
                                                            <div class="field-label">Company</div>
                                                            <input type="text" value="{{ Auth::user()->company }}"
                                                                name="company">
                                                        </div>
                                                    </div>

                                                    <div class="form-field">
                                                        <div class="form-field input-field select-field">
                                                            <span>
                                                                <label class="field-label">Language</label>
                                                                <select id="language" name="language">
                                                                    @php
                                                                        $lang_arr = [
                                                                            'en' => 'English',
                                                                            'de' => 'German',
                                                                            'ru' => 'Russian',
                                                                            'zh' => 'Chinese',
                                                                        ];
                                                                        $selected_lang = 'en';
                                                                        if (!empty(Auth::user()->language)) {
                                                                            $selected_lang = Auth::user()->language;
                                                                        }
                                                                    @endphp
                                                                    @foreach ($lang_arr as $key => $val)
                                                                        <option value="{{ $key }}"
                                                                            @if ($key == $selected_lang) selected @endif>
                                                                            {{ $val }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="form-field">
                                                        <div class="form-field input-field select-field">
                                                            <span>
                                                                <label class="field-label">Skills</label>
                                                                <select class="select2-multiple form-control"  id="skills" name="skills[]" multiple="multiple">
                                                                    @foreach ($skills as  $skill)
                                                                        <option value="{{ $skill->id }}"
                                                                            @if ( is_array(json_decode($user->skills)) && in_array($skill->id, json_decode($user->skills))  ) selected @endif>
                                                                            {{ $skill->name }}</option>
                                                                    @endforeach
                                                                </select>         
                                                            </span>
                                                            
                                                        </div>
                                                    </div>
                                                    @if($skillSubmission)
                                                    <div class="form-field" style="margin: 5px 30px;">
                                                        <label class="field-label">Not seeing your skill? <a href="javascript:void(0);" data-modal="#new-skill-modal">Suggest Here</a></label>
                                                    </div>
                                                    @endif
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
    
    <div class="modal" id="new-skill-modal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <button class="modal-close" aria-label="Close modal"></button>
                <form action="{{ route('developer.skill.suggest') }}" method="POST">
                    @csrf                    
                    <div class="modal-caption">
                        <h3 class="mc-title"><strong>Suggest Skill</strong></h3>
                    </div>

                    <div class="form">
                        <div class="input-field setting-form-field">
                            <div class="field-label">Skill Name</div>
                            <input type="text" id="skill_name" name="skill_name" placeholder="Enter Skill Name" required>
                        </div>
                    </div>

                    <div class="modal-footer" style="flex-wrap: inherit; padding:0px;border:0px;">
                        <button class="btn btn-light js-modal-close"
                            style="padding: 10px 20px;min-height: 54px;border: 1px solid var(--btn-bg); border-radius: var(--r);background: var(--btn-bg);color: var(--btn-color);">Cancel</button>
                        <button class="btn" type="submit"
                            style="padding: 10px 20px;min-height: 54px;border: 1px solid var(--btn-bg); border-radius: var(--r);background: var(--btn-bg);color: var(--btn-color);">Suggest</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
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
