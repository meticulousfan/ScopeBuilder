@extends('admin.layout.main')
@section('page-title')
    Project Types
@endsection

@section('page-caption')
    Project Types
@endsection

@push('ui.style')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css"
integrity="sha256-DF7Zhf293AJxJNTmh5zhoYYIMs2oXitRfBjY+9L//AY=" crossorigin="anonymous">
@endpush

@section('content')
    {{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> --}}
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
                        <h1 class="page-caption" style="margin: 0px!important;">Project Types</h1>
                        <div
                            style="width: 47px;height:26px; margin: 1px 16px;background: #1890FF 0% 0% no-repeat padding-box;border-radius:13px;opacity:1; text-align:center;align-items: center; display: grid;">
                            <div style="font-size:13px;color:white;">{{ $projectTypesNum }}</div>
                        </div>
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
                    <div>
                        <button class="btn setting-btn add-project-btn" type="button" style="float: right;" data-modal="#add-new-project">New Parent Project Type</button>
                    </div>
                    <div class="section-inner">
                        <div class="table-wrapper">
                            <table class="table clients_table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Created On</th>
                                        <th>Updated On</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $index=0; @endphp
                                    @foreach ($projectTypes as $projectType)
                                        @php $index++; @endphp
                                        <tr>
                                            <td>{{ $index }}</td>
                                            <td>
                                                <div class="d-flex justify-content-between">
                                                    {{-- <input type="text" value={{ $projectType->name }} id={{ $projectType->id}} class="parentProject"> --}}
                                                    <span>{{ $projectType->name }}</span>
                                                    <button data-toggle="collapse" data-target="#subproject{{ $projectType->id }}" type="button" role="button" class="collapse-button"><i class="fal fa-arrow-circle-down"></i></button>
                                                </div>
                                            </td>
                                            <td>{{ date('F jS, Y', strtotime($projectType->created_at)) }}</td>
                                            <td>{{ date('F jS, Y', strtotime($projectType->updated_at)) }}</td>
                                            <td>
                                                <label class="switch">
                                                    <input data-id="{{ $projectType->id }}" class="parent-toggle-class"
                                                        type="checkbox" data-onstyle="success" data-offstyle="danger"
                                                        data-toggle="toggle" data-on="Active" data-off="InActive"
                                                        {{ $projectType->status ? 'checked' : '' }}>
                                                    <span class="slider round"></span>
                                                </label>
                                            </td>
                                            <td>
                                                <a target="_blank" href="javascript:void(0)" class="editProjectTypeBtn"
                                                data-id="{{ $projectType->id }}"
                                                data-data="{{ $projectType }}"
                                                data-modal="#edit-project-type-modal"><i class="fa fa-edit"></i></a>
                                                <button class="client-act-btn deleteType" data-id="{{ $projectType->id }}"  data-name="{{ $projectType->name }}"
                                                    data-modal="#remove-parent-type-modal">
                                                    <svg class="btn-icon">
                                                        <use
                                                            xlink:href="{{ asset('ui_assets/img/icons-sprite.svg#trash') }}">
                                                        </use>
                                                    </svg>
                                                </button>
                                            </td>
                                        </tr>
                                        @if (count($projectType->projectTypes))
                                        <tr class="collapse" id="subproject{{ $projectType->id }}">
                                            <td colspan="5" class="w-100">
                                                <table class="w-100">
                                                    <thead>
                                                        <tr>
                                                            <th>ID</th>
                                                            <th>Sub Project Name</th>
                                                            <th>Created On</th>
                                                            <th>Updated On</th>
                                                            <th>Status</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @php $index1=0; @endphp
                                                        @foreach ($projectType->projectTypes as $subProjectType)
                                                            @php $index1++; @endphp
                                                            <tr>
                                                                <td>{{ $index1 }}</td>
                                                                <td>
                                                                    <span>{{ $subProjectType->name }}</span>
                                                                </td>
                                                                <td>{{ date('F jS, Y', strtotime($subProjectType->created_at)) }}</td>
                                                                <td>{{ date('F jS, Y', strtotime($subProjectType->updated_at)) }}</td>
                                                                <td>
                                                                    <label class="switch">
                                                                        <input data-id="{{ $subProjectType->id }}" class="toggle-class"
                                                                            type="checkbox" data-onstyle="success" data-offstyle="danger"
                                                                            data-toggle="toggle" data-on="Active" data-off="InActive"
                                                                            {{ $subProjectType->status ? 'checked' : '' }}>
                                                                        <span class="slider round"></span>
                                                                    </label>
                                                                </td>
                                                                <td>
                                                                    <button class="client-act-btn deleteSubProjectType" data-id="{{ $subProjectType->id }}"  data-name="{{ $subProjectType->name }}"
                                                                        data-modal="#remove-sub-project-type-modal">
                                                                        <svg class="btn-icon">
                                                                            <use
                                                                                xlink:href="{{ asset('ui_assets/img/icons-sprite.svg#trash') }}">
                                                                            </use>
                                                                        </svg>
                                                                    </button>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            <td>
                                        </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="row">
                            <div class="col-auto pagination-wrap">
                                <div class="float-right">
                                    <div class="d-flex justify-content-center">
                                        {!! $projectTypes->links() !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </main>
    <div class="modal" id="new-parent-type-modal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <button class="modal-close" aria-label="Close modal"></button>
                <form action="{{ route('admin.type.parent.store') }}" method="POST">
                    @csrf

                    <input type="hidden" name="type_id" id="type_id" value="0">

                    <div class="modal-caption">
                        <h3 class="mc-title"><strong>New Parent Project Type</strong></h3>
                    </div>

                    <div class="form">
                        <div class="input-field setting-form-field">
                            <div class="field-label">Parent Project Type Name</div>
                            <input type="text" id="type_name" name="type_name" placeholder="Enter Parent Project Type Name">
                        </div>
                    </div>

                    <div class="modal-footer" style="flex-wrap: inherit; padding:0px;border:0px;">
                        <button class="btn btn-light js-modal-close"
                            style="padding: 10px 20px;min-height: 54px;border: 1px solid var(--btn-bg); border-radius: var(--r);background: var(--btn-bg);color: var(--btn-color);">Cancel</button>
                        <button class="btn" type="submit"
                            style="padding: 10px 20px;min-height: 54px;border: 1px solid var(--btn-bg); border-radius: var(--r);background: var(--btn-bg);color: var(--btn-color);">Add
                            Parent Project Type</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal" id="add-sub-project-type-modal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <button class="modal-close" aria-label="Close modal"></button>
                <form action="{{ route('admin.type.store') }}" method="POST">
                    @csrf

                    <input type="hidden" name="sub_type_id" id="sub_type_id" value="0">
                    <input type="hidden" name="parent_type_id" id="parent_type_id" value="0">

                    <div class="modal-caption">
                        <h3 class="mc-title"><strong>New Sub Project Type</strong></h3>
                    </div>

                    <div class="form">
                        <div class="input-field setting-form-field">
                            <div class="field-label">Sub Project Type Name</div>
                            <input type="text" id="sub_type_name" name="sub_type_name" placeholder="Enter Sub Project Type Name">
                        </div>
                    </div>

                    <div class="modal-footer" style="flex-wrap: inherit; padding:0px;border:0px;">
                        <button class="btn btn-light js-modal-close"
                            style="padding: 10px 20px;min-height: 54px;border: 1px solid var(--btn-bg); border-radius: var(--r);background: var(--btn-bg);color: var(--btn-color);">Cancel</button>
                        <button class="btn" type="submit"
                            style="padding: 10px 20px;min-height: 54px;border: 1px solid var(--btn-bg); border-radius: var(--r);background: var(--btn-bg);color: var(--btn-color);">Add
                            Sub Project Type</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal" id="assign-multiple-skills-modal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <button class="modal-close" aria-label="Close modal"></button>
                <form action="{{ route('admin.type.skills.store') }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="sub_type_id" id="skill_sub_type_id" value="0">

                    <div class="modal-caption">
                        <h3 class="mc-title"><strong>Assgin Multiple Skills</strong></h3>
                    </div>

                    <div class="form-field" id="mutli-select-field">
                        <div class="form-field input-field select-field">
                            <span>
                                <label class="field-label">Skills</label>
                                <select name="skills[]" id="skills" class="select2-multiple form-control"
                                    multiple="multiple" style="width: 100%;">
                                    @foreach ($skills as $skill)
                                        <option value="{{ $skill->id }}"
                                                data-tokens="{{ $skill->name }}">
                                            {{ $skill->name }}</option>
                                    @endforeach
                                </select>
                            </span>
                        </div>
                    </div>

                    <div class="modal-footer" style="flex-wrap: inherit; padding:0px;border:0px;">
                        <button class="btn btn-light js-modal-close"
                            style="padding: 10px 20px;min-height: 54px;border: 1px solid var(--btn-bg); border-radius: var(--r);background: var(--btn-bg);color: var(--btn-color);">Cancel</button>
                        <button class="btn" type="submit"
                            style="padding: 10px 20px;min-height: 54px;border: 1px solid var(--btn-bg); border-radius: var(--r);background: var(--btn-bg);color: var(--btn-color);">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal" id="edit-type-modal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <button class="modal-close" aria-label="Close modal"></button>
                <form action="{{ route('admin.type.store') }}" method="POST">
                    @csrf

                    <input type="hidden" id="edit_type_id" name="type_id">

                    <div class="modal-caption">
                        <h3 class="mc-title"><strong>Edit Project Type</strong></h3>
                    </div>

                    <div class="form">
                        <div class="input-field setting-form-field">
                            <div class="field-label">Project Type Name</div>
                            <input type="text" id="edit_type_name" name="type_name" placeholder="Enter Project Type Name">
                        </div>
                    </div>

                    <div class="modal-footer" style="flex-wrap: inherit; padding:0px;border:0px;">
                        <button class="btn btn-light js-modal-close"
                            style="padding: 10px 20px;min-height: 54px;border: 1px solid var(--btn-bg); border-radius: var(--r);background: var(--btn-bg);color: var(--btn-color);">Cancel</button>
                        <button class="btn" type="submit"
                            style="padding: 10px 20px;min-height: 54px;border: 1px solid var(--btn-bg); border-radius: var(--r);background: var(--btn-bg);color: var(--btn-color);">Save
                            Project Type</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal" id="add-new-project" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content"  style="overflow-y:auto; min-height:200px">
                <button class="modal-close" aria-label="Close modal"></button>
                <form action="{{ route('admin.type.store') }}" method="POST">
                    @csrf

                    <input type="hidden" id="project_type_id" name="project_type_id" value="0">

                    <div class="modal-caption">
                        <h3 class="mc-title"><strong class="project-modal-title">Add new project type</strong></h3>
                    </div>

                    <div class="form">
                        <div class="input-field setting-form-field p-panel">
                            <div class="field-label">Project Type Name</div>
                            <input type="text" id="p_type_name" name="p_type_name" placeholder="Enter project type name" required>
                        </div>
                        <button class="btn add-modal-add-sub-type-btn"
                        style="padding: 10px 20px;min-height: 54px;border: 1px solid var(--btn-bg); border-radius: var(--r);background: var(--btn-bg);color: var(--btn-color);">Add sub type</button>
                        <div class="add-modal-sub-projects-panel">
                        </div>
                    </div>

                    <div class="modal-footer" style="flex-wrap: inherit; padding:0px;border:0px;">
                        <button class="btn btn-light js-modal-close"
                            style="padding: 10px 20px;min-height: 54px;border: 1px solid var(--btn-bg); border-radius: var(--r);background: var(--btn-bg);color: var(--btn-color);">Cancel</button>
                        <button class="btn" type="submit"
                            style="padding: 10px 20px;min-height: 54px;border: 1px solid var(--btn-bg); border-radius: var(--r);background: var(--btn-bg);color: var(--btn-color);">Save
                            Project Type</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal" id="edit-project-type-modal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content"  style="overflow-y:auto; min-height:200px">
                <button class="modal-close" aria-label="Close modal"></button>
                <form action="{{ route('admin.type.store') }}" method="POST">
                    @csrf

                    <input type="hidden" id="edit-modal-project-type-id" name="project_type_id">

                    <div class="modal-caption">
                        <h3 class="mc-title"><strong>Edit project type</strong></h3>
                    </div>

                    <div class="form">
                        <div class="input-field setting-form-field p-panel">
                            <div class="field-label">Project Type Name</div>
                            <input type="text" id="edit-modal-type-input" name="p_type_name" placeholder="Enter project type name" required>
                        </div>
                        <button class="btn edit-modal-add-sub-type-btn"
                        style="padding: 10px 20px;min-height: 54px;border: 1px solid var(--btn-bg); border-radius: var(--r);background: var(--btn-bg);color: var(--btn-color);">Add sub type</button>
                        <div class="edit-modal-sub-projects-panel">
                        </div>
                    </div>

                    <div class="modal-footer" style="flex-wrap: inherit; padding:0px;border:0px;">
                        <button class="btn btn-light js-modal-close"
                            style="padding: 10px 20px;min-height: 54px;border: 1px solid var(--btn-bg); border-radius: var(--r);background: var(--btn-bg);color: var(--btn-color);">Cancel</button>
                        <button class="btn" type="submit"
                            style="padding: 10px 20px;min-height: 54px;border: 1px solid var(--btn-bg); border-radius: var(--r);background: var(--btn-bg);color: var(--btn-color);">Save
                            Project Type</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal" id="remove-parent-type-modal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <button class="modal-close" aria-label="Close modal"></button>
                <form action="{{ route('admin.type.parent.delete') }}" method="POST">
                    @csrf
                    <input type="hidden" id="delete_type_id" name="type_id">
                    <div class="modal-caption">
                        <div class="mc-icon">
                            <img src="{{ asset('ui_assets/img/icons/trash.svg') }}" alt="">
                        </div>
                        <h3 class="mc-title"><strong>Delete Parent Project Type</strong></h3>
                        <p class="mc-subtitle">Are you sure you wish to delete this
                            Parent Project Type "<strong id="delete_type_name">Delete Parent Project Type</strong>"? This action will&nbsp;remove the
                            Parent Project Type and can not be undone.</p>
                    </div>

                    <div class="modal-footer" style="flex-wrap: inherit; padding:0px;border:0px;">
                        <button class="btn btn-light js-modal-close"
                            style="padding: 10px 20px;min-height: 54px;border: 1px solid var(--btn-bg); border-radius: var(--r);background: var(--btn-bg);color: var(--btn-color);">Cancel</button>
                        <button class="btn" type="submit"
                            style="padding: 10px 20px;min-height: 54px;border: 1px solid var(--btn-bg); border-radius: var(--r);background: var(--btn-bg);color: var(--btn-color);">Delete
                            Project Type</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal" id="remove-sub-project-type-modal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <button class="modal-close" aria-label="Close modal"></button>
                <form action="{{ route('admin.type.delete') }}" method="POST">
                    @csrf
                    <input type="hidden" id="delete_sub_project_type_id" name="type_id">
                    <div class="modal-caption">
                        <div class="mc-icon">
                            <img src="{{ asset('ui_assets/img/icons/trash.svg') }}" alt="">
                        </div>
                        <h3 class="mc-title"><strong>Delete Sub Project Type</strong></h3>
                        <p class="mc-subtitle">Are you sure you wish to delete this
                            Sub Project Type "<strong id="delete_type_name">Delete Sub Project Type</strong>"? This action will&nbsp;remove the
                            Sub Project Type and can not be undone.</p>
                    </div>

                    <div class="modal-footer" style="flex-wrap: inherit; padding:0px;border:0px;">
                        <button class="btn btn-light js-modal-close"
                            style="padding: 10px 20px;min-height: 54px;border: 1px solid var(--btn-bg); border-radius: var(--r);background: var(--btn-bg);color: var(--btn-color);">Cancel</button>
                        <button class="btn" type="submit"
                            style="padding: 10px 20px;min-height: 54px;border: 1px solid var(--btn-bg); border-radius: var(--r);background: var(--btn-bg);color: var(--btn-color);">Delete
                            Project Type</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal video-modal" id="video-modal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <button class="modal-close" aria-label="Close modal"></button>
                <div class="modal-video">
                    <div id="modal-video-iframe"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('ui.script')
    <script defer="true" src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js" integrity="sha384-+sLIOodYLS7CIrQpBjl+C7nPvqq+FbNUBDunl/OZv93DB7Ln/533i8e/mZXLi/P+" crossorigin="anonymous"></script>
    <script defer="true" src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        function decodeHtml(html) {
            var txt = document.createElement("textarea");
            txt.innerHTML = html;
            var val = txt.value;
            txt.remove();
            return val;
        }

        function subInputs(id, defaultValue) {
            return `<div class='${id}sk-panel'>
                        <div class='input-field setting-form-field'>
                            <input type='text' id='s_type_name' name='type[${id}][s_type_name]' value='${defaultValue}'placeholder='Enter project type name' required>
                        </div>
                    </div>`;
        }

        function selectElments (id) {
            return `<select name="type[${id}][skills][]" id="${id}eskills" class="select2-multiple form-control"
                                    multiple="multiple" style="width: 100%;">`
        }

        function removeBtn(id) {
            return `<button class='removeSubProjectBtn btn btn-danger' type="button" id="${id}removeBtn"data-id='${id}'>remove</button>`
        }

        removeBtn(1)
        var skillOptions = JSON.parse(decodeHtml("{{$skills}}"));
        var count = "{{$count}}";
        var index = count+1;
        var collapsed = true
        $(document).ready(function() {
            $('.select2-multiple').select2({
                placeholder: 'Select',
                allowClear: true
                });
            $(".deleteType").click(function() {
                var id = $(this).data("id");
                var name = $(this).data("name");
                $('#delete_type_id').val(id);
                //$('.type_name').val(name);
            })
            $(".deleteSubProjectType").click(function() {
                var id = $(this).data("id");
                var name = $(this).data("name");
                $('#delete_sub_project_type_id').val(id);
                //$('.type_name').val(name);
            })
            $(".editeType").click(function() {
                var id = $(this).data("id");
                var name = $(this).data("name");
                $('#edit_type_id').val(id);
                $('#edit_type_name').val(name);
            })
            $(".collapse-button").click(function(){
                $('i', this).toggleClass('fa-arrow-circle-down').toggleClass('fa-arrow-circle-up');
            })
            $(".addSubProjectType").click(function(){
                var id = $(this).data("id")
                $("#parent_type_id").val(id);
            })
            $(".assignMultipleSkills").click(function(){
                $("#skills").val("");
                var id = $(this).data("id");
                $("#skill_sub_type_id").val(id);
                var skills = "";
                if ($(this).data("skills")) {
                     skills = JSON.parse($(this).data("skills")).split(",")
                } else {
                    skills = [];
                }
                $("#skills").val(skills).change()

            })
            $('.parent-toggle-class').change(function() {
                var status = $(this).prop('checked') == true ? 1 : 0;
                var type_id = $(this).data('id');

                $.ajax({
                    type: "POST",
                    dataType: "json",
                    url: "{{ route('admin.type.parent.changeStatus') }}",
                    data: {
                        'status': status,
                        'type_id': type_id
                    },
                    success: function(data) {
                        console.log(data.success)
                    }
                });
            })
            $('.toggle-class').change(function() {
                var status = $(this).prop('checked') == true ? 1 : 0;
                var type_id = $(this).data('id');

                $.ajax({
                    type: "POST",
                    dataType: "json",
                    url: "{{ route('admin.type.changeStatus') }}",
                    data: {
                        'status': status,
                        'type_id': type_id
                    },
                    success: function(data) {
                        console.log(data.success)
                    }
                });
            })
            $(".parentProject").on("input", function() {
                var type_id = $(this).attr('id')
                $.ajax({
                    type: "POST",
                    dataType: "json",
                    url: "{{ route('admin.type.parent.store') }}",
                    data: {
                    'type_name': $(this).val(),
                    'type_id': type_id
                    },
                    success: function(data) {
                        console.log(data.success)
                    }
                })
            })
            $(".subproject").on("input", function() {
                var type_id = $(this).attr('id')
                var parent_id = $(this).data('id')
            })
            $('.modal-content').on("click", ".removeSubProjectBtn", function(e) {
                var id = $(this).data('id')
                $(`.${id}sk-panel`).remove()
                e.preventDefault()
            })
            $(".add-modal-add-sub-type-btn").click(function(e){
                e.preventDefault()
                var subInput = subInputs(index, "")
                var selectElement = selectElments(index)
                var skillsElement = ''
                skillOptions.map((item) => {
                    skillsElement += `<option value='${item.id}' data-tokens='${item.name}'>${item.name}</option>`
                })
                selectElement += skillsElement
                selectElement += `</select>`
                var destroyBtn = removeBtn(index)
                if ($("#p_type_name").val())
                {
                    $(".add-modal-sub-projects-panel").append(subInput)
                    $(`.${index}sk-panel`).append(selectElement)
                    $(`.${index}sk-panel`).append(destroyBtn)
                    index++;
                }
            })
            $(".edit-modal-add-sub-type-btn").click(function(e){
                e.preventDefault()
                var subInput = subInputs(index, "")
                var selectElement = selectElments(index)
                var skillsElement = ''
                skillOptions.map((item) => {
                    skillsElement += `<option value='${item.id}' data-tokens='${item.name}'>${item.name}</option>`
                })
                selectElement += skillsElement
                selectElement += `</select>`
                var destroyBtn = removeBtn(index)
                if ($("#edit-modal-type-input").val())
                {
                    $(".edit-modal-sub-projects-panel").append(subInput)
                    $(`.${index}sk-panel`).append(selectElement)
                    $(`.${index}sk-panel`).append(destroyBtn)
                    index++;
                }
            })

            $(".editProjectTypeBtn").click(function(e) {
                var type_id = $(this).data('id')
                $("#edit-modal-project-type-id").val(type_id)
                $(".edit-modal-sub-projects-panel").empty()
                e.preventDefault()
                var projectData = $(this).data('data');
                $("#edit-modal-type-input").val(projectData.name)
                if (projectData.project_types.length) {
                    projectData.project_types.forEach((item, index) => {
                        var subInput = subInputs(index + 1, item.name)
                        $(".edit-modal-sub-projects-panel").append(subInput)
                        if (item.skills) {
                            var sub_skills = JSON.parse(item.skills).split(",")
                        } else {
                            var sub_skills= []
                        }
                        var selectElement = selectElments(index + 1)
                        var skillsElement = ''
                        skillOptions.map((item) => {
                            skillsElement += `<option value='${item.id}' data-tokens='${item.name}'>${item.name}</option>`
                        })
                        selectElement += skillsElement
                        selectElement += `</select>`
                        var destroyBtn = removeBtn(index +1)
                        $(`.${index + 1}sk-panel`).append(selectElement)
                        $(`.${index + 1}sk-panel`).append(destroyBtn)
                        if (sub_skills.length) {
                            $(`#${index + 1}eskills`).val(sub_skills).change()
                        } else {
                            $(`#${index + 1}eskills`).val([]).change()
                        }
                    })
                }
            })
        })
    </script>
@endpush