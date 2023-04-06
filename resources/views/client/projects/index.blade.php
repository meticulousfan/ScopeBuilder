@extends('client.layout.main')
@section('page-title')
    Projects
@endsection

@section('page-caption')
    My Projects
@endsection
@push('ui.style')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .select2.select2-container .select2-selection {
            border: 0px;
            margin-left: -5px;
        }

        .select2-container .select2-search--inline .select2-search__field::placeholder {
            color: #000;
            font-size: 17px;
        }
        #adduserform .form-field {
            display: flex;
            justify-content: center;
        }
    </style>
@endpush
@section('content')
    @include('client.layout.header')
    <main class="page-main">
        <div class="page-content">
            @if (session('success'))
                <div class="card" style="margin-top: 2rem; margin-bottom: 3px; margin-left: 2rem;">
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
            <section class="projects-section">
                <div class="container">
                    <div class="projects-grid">
                        @forelse ($projects as $project)
                            @php $uuid =$project->BLOBText;@endphp
                            @php
                                if ($project->project_type == 'existing') {
                                    $row_pdf_link = route('client.projects_existing.download.pdf', $project->uuid);
                                    $row_project_link = route('client.projects_existing.edit',[$project->uuid,'client']);
                                } else {
                                    $row_pdf_link = route('client.projects.download.pdf', $project->uuid);
                                    $row_project_link = route('client.projects.edit', [$project->uuid,'client']);
                                }
                            @endphp
                            <div class="project-card">
                                <div class="card-header">
                                    <div class="card-image">
                                        <img src="{{ asset('ui_assets/img/icons/folder.svg') }}" alt="">
                                    </div>
                                    <p class="card-type card-project-type">{{ ucfirst($project->project_type) }}</p>

                                    @if (!empty($project->web_frameworks) && !empty($project->mobile_frameworks))
                                        <p class="card-type">Web & Mobile Project</p>
                                    @elseif (!empty($project->mobile_frameworks))
                                        <p class="card-type">Mobile Project</p>
                                    @elseif (!empty($project->web_frameworks))
                                        <p class="card-type">Web Project</p>
                                    @endif
                                </div>

                                <div class="card-content">
                                    <h3 class="card-caption">{{ $project->name }}</h3>
                                    @if ($project->is_draft == 0)
                                        @if($project->transaction() && $project->transaction()->status == 2)
                                            <a target="_blank" href="{{ $row_pdf_link }}"
                                                class="card-link">{{ $row_pdf_link }}</a>
                                        @else
                                            <span class="card-link">{{ $row_pdf_link }}</span>
                                        @endif
                                    @endif
                                    <div class="card-text">
                                        <p>{{ \Illuminate\Support\Str::limit($project->description, 70, '...') }}
                                            @if (strlen($project->description) > 69)
                                                <a style="margin-left: 7px;" href="#" class="viewmore-btn"
                                                    data-modal="#view-more-modal"
                                                    data-value="{{ $project->description }}">view more</a>
                                            @endif
                                        </p>
                                    </div>
                                </div>

                                <div class="card-footer">
                                    @if ($project->full_access)
                                        @if($project->transaction() && $project->transaction()->status == 2)
                                            <div class="tooltip">
                                                <a target="_blank" href="{{ $row_pdf_link }}"
                                                    {{ $project->is_draft == 1 ? 'disabled' : '' }}
                                                    class="btn btn-stroke icon-btn">
                                                    <svg class="btn-icon">
                                                        <use
                                                            xlink:href="{{ asset('ui_assets/img/icons-sprite.svg#download') }}">
                                                        </use>
                                                    </svg>
                                                </a>
                                                <div class="right">
                                                    <div class="text-content">
                                                        <h3>Download Project</h3>
                                                        <ul>
                                                            <li>This demo has fade in/out effect.</li>
                                                        </ul>
                                                    </div>
                                                    <i></i>
                                                </div>
                                            </div>
                                            
                                        @else
                                            <div class="tooltip">
                                                <button disabled class="btn btn-stroke icon-btn">
                                                    <svg class="btn-icon">
                                                        <use
                                                            xlink:href="{{ asset('ui_assets/img/icons-sprite.svg#download') }}">
                                                        </use>
                                                    </svg>
                                                </button>
                                                <div class="right">
                                                    <div class="text-content">
                                                        <h3>Download Project</h3>
                                                        <ul>
                                                            <li>This demo has fade in/out effect.</li>
                                                        </ul>
                                                    </div>
                                                    <i></i>
                                                </div>
                                            </div>
                                        @endif

                                        {{-- <button class="btn btn-stroke icon-btn copy-btn" {{ $project->is_draft == 1 ? 'disabled' : '' }} data-value="{{ $row_pdf_link }}">
                                            <svg class="btn-icon">
                                                <use xlink:href="{{ asset('ui_assets/img/icons-sprite.svg#link') }}"></use>
                                            </svg>
                                        </button> --}}

                                        <div class="tooltip">
                                            <button class="btn btn-stroke icon-btn"
                                                {{ $project->is_draft == 1 ? 'disabled' : '' }}
                                                data-modal="#share-project-modal{{ $project->id }}">
                                                <!-- <button class="btn btn-stroke icon-btn copy-btn" {{ $project->is_draft == 1 ? 'disabled' : '' }} data-value="{{ $row_pdf_link }}"> -->
                                                <svg class="btn-icon">
                                                    <use xlink:href="{{ asset('ui_assets/img/icons-sprite.svg#link') }}">
                                                    </use>
                                                </svg>
                                            </button>
                                            <div class="right">
                                                <div class="text-content">
                                                    <h3>Share a Link</h3>
                                                    <ul>
                                                        <li>This demo has fade in/out effect.</li>
                                                    </ul>
                                                </div>
                                                <i></i>
                                            </div>
                                        </div>


                                        <!-- You can fire this modal using showModal('#remove-project-modal') in js -->
                                        {{-- <button class="btn btn-stroke icon-btn destroy-btn" type="button" data-id="{{ $project->id }}"
                                            data-modal="#remove-project-modal">
                                            <svg class="btn-icon">
                                                <use xlink:href="{{ asset('ui_assets/img/icons-sprite.svg#trash') }}"></use>
                                            </svg>
                                        </button> --}}
                                        <div class="tooltip">
                                            <!-- You can fire this modal using showModal('#remove-project-modal') in js -->
                                            @if (auth()->user() && $project->user_id == auth()->user()->id)
                                                <button class="btn btn-stroke icon-btn destroy-btn" type="button"
                                                data-id="{{ $project->id }}" data-modal="#remove-project-modal">
                                                    <svg class="btn-icon">
                                                        <use xlink:href="{{ asset('ui_assets/img/icons-sprite.svg#trash') }}">
                                                        </use>
                                                    </svg>
                                                </button>
                                                <div class="right">
                                                    <div class="text-content">
                                                        <h3>Delete Project</h3>
                                                        <ul>
                                                            <li>This demo has fade in/out effect.</li>
                                                            {{-- <li>It is using CSS opacity, visibility, and transition property to toggle the tooltip.</li>
                                                            <li>Other demos are using display property<em>(none or block)</em> for the toggle.</li> --}}
                                                        </ul>
                                                    </div>
                                                    <i></i>
                                                </div>
                                            @endif
                                            
                                        </div>
                                        <button class="btn btn-stroke icon-btn destroy-btn" type="button"
                                            data-id="{{ $project->id }}" data-name="{{ $project->name }}" data-modal="#add-user-modal">
                                            Add User
                                        </button>
                                        {{-- <a href="{{ $row_project_link }}" class="btn btn-stroke">View Project</a> --}}
                                        <a href="{{ route('client.projects.questions', $project->uuid) }}" class="btn btn-stroke">View Project</a>
                                    @elseif($project->can_view_pdf)
                                        @if($project->transaction() && $project->transaction()->status == 2)
                                            <div class="tooltip">
                                                <a target="_blank" href="{{ $row_pdf_link }}"
                                                    {{ $project->is_draft == 1 ? 'disabled' : '' }}
                                                    class="btn btn-stroke icon-btn">
                                                    <svg class="btn-icon">
                                                        <use
                                                            xlink:href="{{ asset('ui_assets/img/icons-sprite.svg#download') }}">
                                                        </use>
                                                    </svg>
                                                </a>
                                                <div class="right">
                                                    <div class="text-content">
                                                        <h3>Download Project</h3>
                                                        <ul>
                                                            <li>This demo has fade in/out effect.</li>
                                                        </ul>
                                                    </div>
                                                    <i></i>
                                                </div>
                                            </div>
                                        @else
                                            <div class="tooltip">
                                                <button disabled class="btn btn-stroke icon-btn">
                                                    <svg class="btn-icon">
                                                        <use
                                                            xlink:href="{{ asset('ui_assets/img/icons-sprite.svg#download') }}">
                                                        </use>
                                                    </svg>
                                                </button>
                                                <div class="right">
                                                    <div class="text-content">
                                                        <h3>Download Project</h3>
                                                        <ul>
                                                            <li>This demo has fade in/out effect.</li>
                                                        </ul>
                                                    </div>
                                                    <i></i>
                                                </div>
                                            </div>
                                        @endif
                                    @endif
                                </div>
                            </div>
                            <div class="modal" id="share-project-modal{{ $project->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <button class="modal-close" aria-label="Close modal"></button>
                                        <div class="card-footer">
                                            <div class="tooltip">
                                                <div class="card-image">
                                                    <i class="fa fa-link" aria-hidden="true"></i>
                                                </div>
                                            </div>
                                            <div class="tooltip">
                                                <h3 style="color: #000;">&nbsp;&nbsp;<b>Share Project PDF</b></h3>
                                            </div>
                                        </div>

                                        <form action="{{ route('client.invite') }}" method="POST" id="shareform">
                                            @csrf
                                            <input type="hidden" name="pdf_link"
                                                value="{{ url('client/projects/generate-pdf/public', $uuid) }}">
                                            <input type="hidden" name="project_id" value="{{ $project->id }}"
                                                id="project_id">
                                            <div class="modal-form form">
                                                <div class="form-fields">
                                                    <div class="fields-group">
                                                        <label class="radio">
                                                            <input type="radio" name="mockup" value="1"
                                                                class="visually-hidden " /> <span class="fake-label">Share
                                                                with collaborators</span>
                                                        </label>
                                                        <label class="radio">
                                                            <input id="mockupInput" type="radio" name="mockup"
                                                                value="0" class="visually-hidden mockupInputClass" /> <span
                                                                class="fake-label">Invite via Email</span>
                                                        </label>
                                                    </div>

                                                    <div class="form-field" id="multiple"
                                                        style="display: none;width: -webkit-fill-available;">
                                                        <div class="form-field input-field select-field1">
                                                            <span>
                                                                <label class="field-label">Enter Email</label><br>
                                                                <select name="user_email[]" class="mdb-select mdb-select-email"
                                                                    id="" multiple><br>
                                                                    @foreach ($user as $users)
                                                                        <option value="{{ $users->id }}">
                                                                            {{ $users->email }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="input-field" style="width: -webkit-fill-available;">
                                                        <textarea class="expanding-textarea" rows="10" name="pageq" placeholder="Enter Something here"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button class="btn btn-light js-modal-close">CLOSE WINDOW</button>
                                                <button class="btn btncp" value="1" name="copyemail"
                                                    type="submit">SEND INVITATION</button>
                                            </div>
                                        </form>
                                    </div>

                                    <div class="modal-content" style="margin-top: 20px;max-height: 80px;">
                                        <div class="card-footer" style="margin-top: -15px;">
                                            <div class="tooltip">
                                                <div class="card-image">
                                                    <i class="fa fa-link" aria-hidden="true"></i>
                                                </div>
                                            </div>
                                            <div class="tooltip">
                                                <h3 style="color: #000;">&nbsp;&nbsp;<b>Get Link</b></h3>
                                            </div>
                                            <a href="javascript:void(0);" class="btn btn-stroke copy-btn"
                                                style="float: right;"
                                                data-value='{{ url('client/projects/generate-pdf/public', $uuid) }}'
                                                data-id='{{ $project->id }}'>COPY LINK</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <h3>No Projects Found</h3>
                        @endforelse
                    </div>
                </div>
            </section>
        </div>
    </main>
    <div class="modal" id="add-user-modal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <button class="modal-close" aria-label="Close modal"></button>
                <form action="{{ route('client.projects.adduser') }}" method="POST" id="adduserform">
                    @csrf
                    <input type="hidden" name="project_id" class="project_id">
                    <div class="modal-caption">
                        <div class="mc-icon">
                            <img src="{{ asset('ui_assets/img/icons/users.svg') }}" alt="">
                        </div>
                        <h3 class="mc-title">New Project Invitation</h3>
                        <p class="mc-subtitle">Please Enter Project name and email address of your client so that and
                            invitation amil can be send for project.</p>
                    </div>
                    <div class="modal-form form">
                        <div class="form-fields">
                            <div class="form-field">
                                <div class="input-field disabled">
                                    <div class="field-label">Generated URL</div>
                                    <input id="project_urlshare" name="project_url" type="text"
                                        value="{{ Auth::user()->referral_link }}">
                                </div>
                            </div>
                            <div class="form-field">
                                <div class="input-field disabled">
                                    <div class="field-label">Project Name</div>
                                    <input type="text" value="" id="project_name">
                                </div>
                            </div>
                            <div class="form-field">
                                <div class="input-field">
                                    <div class="field-label">User Email</div>
                                    <input type="email" placeholder="User Email" class="form-control" name="email"
                                        id="emailInput" required>
                                    <div style="color: red;" class="invalid-feedback" id="emailError"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-light js-modal-close">Cancel</button>
                        <button id="adduser" class="btn" type="submit">Invite Client</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal" id="remove-project-modal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <button class="modal-close" aria-label="Close modal"></button>
                <form action="{{ route('client.projects.destroy') }}" method="POST">
                    @csrf
                    <input type="hidden" name="project_id" class="project_id">
                    <div class="modal-caption">
                        <div class="mc-icon">
                            <img src="{{ asset('ui_assets/img/icons/trash.svg') }}" alt="">
                        </div>
                        <h3 class="mc-title">Delete Project</h3>
                        <p class="mc-subtitle">Are you sure you wish to delete this project? This action will&nbsp;remove
                            the project and can not be undone.</p>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-light js-modal-close">Cancel</button>
                        <button class="btn">Delete Project</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal" id="view-more-modal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <button class="modal-close" aria-label="Close modal"></button>
                <div class="modal-caption">
                    <h3 class="mc-title">Project Description</h3>
                    <p class="mc-subtitle" id="description"></p>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-light js-modal-close">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('ui.script')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {

            $('.select-skills').select2();

            $('.btnemail').click(function() {
                if (!$('#emailInput').val()) {
                    $("#emailInput").addClass("is-invalid");
                    $("#emailError").text("Please enter email").show();
                } else {
                    $('#shareform').submit();
                }

            });
            $('.btncp').click(function() {
                //  if(!$('#nameInput').val()){
                //    $("#nameInput").addClass("is-invalid");
                //    $("#nameError").text("Please enter project name").show();
                //  }else{
                var copyText = document.getElementById("urlshare");
                var finalURL = copyText.value;
                if ($('#nameInput').val()) {
                    finalURL = finalURL + '&project=' + $('#nameInput').val();
                }
                copyToClipboard(finalURL)
                    .then(() => $(this).text('Copied...'))
                    .catch(() => alert(finalURL));

                setTimeout(function() {
                    $('#new-project-invitation-modal .modal-close').click();
                }, 2000);
                // }      
            });
            $(".mockupInputClass").click(function() {
                console.log('ehl')
                $(this).parent().parent().next().show();
                // $("#multiple").show();
            });

            function copy_link(id) {
                $.ajax({
                    type: "POST",
                    url: "{{ route('client.invite') }}",
                    data: {
                        id: id
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(data) {
                        location.reload();
                    }
                });
            }
            $(".mdb-select-email").select2({
                placeholder: "Enter Email Address...",
                width: "100%",

                clear: true
            });
            $('.adduser').click(function() {
                var id = $(this).data("id");
                $('#user_project_id').val(id)
                $('#adduserform').submit();

            });
            $('#adduserform').submit(function(e) {
                e.preventDefault();
                $('#adduser').attr("disabled", true);
                $('#adduser').text("Please wait..!");
                var form = $("#adduserform");
                var formData = new FormData(form[0]);
                $(".invalid-feedback").children("strong").text("");
                $("#adduserform input").removeClass("is-invalid");
                $.ajax({
                    method: "POST",
                    processData: false,
                    contentType: false,
                    url: form.attr('action'),
                    data: formData,
                    success: (response) => {
                        if (response.status == 200) {
                            $('.js-modal-close').click();
                            $('#adduser').attr("disabled", false);
                            $('#adduser').text("Invite Client");
                            alert("User has been assigned");
                        }
                    },
                    error: (response) => {
                        $('#adduser').attr("disabled", false).text("Submit");;
                        if (response.status === 422) {
                            let errors = response.responseJSON.errors;
                            Object.keys(errors).forEach(function(key) {
                                $("#" + key + "Input").addClass("is-invalid");
                                $("#" + key + "Error").text(errors[key][0]);
                            });
                        } else {
                            //  window.location.reload();
                        }
                    }
                })
            });

            function copyToClipboard(textToCopy) {
                // navigator clipboard api needs a secure context (https)
                if (navigator.clipboard && window.isSecureContext) {
                    // navigator clipboard api method'
                    return navigator.clipboard.writeText(textToCopy);
                } else {
                    // text area method
                    let textArea = document.createElement("textarea");
                    textArea.value = textToCopy;
                    // make the textarea out of viewport
                    textArea.style.position = "fixed";
                    textArea.style.left = "-999999px";
                    textArea.style.top = "-999999px";
                    document.body.appendChild(textArea);
                    textArea.focus();
                    textArea.select();
                    return new Promise((res, rej) => {
                        // here the magic happens
                        document.execCommand('copy') ? res() : rej();
                        textArea.remove();
                    });
                }
            }
            $(".destroy-btn").click(function() {
                var id = $(this).data("id");
                var uuid = $(this).data("uuid");
                var name = $(this).data("name");
                console.log(name);
                $('.project_id').val(id);
                $('#project_name').val(name);
                var url = `{{ route("developer.projects.edit", [':id','developer']) }}`;
                url = url.replace(':id', uuid);
                $('#project_urlshare').val(url);
            })

            $(".copy-btn").click(function() {
                var copyText = $(this).data("value");
                var id = $(this).data("id");
                copyToClipboard(copyText)
                    .then(() => $(this).text('Copied'))
                    .catch(() => alert(copyText));
                setTimeout(function() {
                    $(".copy-btn").html(
                        '<svg class="btn-icon"><use xlink:href="{{ asset('ui_assets/img/icons-sprite.svg#link') }}"></use></svg>'
                        );
                }, 500);
                copy_link(id);
            })

            $(".viewmore-btn").click(function() {
                var description = $(this).data('value');
                $("#description").text(description)
            })
        });
    </script>
    <style type="text/css">
        #add-user-modal input#emailInput {
            width: 100%;
            padding: 10px;
            margin-top: 20px;
        }

        #add-user-modal h3 {
            color: #000;
            font-size: 28px;
        }

        #add-user-modal .input-field {
            width: 70%;
        }

        .alert.alert-success {
            color: #1890ff;
            font-size: 20px;
        }
    </style>
@endpush
