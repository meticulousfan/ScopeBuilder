@extends('admin.layout.main')
@section('page-title')
    Settings
@endsection

@section('page-caption')
    SEO
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
                    <h1 class="page-caption">SEO</h1>
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
                                        style="text-decoration: none;">General
                                        Settings</a></li>
                                <li><a href="{{ route('admin.settings.calls') }}" style="text-decoration: none;">Calls
                                        Settings</a></li>
                                <li><a href="{{ route('admin.settings.seo') }}" style="text-decoration: none; color: #1890FF;">SEO</a></li>
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
                        <form action="{{ route('admin.settings.index.submit') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="page-tab">
                                <div class="tab-content" style="display: block;">
                                    <div class="column">
                                        <div class="column-section">
                                            <div class="form">
                                                
                                                <div class="section-caption" style="mmargin: 30px 0 20px 0;">
                                                    <h2 class="sc-title">Site Links</h2>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="input-field setting-form-field">
                                                            <div class="field-label">Meta-Title</div>
                                                            <textarea type="text"
                                                                name="data[settings][metaTitle]"
                                                                placeholder="Meta Tile" rows="3">{{ $settings['metaTitle'] ?? '' }}</textarea>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="input-field setting-form-field">
                                                            <div class="field-label">Meta-Description</div>
                                                            <textarea type="text"
                                                                name="data[settings][metaDescription]"
                                                                placeholder="Meta Description" rows="3">{{ $settings['metaDescription'] ?? '' }}</textarea>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4">
                                                        
                                                        <div class="input-field setting-form-field"
                                                            style="height: 83px !important;">
                                                            <div class="field-label">Meta-image</div>
                                                            <div style="display: flex;justify-content: space-between;">
                                                                <div id="selectFileName"
                                                                    style="    display: block; width: 200px; overflow: hidden; white-space: nowrap; text-overflow: ellipsis;">
                                                                    {{ $settings['metaImage'] ? (strlen($settings['metaImage']) < 8 ? $settings['metaImage'] : substr($settings['metaImage'], 0, 8) . '...') : 'No file' }}
                                                                </div>
                                                                <div id="selectFileBtn"
                                                                    style="    width: 90px; height: 26px; margin: 1px 16px; background: #1890FF 0% 0% no-repeat padding-box; border-radius: 10px; opacity: 1;
                                                            text-align: center; align-items: center; display: grid; color: white; font-size: 12px;">
                                                                    {{ $settings['metaImage'] == '' ? 'Select' : 'Update ' }}
                                                                    File</div>
                                                            </div>
                                                            <input type="file" id="metaImage" class="form-control"
                                                                name="metaImage" accept=".png, .jpg" multiple
                                                                hidden>
                                                        </div>
                                                        <div class="block-image" style="text-align: center;">
                                                            <img id="meta_image" src="{{ asset('upload/'.$settings['metaImage']) }}"
                                                                alt="{{ $settings['metaImage'] ? 'Meta Image' : 'No file' }} " style="height: 100px;">
                                                        </div>
                                                    </div>
                                                    


                                                </div>
                                                <br>
                                                
                                                <div class="section-caption" style="mmargin: 30px 0 20px 0;">
                                                    <h2 class="sc-title">Referral Links</h2>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12" style="color: #8c8c8c;">
                                                        Variables: %NAME%, %EMAIL%, %SKILLS%, %RATING%, %TOTALCALLS% 
                                                    </div>
                                                    
                                                    <div class="col-md-4">
                                                        <div class="input-field setting-form-field">
                                                            <div class="field-label">Meta-Title</div>
                                                            <textarea type="text"
                                                                name="data[settings][referralMetaTitle]"
                                                                placeholder="Meta Tile">{{ $settings['referralMetaTitle'] ?? '' }}</textarea>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="input-field setting-form-field">
                                                            <div class="field-label">Meta-Description</div>
                                                            <textarea type="text"
                                                                name="data[settings][referralMetaDesc]"
                                                                placeholder="Meta Description">{{ $settings['referralMetaDesc'] ?? '' }}</textarea>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="input-field setting-form-field">
                                                            <div class="field-label">Use Freelancer's Avatar</div>
                                                            <label class="toggle" style="display: flex">
                                                                <input type="text" class="referralMetaImage"
                                                                    name="data[settings][referralMetaImage]"
                                                                    value="{{ isset($settings['referralMetaImage']) && $settings['referralMetaImage'] ? 'Enabled' : 'Disabled' }}"
                                                                    readonly>
                                                                <label class="switch">
                                                                    <input class="toggle-class" type="checkbox"
                                                                        data-name=".referralMetaImage" data-onstyle="success"
                                                                        data-offstyle="danger" data-toggle="toggle"
                                                                        data-on="Active" data-off="InActive"
                                                                        {{ isset($settings['referralMetaImage']) && $settings['referralMetaImage'] ? 'checked' : '' }}>
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
        $('#selectFileBtn').click(function() {
            $('#metaImage').click();
        })
        $('input[type="file"]').change(function(e) {
            var file = e.target.files[0].name;
            $('#selectFileName').html(file);
            $('#meta_image').attr('src','');
            $('#meta_image').attr('alt','Can be viewed after saving');
        });
    });
</script>

