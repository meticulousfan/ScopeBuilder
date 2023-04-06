@extends('admin.layout.main')
@section('page-title')
    Settings
@endsection

@section('page-caption')
    General Settings
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
                    <h1 class="page-caption">General Settings</h1>

                    {{-- <input type="number" name="data[settings][pricePerCallMinute]"
                    value="{{ $settings['pricePerCallMinute'] ?? '0.00' }}"> --}}
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
                                <li><a href="{{ route('admin.settings.index') }}"
                                        style="text-decoration: none; color: #1890FF;">General
                                        Settings</a></li>
                                <li><a href="{{ route('admin.settings.calls') }}" style="text-decoration: none;">Calls
                                        Settings</a></li>
                                <li><a href="{{ route('admin.settings.seo') }}" style="text-decoration: none;">SEO</a></li>
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
                        <form action="{{ route('admin.settings.index.submit') }}" method="POST">
                            @csrf
                            <div class="page-tab">
                                <div class="tab-content" style="display: block;">
                                    <div class="column">
                                        <div class="column-section">
                                            <div class="form">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="input-field setting-form-field">
                                                            <div class="field-label">Maintenance</div>
                                                            <label class="toggle" style="display: flex">
                                                                <input type="text" class="maintenance"
                                                                    name="data[settings][maintenance]"
                                                                    value="{{ isset($settings['maintenance']) && $settings['maintenance'] ? 'Enabled' : 'Disabled' }}"
                                                                    readonly>
                                                                <label class="switch">
                                                                    <input class="toggle-class" type="checkbox"
                                                                        data-name=".maintenance" data-onstyle="success"
                                                                        data-offstyle="danger" data-toggle="toggle"
                                                                        data-on="Active" data-off="InActive"
                                                                        {{ isset($settings['maintenance']) && $settings['maintenance'] ? 'checked' : '' }}>
                                                                    <span class="slider round"></span>
                                                                </label>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="input-field setting-form-field">
                                                            <div class="field-label">Maintenance Reason</div>
                                                            <input type="text"
                                                                value="{{ $settings['maintenanceReason'] ?? '' }}"
                                                                name="data[settings][maintenanceReason]"
                                                                placeholder="Reasons for Maintenance">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-field input-field setting-form-field">
                                                            <span>
                                                                <label class="field-label">Maintenance Duration
                                                                    Minutes</label>
                                                                <input type="number"
                                                                    name="data[settings][maintenanceDuration]"
                                                                    value="{{ $settings['maintenanceDuration'] ?? '1' }}"
                                                                    min="1" max="1440" step="1"
                                                                    required>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-field input-field setting-form-field">
                                                            <span>
                                                                <label class="field-label">Number of items per
                                                                    table</label>
                                                                <input type="number"
                                                                    name="data[settings][numberPerTable]"
                                                                    value="{{ $settings['numberPerTable'] ?? '5' }}"
                                                                    min="5" max="20" step="1"
                                                                    required>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-field input-field select-field setting-form-field">
                                                            <span>
                                                                <label class="field-label">Timezone</label>
                                                                <select name="data[settings][timezone]">
                                                                    @php
                                                                        $tzlist = timezone_identifiers_list();
                                                                        $selected_tz = $settings['timezone'] ?? 'America/New_York';
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
                                                    <div class="col-md-4">
                                                        <div
                                                            class="form-field input-field select-field setting-form-field">
                                                            <div class="field-label">Locale</div>
                                                            <select type="text" name="data[settings][locale]">
                                                                <option value="en">English</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <div class="input-field setting-form-field">
                                                            <div class="field-label">Minimum Payout Amount</div>
                                                            <input type="number"
                                                                name="data[settings][minimumPayoutAmount]"
                                                                value="{{ $settings['minimumPayoutAmount'] ?? '0.00' }}"
                                                                min="0" max="100000" step="0.01" required>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <div class="input-field setting-form-field">
                                                            <div class="field-label">Maximum Payout Amount</div>
                                                            <input type="number"
                                                                name="data[settings][maximumPayoutAmount]"
                                                                value="{{ $settings['maximumPayoutAmount'] ?? '0.00' }}"
                                                                min="0" max="100000" step="0.01" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="input-field setting-form-field">
                                                            <div class="field-label">Minimum Days before request Payout</div>
                                                            <input type="number"
                                                                name="data[settings][minimumDaysBeforeRPayout]"
                                                                value="{{ $settings['minimumDaysBeforeRPayout'] ?? '0' }}"
                                                                min="0" max="100" step="1" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="input-field setting-form-field">
                                                            <div class="field-label">Dev percentage per call</div>
                                                            <input type="number"
                                                                name="data[settings][devPercentagePerCall]"
                                                                value="{{ $settings['devPercentagePerCall'] ?? '0.00' }}"
                                                                min="0" max="100" step="0.01" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="input-field setting-form-field">
                                                            <div class="field-label">Dev percentage per project</div>
                                                            <input type="number"
                                                                name="data[settings][devPercentagePerProject]"
                                                                value="{{ $settings['devPercentagePerProject'] ?? '0.00' }}"
                                                                min="0" max="100" step="0.01" required>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <div class="input-field setting-form-field">
                                                            <div class="field-label">Default Question Price</div>
                                                            <input type="number"
                                                                name="data[settings][defaultQuestionPrice]"
                                                                value="{{ $settings['defaultQuestionPrice'] ?? '0.10' }}"
                                                                min="0.10" max="100" step="0.01" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-field input-field setting-form-field">
                                                            <span>
                                                                <label class="field-label">Dev Project Referral
                                                                    Commission</label>
                                                                <input type="number"
                                                                    name="data[settings][devProjectReferralCommission]"
                                                                    value="{{ $settings['devProjectReferralCommission'] ?? '5' }}"
                                                                    min="0" max="100" step="1"
                                                                    required>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-field input-field setting-form-field">
                                                            <span>
                                                                <label class="field-label">Max Number Skills Dev Can
                                                                    Choose</label>
                                                                <input type="number"
                                                                    name="data[settings][maxNumberSkillsDevCanChoose]"
                                                                    value="{{ $settings['maxNumberSkillsDevCanChoose'] ?? '5' }}"
                                                                    min="0" max="100" step="1"
                                                                    required>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="input-field setting-form-field">
                                                            <div class="field-label">Skill Submission</div>
                                                            <label class="toggle" style="display: flex">
                                                                <input type="text" class="skillSubmission"
                                                                    name="data[settings][skillSubmission]"
                                                                    value="{{ isset($settings['skillSubmission']) && $settings['maintenance'] ? 'Enabled' : 'Disabled' }}"
                                                                    readonly>
                                                                <label class="switch">
                                                                    <input class="toggle-class" type="checkbox"
                                                                        data-name=".skillSubmission"
                                                                        data-onstyle="success" data-offstyle="danger"
                                                                        data-toggle="toggle" data-on="Active"
                                                                        data-off="InActive"
                                                                        {{ isset($settings['skillSubmission']) && $settings['skillSubmission'] ? 'checked' : '' }}>
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
    });
</script>
