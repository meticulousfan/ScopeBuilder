<header class="header">
    <div class="container">
        <div class="header-inner">
            <div class="header-block with-menu-opener">
                <button class="menu-opener lg-visible-flex" aria-label="Show navigation">
                    <span class="bar"></span>
                    <span class="bar"></span>
                    <span class="bar"></span>
                </button>
                <h1 class="page-caption">My Projects</h1>
            </div>

            <div class="header-block">
                <button class="btn sub-xs-small new-existing-btn" type="button" data-id="1234" data-modal="#new-existing-modal">Add Project</button>
                <!--a href="{{ route('client.projects.create') }}" class="btn sub-xs-small">Add New Project</a-->
            </div>
        </div>
    </div>
</header>
<div class="modal" id="new-existing-modal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <button class="modal-close" aria-label="Close modal"></button>
            <form action="{{ route('client.projects.store.new') }}" method="POST" id="">
                @csrf
                <input type="hidden" name="project_id" class="project_id">
                <div class="modal-caption">
                    <h5 class="modal-title">Add Your Project</h5>
                </div>
                <div class="modal-form form">
                    <div class="form-fields">

                        <div class="form-field">
                            <div class="input-field">
                                <div class="field-label">Project Name</div>
                                <input type="text" value="" name="project_name" required>
                            </div>
                        </div>
                        <div class="form-field">
                            <div class="input-field">
                                <div class="field-label">Project Type</div>
                                <select name="parent_project_type" id="parent_project_type_select" class="form-control" required>
                                    <option value="" disabled selected hidden></option>
                                    @foreach ($parentProjectTypes as $type)
                                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-field">
                            <div class="input-field">
                                <div class="field-label">Specialty</div>
                                <select name="project_type" id="sub_project_type_select1" class="form-control" required>
                                </select>
                            </div>
                        </div>
                        <div class="hidden-select-field">
                            <select name="hidden_project_type" id="hidden_sub_project_type_select" class="form-control" style="display: none;" required>
                                @foreach ($subProjectTypes as $type)
                                    <option value="{{ $type->id }}" data-id="{{ $type->parent_id }}">{{ $type->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-field">
                            <div class="input-field">
                                <div class="field-label">Skills</div>
                                <select class="select-skills" name="skills[]" multiple="multiple" placeholder="Skills">

                                    @foreach ($skills as $skill)
                                        <option value="{{ $skill->id }}">{{ $skill->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-light js-modal-close">Cancel</button>
                    <button id="" class="btn" type="submit">Create Project</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- <div class="modal" id="new-existing-modal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                 <h5 class="modal-title">Add Your Project</h5>
                <div style="float:right"><button type="button" class="btn-close js-modal-close" data-bs-dismiss="modal" aria-label="Close">X</button></div>
            </div>
                <a href="{{ route('client.projects.create') }}" class="btn sub-xs-small">Add New Project</a>
                <a href="{{ route('client.projects_existing.create') }}" class="btn sub-xs-small">Add Existing Project</a>
            </div>
        </div>
    </div>
</div> --}}

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $("#parent_project_type_select").change(function(){
            var parentProjectTypeId = $(this).val()
            var newOptions = []
            $("#hidden_sub_project_type_select option").each((index, item) =>{
                if ($(item).data('id') == parentProjectTypeId) {
                    newOptions.push($(item).clone())
                }
            })
            $("#sub_project_type_select1").empty()
            if(newOptions.length >= 1)
                $("#sub_project_type_select1").append(newOptions)
            else
            $("#sub_project_type_select1").append("<option value='' disabled selected hidden></option>")
        })
    });
</script>
