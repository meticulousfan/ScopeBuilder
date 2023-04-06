@extends('admin.layout.main')
@section('page-title')
    Questionnaires
@endsection

@section('page-caption')
    Questionnaires
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
                    <span style="display: flex;align-items: center;">
                        <h1 class="page-caption" style="margin: 0px!important;">Edit Questionnaires</h1>
                        {{-- <div
                            style="width: 47px;height:26px; margin: 1px 16px;background: #1890FF 0% 0% no-repeat padding-box;border-radius:13px;opacity:1; text-align:center;align-items: center; display: grid;">
                            <div style="font-size:13px;color:white;"></div>
                        </div> --}}
                    </span>
                </div>
            </div>
        </div>
    </header>
    <main class="page-main">
        <div class="page-content">
            <section class="default-section">
                <div class="container">
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
                                                <span style="margin-left: 3px;">Oops! {{ session('error') }}
                                                    Please try again.</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="section-inner">
                        <div id="fb-editor" style=" padding: 0; margin: 10px 0; background: #f2f2f2 "></div>
                    </div>
                    <div>
                        <button class="btn setting-btn" type="button"  id="getXML" style="float: left; width:100px;" >Back</button>
                        <button class="btn setting-btn" type="button"  id="getJSON" style="float: right; width:100px;" >Next</button>
                    </div>
                </div>
            </section>
        </div>
    </main>
    <div class="modal" id="remove-questionnaire-modal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <button class="modal-close" aria-label="Close modal"></button>
                <form action="{{ route('admin.questionnaire.delete') }}" method="POST">
                    @csrf
                    <input type="hidden" id="delete_questionnaire_id" name="questionnaire_id">
                    <div class="modal-caption">
                        <div class="mc-icon">
                            <img src="{{ asset('ui_assets/img/icons/trash.svg') }}" alt="">
                        </div>
                        <h3 class="mc-title"><strong>Delete Questionnaire</strong></h3>
                        <p class="mc-subtitle">Are you sure you wish to delete this
                            Questionnaire "<strong id="delete_questionnaire_name"></strong>"? This action will&nbsp;remove
                            the
                            Questionnaire and can not be undone.</p>
                    </div>

                    <div class="modal-footer" style="flex-wrap: inherit; padding:0px;border:0px;">
                        <button class="btn btn-light js-modal-close"
                            style="padding: 10px 20px;min-height: 54px;border: 1px solid var(--btn-bg); border-radius: var(--r);background: var(--btn-bg);color: var(--btn-color);">Cancel</button>
                        <button class="btn" type="submit"
                            style="padding: 10px 20px;min-height: 54px;border: 1px solid var(--btn-bg); border-radius: var(--r);background: var(--btn-bg);color: var(--btn-color);">Delete
                            Questionnaire</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection


<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
<script src="{{ asset('js/form-builder.min.js') }}"></script>

<script>
    //   jQuery(function($) {
    //     $(document.getElementById('fb-editor')).formBuilder();
    //   });


    jQuery(function($) {
        var fbEditor = document.getElementById('fb-editor');
        var options = {
            showActionButtons: false // defaults: `true`
        };
        var formBuilder = $(fbEditor).formBuilder(options);
        //var formBuilder = $(fbEditor).formBuilder();

        document.getElementById('getXML').addEventListener('click', function() {
            alert(formBuilder.actions.getData('xml'));
        });
        document.getElementById('getJSON').addEventListener('click', function() {
            alert(formBuilder.actions.getData('json'));
        });
        document.getElementById('getJS').addEventListener('click', function() {
            alert('check console');
            console.log(formBuilder.actions.getData());
        });
    });
</script>
<script>
    $(document).ready(function() {
        $(".deleteType").click(function() {
            var id = $(this).data("id");
            var name = $(this).data("name");
            $('#delete_questionnaire_id').val(id);
            $('#delete_questionnaire_name').html(name);
        })
    });
</script>
