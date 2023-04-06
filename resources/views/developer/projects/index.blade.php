@extends('developer.layout.main')
@section('page-title')
    Projects
@endsection

@section('page-caption')
    My Projects
@endsection
@push('ui.style')
<style>
.select2.select2-container .select2-selection {
    border: 0px;
    margin-left: -5px;
}
.select2-container .select2-search--inline .select2-search__field::placeholder {
    color: #000;
    font-size: 17px;
}
</style>
@endpush
@section('content')
    @include('developer.layout.header')
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
                        @foreach ($projects as $project)
                            @php $uuid =$project->BLOBText;@endphp
                            @php
                                if ($project->project_type == 'existing') {
                                    $row_project_link = route('developer.projects_existing.edit', [$project->uuid,'developer']);
                                } else {
                                    $row_project_link = route('developer.projects.edit', [$project->uuid,'developer']);
                                }
                            @endphp
                            <div class="project-card">
                                <div class="card-header">
                                    <div class="card-image">
                                        <img src="{{ asset('ui_assets/img/icons/folder.svg') }}" alt="">
                                    </div>
                                    <p class="card-type">Web Project</p>
                                </div>

                                <div class="card-content">
                                    <h3 class="card-caption">{{ $project->name }}</h3>
                                    <a href="#" target="_blank" class="card-link">https://www.projectlink.com/</a>
                                    <div class="card-text">
                                        <p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod
                                            tempor
                                            invidunt ut labore et dolore magna aliquyam erat...</p>
                                    </div>
                                </div>
                                @php $uuid =$project->BLOBText;@endphp
                                <div class="card-footer">
                                    @if(isset($project->can_view_pdf) && $project->can_view_pdf)
                                        @if ($project->transaction() && $project->transaction()->status == 2)
                                            <div class="tooltip">
                                                <a target="_blank" href="{{ route('client.projects.download.pdf', $project->uuid) }}"
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
                                    @if(isset($project->collaborative_project))
                                        <a href="{{ route('client.projects.questions', $project->uuid) }}" class="btn btn-stroke">View Project</a>
                                    @endif
                                </div>
                            </div>
                           
                        @endforeach
                    </div>
                </div>
            </section>
            
           
        </div>
    </main>

    <div class="modal" id="remove-project-modal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <button class="modal-close" aria-label="Close modal"></button>

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
            </div>
        </div>
    </div>

    <div class="modal" id="new-project-invitation-modal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <button class="modal-close" aria-label="Close modal"></button>

                <div class="modal-caption">
                    <div class="mc-icon">
                        <img src="{{ asset('ui_assets/img/icons/users.svg') }}" alt="">
                    </div>
                    <h3 class="mc-title">Referral</h3>
                    <p class="mc-subtitle">Please share your Referral link with your clients.</p>
                </div>

                <form action="{{ route('developer.invite') }}" method="POST" id="shareform">
                    @csrf
                    <div class="modal-form form">
                        <div class="form-fields">
                            <div class="form-field">
                                <div class="input-field disabled">
                                    <div class="field-label">Generated URL</div>
                                    <input id="urlshare" type="url" value="{{ Auth::user()->referral_link }}" name="url" disabled>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-light js-modal-close">Cancel</button>
                        <button class="btn btncp" value="1" name="copyemail" type="button">Copy Link</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('ui.script')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script type="text/javascript">
    $('.btnemail').click(function(){
        if(!$('#emailInput').val()){
            $("#emailInput").addClass("is-invalid");
            $("#emailError").text("Please enter email").show();
        }else{
            $('#shareform').submit();
        }
       
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
    $('.btncp').click(function(){
      //  if(!$('#nameInput').val()){
        //    $("#nameInput").addClass("is-invalid");
        //    $("#nameError").text("Please enter project name").show();
      //  }else{
            var copyText = document.getElementById("urlshare");
            var finalURL = copyText.value;
            if($('#nameInput').val()){
               finalURL = finalURL + '&project='+$('#nameInput').val();
            }
             copyToClipboard(finalURL)
            .then(() => $(this).text('Copied...'))
            .catch(() => alert(finalURL));                
                 
           setTimeout(function(){ 
               $('#new-project-invitation-modal .modal-close').click(); 
            },2000);
       // }      
    });
    $("input").focus(function(){
        $('.invalid-feedback').text('');
    });

    $("#mockupInput").click(function(){
        $("#multiple").show();   
    });

    $("#share").on("click",function(){
        $("#share-project-modal").modal("show");
    });
    
    function copy_link(id)
    {
        $.ajax({
            type: "POST",
            url: "{{ route('developer.invite') }}",
            data:{id:id},
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success : function(data) {
                location.reload();
            }
        });
    }

    $("#useremail").select2({
        placeholder:"Enter Email Address...",
        width:"100%",
        
        clear:true
    });
</script>
@endpush