@extends('admin.layout.main')
@section('page-title')
    Settings
@endsection

@section('page-caption')
    Calls Settings
@endsection

@section('content')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css"
        integrity="sha256-DF7Zhf293AJxJNTmh5zhoYYIMs2oXitRfBjY+9L//AY=" crossorigin="anonymous">
    <header class="header">
        <div class="container">
            <div class="header-inner">
                <div class="header-block with-menu-opener">
                    <button class="menu-opener lg-visible-flex" aria-label="Show navigation">
                        <span class="bar"></span>
                        <span class="bar"></span>
                        <span class="bar"></span>
                    </button>
                    <h1 class="page-caption">Calls Settings</h1>
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
                                <li><a href="{{ route('admin.settings.index') }}" style="text-decoration: none;">General
                                        Settings</a></li>
                                <li><a href="{{ route('admin.settings.calls') }}"
                                        style="text-decoration: none; color: #1890FF;">Calls
                                        Settings</a></li>
                                <li><a href="{{ route('admin.settings.seo') }}" style="text-decoration: none;">SEO</a></li>
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
                        <form action="{{ route('admin.settings.index.submit') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="page-tab">
                                <div class="tab-content" style="display: block;">
                                    <div class="column">
                                        <div class="column-section">
                                            <div class="form">

                                                <div class="section-caption" style="mmargin: 30px 0 20px 0;">
                                                    <h2 class="sc-title">General</h2>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="input-field setting-form-field">
                                                            <div class="field-label">Calls</div>
                                                            <label class="toggle" style="display: flex">
                                                                <input type="text" class="calls"
                                                                    name="data[settings][calls]"
                                                                    value="{{ isset($settings['calls']) && $settings['calls'] ? 'Enabled' : 'Disabled' }}"
                                                                    readonly>
                                                                <label class="switch">
                                                                    <input class="toggle-class" type="checkbox"
                                                                        data-name=".calls" data-onstyle="success"
                                                                        data-offstyle="danger" data-toggle="toggle"
                                                                        data-on="Active" data-off="InActive"
                                                                        {{ isset($settings['calls']) && $settings['calls'] ? 'checked' : '' }}>
                                                                    <span class="slider round"></span>
                                                                </label>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <!-- <div class="col-md-4">
                                                            <div class="input-field setting-form-field">
                                                                <div class="field-label">Minimum Call Duration in Minutes</div>
                                                                <input type="number" name="data[settings][minimumCallDuration]"
                                                                    value="{{ $settings['minimumCallDuration'] ?? '1' }}"
                                                                    min="1" max="100" step="1" required>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-4">
                                                            <div class="input-field setting-form-field">
                                                                <div class="field-label">Maximum Call Duration in Minutes</div>
                                                                <input type="number"
                                                                    name="data[settings][maximumCallDuration]"
                                                                    value="{{ $settings['maximumCallDuration'] ?? '1' }}"
                                                                    min="1" max="10000" step="1" required>
                                                            </div>
                                                        </div> -->

                                                    <div class="col-md-4">
                                                        <div class="input-field setting-form-field">
                                                            <div class="field-label">Minutes Before Start Charging Client
                                                            </div>
                                                            <input type="number"
                                                                name="data[settings][timeBeforeChargingClientInMinutes]"
                                                                value="{{ $settings['timeBeforeChargingClientInMinutes'] ?? '1' }}"
                                                                min="1" max="100" step="1" required>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <div class="input-field setting-form-field">
                                                            <div class="field-label">Call Duration Select Step</div>
                                                            <input type="number"
                                                                name="data[settings][callDurationSeleteStep]"
                                                                value="{{ $settings['callDurationSeleteStep'] ?? '1' }}"
                                                                min="1" max="100" step="1" required>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <div class="input-field setting-form-field">
                                                            <div class="field-label">Price Per Minute</div>
                                                            <input type="number"
                                                                name="data[settings][pricePerCallMinute]"
                                                                value="{{ $settings['pricePerCallMinute'] ?? '0.00' }}"
                                                                min="0" max="100.00" step="0.01" required>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <div class="input-field setting-form-field">
                                                            <div class="field-label">Extend Duration</div>
                                                            <label class="toggle" style="display: flex">
                                                                <input type="text" class="extendDuration"
                                                                    name="data[settings][extendDuration]"
                                                                    value="{{ isset($settings['extendDuration']) && $settings['extendDuration'] ? 'Enabled' : 'Disabled' }}"
                                                                    readonly>
                                                                <label class="switch">
                                                                    <input class="toggle-class" type="checkbox"
                                                                        data-name=".extendDuration" data-onstyle="success"
                                                                        data-offstyle="danger" data-toggle="toggle"
                                                                        data-on="Active" data-off="InActive"
                                                                        {{ isset($settings['extendDuration']) && $settings['extendDuration'] ? 'checked' : '' }}>
                                                                    <span class="slider round"></span>
                                                                </label>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="input-field setting-form-field">
                                                            <div class="field-label">Max Number of Available Devs for Call
                                                            </div>
                                                            <input type="number"
                                                                name="data[settings][numberOfDevsAvailable]"
                                                                value="{{ $settings['numberOfDevsAvailable'] ?? '1' }}"
                                                                min="1" max="1000" step="1" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="input-field setting-form-field"
                                                            style="height: 83px !important;">
                                                            <div class="field-label">Alert Sound</div>
                                                            <div style="display: flex;justify-content: space-between;">
                                                                <div id="selectFileName"
                                                                    style="    display: block;
                                                            width: 200px;
                                                            overflow: hidden;
                                                            white-space: nowrap;
                                                            text-overflow: ellipsis;">
                                                                    {{ $settings['alertSound'] ? (strlen($settings['alertSound']) < 8 ? $settings['alertSound'] : substr($settings['alertSound'], 0, 8) . '...') : 'No file' }}
                                                                </div>
                                                                <div id="selectFileBtn"
                                                                    style="    width: 90px;
                                                            height: 26px;
                                                            margin: 1px 16px;
                                                            background: #1890FF 0% 0% no-repeat padding-box;
                                                            border-radius: 10px;
                                                            opacity: 1;
                                                            text-align: center;
                                                            align-items: center;
                                                            display: grid;
                                                            color: white;
                                                            font-size: 12px;">
                                                                    {{ $settings['alertSound'] == '' ? 'Select' : 'Update ' }}
                                                                    File</div>
                                                            </div>
                                                            <input type="file" id="alertSound" class="form-control"
                                                                name="upload_file" accept=".mp3, .ogg, .wav" multiple
                                                                hidden>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="section-caption" style="margin: 30px 0 20px 0;">
                                                    <h2 class="sc-title">Default Create Settings</h2>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="input-field setting-form-field">
                                                            <div class="field-label">Always Record</div>
                                                            <label class="toggle" style="display: flex">
                                                                <input type="text" class="record"
                                                                    name="data[bbb_default_create_settings][record]"
                                                                    value="{{ isset($bbb_default_create_settings['record']) && $bbb_default_create_settings['record'] ? 'Enabled' : 'Disabled' }}"
                                                                    readonly>
                                                                <label class="switch">
                                                                    <input class="toggle-class" type="checkbox"
                                                                        data-name=".record" data-onstyle="success"
                                                                        data-offstyle="danger" data-toggle="toggle"
                                                                        data-on="Active" data-off="InActive"
                                                                        {{ isset($bbb_default_create_settings['record']) && $bbb_default_create_settings['record'] ? 'checked' : '' }}>
                                                                    <span class="slider round"></span>
                                                                </label>
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <div class="input-field setting-form-field">
                                                            <div class="field-label">Auto Start Stop Recording</div>
                                                            <label class="toggle" style="display: flex">
                                                                <input type="text" class="autoStartRecording"
                                                                    name="data[bbb_default_create_settings][autoStartRecording]"
                                                                    value="{{ isset($bbb_default_create_settings['autoStartRecording']) && $bbb_default_create_settings['autoStartRecording'] ? 'Enabled' : 'Disabled' }}"
                                                                    readonly>
                                                                <label class="switch">
                                                                    <input class="toggle-class" type="checkbox"
                                                                        data-name=".autoStartRecording"
                                                                        data-onstyle="success" data-offstyle="danger"
                                                                        data-toggle="toggle" data-on="Active"
                                                                        data-off="InActive"
                                                                        {{ isset($bbb_default_create_settings['autoStartRecording']) && $bbb_default_create_settings['autoStartRecording'] ? 'checked' : '' }}>
                                                                    <span class="slider round"></span>
                                                                </label>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="input-field setting-form-field">
                                                            <div class="field-label">Allow Start Stop Recording</div>
                                                            <label class="toggle" style="display: flex">
                                                                <input type="text" class="allowStartStopRecording"
                                                                    name="data[bbb_default_create_settings][allowStartStopRecording]"
                                                                    value="{{ isset($bbb_default_create_settings['allowStartStopRecording']) && $bbb_default_create_settings['allowStartStopRecording'] ? 'Enabled' : 'Disabled' }}"
                                                                    readonly>
                                                                <label class="switch">
                                                                    <input class="toggle-class" type="checkbox"
                                                                        data-name=".allowStartStopRecording"
                                                                        data-onstyle="success" data-offstyle="danger"
                                                                        data-toggle="toggle" data-on="Active"
                                                                        data-off="InActive"
                                                                        {{ isset($bbb_default_create_settings['allowStartStopRecording']) && $bbb_default_create_settings['allowStartStopRecording'] ? 'checked' : '' }}>
                                                                    <span class="slider round"></span>
                                                                </label>
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <div class="input-field setting-form-field">
                                                            <div class="field-label">Auto End Meeting if No Dev</div>
                                                            <label class="toggle" style="display: flex">
                                                                <input type="text" class="endWhenNoModerator"
                                                                    name="data[bbb_default_create_settings][endWhenNoModerator]"
                                                                    value="{{ isset($bbb_default_create_settings['endWhenNoModerator']) && $bbb_default_create_settings['endWhenNoModerator'] ? 'Enabled' : 'Disabled' }}"
                                                                    readonly>
                                                                <label class="switch">
                                                                    <input class="toggle-class" type="checkbox"
                                                                        data-name=".endWhenNoModerator"
                                                                        data-onstyle="success" data-offstyle="danger"
                                                                        data-toggle="toggle" data-on="Active"
                                                                        data-off="InActive"
                                                                        {{ isset($bbb_default_create_settings['endWhenNoModerator']) && $bbb_default_create_settings['endWhenNoModerator'] ? 'checked' : '' }}>
                                                                    <span class="slider round"></span>
                                                                </label>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="input-field setting-form-field">
                                                            <div class="field-label">Minutes to End Meeting if No Dev
                                                            </div>
                                                            <input type="number"
                                                                name="data[bbb_default_create_settings][endWhenNoModeratorDelayInMinutes]"
                                                                value="{{ $bbb_default_create_settings['endWhenNoModeratorDelayInMinutes'] ?? '1' }}"
                                                                min="1" max="100" step="1" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="input-field setting-form-field">
                                                            <div class="field-label">Max Participants
                                                            </div>
                                                            <input type="number"
                                                                name="data[bbb_default_create_settings][maxParticipants]"
                                                                value="{{ $bbb_default_create_settings['maxParticipants'] ?? '1' }}"
                                                                min="1" max="10" step="1" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="input-field setting-form-field">
                                                            <div class="field-label">Banner Color</div>
                                                            <label class="toggle" style="display: flex">
                                                                <input type="text" class="bannerColor"
                                                                    value="{{ $bbb_default_create_settings['bannerColor'] ?? '#1890FF' }}"
                                                                    readonly>
                                                                <input type="color" class="color-class"
                                                                    name="data[bbb_default_create_settings][bannerColor]"
                                                                    value="{{ $bbb_default_create_settings['bannerColor'] ?? '#1890FF' }}">
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <div class="input-field setting-form-field">
                                                            <div class="field-label">Virtual Backgrounds</div>
                                                            <label class="toggle" style="display: flex">
                                                                <input type="text" class="virtualBackgroundsDisabled"
                                                                    name="data[bbb_default_create_settings][virtualBackgroundsDisabled]"
                                                                    value="{{ isset($bbb_default_create_settings['virtualBackgroundsDisabled']) && $bbb_default_create_settings['virtualBackgroundsDisabled'] ? 'Enabled' : 'Disabled' }}"
                                                                    readonly>
                                                                <label class="switch">
                                                                    <input class="toggle-class" type="checkbox"
                                                                        data-name=".virtualBackgroundsDisabled"
                                                                        data-onstyle="success" data-offstyle="danger"
                                                                        data-toggle="toggle" data-on="Active"
                                                                        data-off="InActive"
                                                                        {{ isset($bbb_default_create_settings['virtualBackgroundsDisabled']) && $bbb_default_create_settings['virtualBackgroundsDisabled'] ? 'checked' : '' }}>
                                                                    <span class="slider round"></span>
                                                                </label>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="input-field setting-form-field">
                                                            <div class="field-label">Enable Learning Dashboard</div>
                                                            <label class="toggle" style="display: flex">
                                                                <input type="text" class="learningDashboardEnabled"
                                                                    name="data[bbb_default_create_settings][learningDashboardEnabled]"
                                                                    value="{{ isset($bbb_default_create_settings['learningDashboardEnabled']) && $bbb_default_create_settings['learningDashboardEnabled'] ? 'Enabled' : 'Disabled' }}"
                                                                    readonly>
                                                                <label class="switch">
                                                                    <input class="toggle-class" type="checkbox"
                                                                        data-name=".learningDashboardEnabled"
                                                                        data-onstyle="success" data-offstyle="danger"
                                                                        data-toggle="toggle" data-on="Active"
                                                                        data-off="InActive"
                                                                        {{ isset($bbb_default_create_settings['learningDashboardEnabled']) && $bbb_default_create_settings['learningDashboardEnabled'] ? 'checked' : '' }}>
                                                                    <span class="slider round"></span>
                                                                </label>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="input-field setting-form-field">
                                                            <div class="field-label">Banner Text
                                                            </div>
                                                            <textarea name="data[bbb_default_create_settings][bannerText]" rows="3">{{ $bbb_default_create_settings['bannerText'] ?? '' }}
                                                            </textarea>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="input-field setting-form-field">
                                                            <div class="field-label">Moderator Message
                                                            </div>
                                                            <textarea name="data[bbb_default_create_settings][moderatorOnlyMessage]" min="0" max="255"
                                                                rows="3">{{ $bbb_default_create_settings['moderatorOnlyMessage'] ?? '' }}
                                                            </textarea>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="input-field setting-form-field">
                                                            <div class="field-label">Disabled Features</div>
                                                            <textarea class="form-control border-0" rows="3" name="data[bbb_default_create_settings][disabledFeatures]"
                                                                id="Disabled Features">{{ $bbb_default_create_settings['disabledFeatures'] ?? '' }}</textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="section-caption" style="margin: 30px 0 20px 0;">
                                                    <h2 class="sc-title">Default Join Settings</h2>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="input-field setting-form-field">
                                                            <div class="field-label">Auto Initiate Microphone Join</div>
                                                            <label class="toggle" style="display: flex">
                                                                <input type="text" class="userdata_bbb_auto_join_audio"
                                                                    name="data[bbb_default_join_settings][userdata_bbb_auto_join_audio]"
                                                                    value="{{ isset($bbb_default_join_settings['userdata_bbb_auto_join_audio']) && $bbb_default_join_settings['userdata_bbb_auto_join_audio'] ? 'Enabled' : 'Disabled' }}"
                                                                    readonly>
                                                                <label class="switch">
                                                                    <input class="toggle-class" type="checkbox"
                                                                        data-name=".userdata_bbb_auto_join_audio"
                                                                        data-onstyle="success" data-offstyle="danger"
                                                                        data-toggle="toggle" data-on="Active"
                                                                        data-off="InActive"
                                                                        {{ isset($bbb_default_join_settings['userdata_bbb_auto_join_audio']) && $bbb_default_join_settings['userdata_bbb_auto_join_audio'] ? 'checked' : '' }}>
                                                                    <span class="slider round"></span>
                                                                </label>
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <div class="input-field setting-form-field">
                                                            <div class="field-label">Auto Initiate Webcam Join</div>
                                                            <label class="toggle" style="display: flex">
                                                                <input type="text"
                                                                    class="userdata_bbb_auto_share_webcam"
                                                                    name="data[bbb_default_join_settings][userdata_bbb_auto_share_webcam]"
                                                                    value="{{ isset($bbb_default_join_settings['userdata_bbb_auto_share_webcam']) && $bbb_default_join_settings['userdata_bbb_auto_share_webcam'] ? 'Enabled' : 'Disabled' }}"
                                                                    readonly>
                                                                <label class="switch">
                                                                    <input class="toggle-class" type="checkbox"
                                                                        data-name=".userdata_bbb_auto_share_webcam"
                                                                        data-onstyle="success" data-offstyle="danger"
                                                                        data-toggle="toggle" data-on="Active"
                                                                        data-off="InActive"
                                                                        {{ isset($bbb_default_join_settings['userdata_bbb_auto_share_webcam']) && $bbb_default_join_settings['userdata_bbb_auto_share_webcam'] ? 'checked' : '' }}>
                                                                    <span class="slider round"></span>
                                                                </label>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="input-field setting-form-field">
                                                            <div class="field-label"> Record Webcams</div>
                                                            <label class="toggle" style="display: flex">
                                                                <input type="text" class="userdata_bbb_record_video"
                                                                    name="data[bbb_default_join_settings][userdata_bbb_record_video]"
                                                                    value="{{ isset($bbb_default_join_settings['userdata_bbb_record_video']) && $bbb_default_join_settings['userdata_bbb_record_video'] ? 'Enabled' : 'Disabled' }}"
                                                                    readonly>
                                                                <label class="switch">
                                                                    <input class="toggle-class" type="checkbox"
                                                                        data-name=".userdata_bbb_record_video"
                                                                        data-onstyle="success" data-offstyle="danger"
                                                                        data-toggle="toggle" data-on="Active"
                                                                        data-off="InActive"
                                                                        {{ isset($bbb_default_join_settings['userdata_bbb_record_video']) && $bbb_default_join_settings['userdata_bbb_record_video'] ? 'checked' : '' }}>
                                                                    <span class="slider round"></span>
                                                                </label>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="input-field setting-form-field">
                                                            <div class="field-label">Skip Audio Check</div>
                                                            <label class="toggle" style="display: flex">
                                                                <input type="text"
                                                                    class="userdata_bbb_skip_check_audio"
                                                                    name="data[bbb_default_join_settings][userdata_bbb_skip_check_audio]"
                                                                    value="{{ isset($bbb_default_join_settings['userdata_bbb_skip_check_audio']) && $bbb_default_join_settings['userdata_bbb_skip_check_audio'] ? 'Enabled' : 'Disabled' }}"
                                                                    readonly>
                                                                <label class="switch">
                                                                    <input class="toggle-class" type="checkbox"
                                                                        data-name=".userdata_bbb_skip_check_audio"
                                                                        data-onstyle="success" data-offstyle="danger"
                                                                        data-toggle="toggle" data-on="Active"
                                                                        data-off="InActive"
                                                                        {{ isset($bbb_default_join_settings['userdata_bbb_skip_check_audio']) && $bbb_default_join_settings['userdata_bbb_skip_check_audio'] ? 'checked' : '' }}>
                                                                    <span class="slider round"></span>
                                                                </label>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="input-field setting-form-field">
                                                            <div class="field-label">Enable Listen Only</div>
                                                            <label class="toggle" style="display: flex">
                                                                <input type="text"
                                                                    class="userdata_bbb_listen_only_mode"
                                                                    name="data[bbb_default_join_settings][userdata_bbb_listen_only_mode]"
                                                                    value="{{ isset($bbb_default_join_settings['userdata_bbb_listen_only_mode']) && $bbb_default_join_settings['userdata_bbb_listen_only_mode'] ? 'Enabled' : 'Disabled' }}"
                                                                    readonly>
                                                                <label class="switch">
                                                                    <input class="toggle-class" type="checkbox"
                                                                        data-name=".userdata_bbb_listen_only_mode"
                                                                        data-onstyle="success" data-offstyle="danger"
                                                                        data-toggle="toggle" data-on="Active"
                                                                        data-off="InActive"
                                                                        {{ isset($bbb_default_join_settings['userdata_bbb_listen_only_mode']) && $bbb_default_join_settings['userdata_bbb_listen_only_mode'] ? 'checked' : '' }}>
                                                                    <span class="slider round"></span>
                                                                </label>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="input-field setting-form-field">
                                                            <div class="field-label">Custom CSS URL</div>
                                                            <input type="url"
                                                                name="data[bbb_default_join_settings][userdata_bbb_custom_style_url]"
                                                                value="{{ $bbb_default_join_settings['userdata_bbb_custom_style_url'] ?? '' }}"
                                                                placeholder="Custom CSS URL" />
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="input-field setting-form-field">
                                                            <div class="field-label">Hide Nav Bar</div>
                                                            <label class="toggle" style="display: flex">
                                                                <input type="text" class="userdata_bbb_hide_nav_bar"
                                                                    name="data[bbb_default_join_settings][userdata_bbb_hide_nav_bar]"
                                                                    value="{{ isset($bbb_default_join_settings['userdata_bbb_hide_nav_bar']) && $bbb_default_join_settings['userdata_bbb_hide_nav_bar'] ? 'Enabled' : 'Disabled' }}"
                                                                    readonly>
                                                                <label class="switch">
                                                                    <input class="toggle-class" type="checkbox"
                                                                        data-name=".userdata_bbb_hide_nav_bar"
                                                                        data-onstyle="success" data-offstyle="danger"
                                                                        data-toggle="toggle" data-on="Active"
                                                                        data-off="InActive"
                                                                        {{ isset($bbb_default_join_settings['userdata_bbb_hide_nav_bar']) && $bbb_default_join_settings['userdata_bbb_hide_nav_bar'] ? 'checked' : '' }}>
                                                                    <span class="slider round"></span>
                                                                </label>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="input-field setting-form-field">
                                                            <div class="field-label">Hide Presentation</div>
                                                            <label class="toggle" style="display: flex">
                                                                <input type="text"
                                                                    class="userdata_bbb_hide_presentation"
                                                                    name="data[bbb_default_join_settings][userdata_bbb_hide_presentation]"
                                                                    value="{{ isset($bbb_default_join_settings['userdata_bbb_hide_presentation']) && $bbb_default_join_settings['userdata_bbb_hide_presentation'] ? 'Enabled' : 'Disabled' }}"
                                                                    readonly>
                                                                <label class="switch">
                                                                    <input class="toggle-class" type="checkbox"
                                                                        data-name=".userdata_bbb_hide_presentation"
                                                                        data-onstyle="success" data-offstyle="danger"
                                                                        data-toggle="toggle" data-on="Active"
                                                                        data-off="InActive"
                                                                        {{ isset($bbb_default_join_settings['userdata_bbb_hide_presentation']) && $bbb_default_join_settings['userdata_bbb_hide_presentation'] ? 'checked' : '' }}>
                                                                    <span class="slider round"></span>
                                                                </label>
                                                            </label>
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
                                    <button class="btn setting-btn" type="submit">Save Changes</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </section>
        </div>
    </main>
@endsection

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('.toggle-class').change(function() {
            var status = $(this).prop('checked') == true ? 'Enabled' : 'Disabled';
            var toggleName = $(this).data('name');
            $(toggleName).val(status);
        })
        $('.color-class').change(function() {
            var color = $(this).val();
            $('.bannerColor').val(color);
        })
        $('#selectFileBtn').click(function() {
            $('#alertSound').click();
        })
        $('input[type="file"]').change(function(e) {
            var file = e.target.files[0].name;
            $('#selectFileName').html(file);
        });
    });
</script>
