@extends('admin.layout.main')
@section('page-title')
    Client Projects
@endsection

@section('page-caption')
    Client Projects
@endsection

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css" integrity="sha256-DF7Zhf293AJxJNTmh5zhoYYIMs2oXitRfBjY+9L//AY=" crossorigin="anonymous">
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
                        <h1 class="page-caption" style="margin: 0px!important;">Client Projects</h1>
                        <div
                            style="width: 47px;height:26px; margin: 1px 16px;background: #1890FF 0% 0% no-repeat padding-box;border-radius:13px;opacity:1; text-align:center;align-items: center; display: grid;">
                            <div style="font-size:13px;color:white;">{{ $projectsNum }}</div>
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
                    <div class="section-inner">
                        <div class="table-wrapper">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Status</th>
                                        <th>Estimated Cost</th>
                                        <th>Calls</th>
                                        <th>Vetted</th>
                                        <th>Collaborator</th>
                                        <th>Created On</th>
                                        <th>Updated On</th>
                                        <th style="min-width: 80px">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($projects as $project)
                                        <tr>
                                            <td>{{ $project->id }}</td>
                                            <td class="{{ $project->deleted_at ? 'text-danger' : '' }}">{{ $project->name }}</td>
                                            <td>
                                                <div
                                                    style="width: 69px;height:24px;background: #1890FF33 0% 0% no-repeat padding-box;border-radius: 4px;opacity:1; text-align:center;align-items: center; display: grid;">
                                                    <div style="font-size:12px;color:#1890FF;font-weight: bold">{{ ucfirst($project->project_type) }}</div>
                                                </div>
                                            </td>
                                            <td>{{ number_format(round(floatval($project->amount), 2), 2) }} USD</td>
                                            <td>{{ $project->meetingNum }}</td>
                                            <td>{{ $project->assigned_to_user_id?'Yes':'No' }}</td>
                                            <td>{{ $project->collaborativeNum }}</td>
                                            <td>{{ date('F jS, Y', strtotime($project->created_at)) }}</td>
                                            <td>{{ date('F jS, Y', strtotime($project->updated_at)) }}</td>
                                            <td>
                                                <a target="_blank" href="{{ route('admin.project', $project->uuid) }}"><img
                                                        src="{{ asset('ui_assets/img/icons/vuesax-bulk-document-text.svg') }}"></a>
                                                <button class="client-act-btn" data-id="{{ $project->id }}"
                                                    data-modal="#remove-user-modal">
                                                    <svg class="btn-icon">
                                                        <use
                                                            xlink:href="{{ asset('ui_assets/img/icons-sprite.svg#trash') }}">
                                                        </use>
                                                    </svg>
                                                </button>
                                            </td>
                                            {{-- <td>
                                                
                                                @if($project->contact_developer=='1')
                                                    @if($project->project_type=='existing')
                                                        <a target="_blank" href="{{ route('client.projects_existing.download.pdf', $project->uuid) }}">View</a>
                                                    @else
                                                        <a target="_blank" href="{{ route('client.projects.download.pdf', $project->uuid) }}">View</a>
                                                    @endif
                                                @endif

                                                @if(!empty($project->assigned_to_user_id))
                                                    <span>Assigned</span>
                                                @else
                                                    <a href="{{ route('admin.projects.assign', $project->uuid) }}">To Be Assigned</a>
                                                @endif
                                            </td> --}}
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="row">
                            <div class="col-auto pagination-wrap">
                                <div class="float-right">
                                    {!! $projects->appends(compact('perpage'))->links() !!}
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </section>
        </div>
    </main>
    
    <div class="modal" id="remove-user-modal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <button class="modal-close" aria-label="Close modal"></button>
                <form action="{{ route('admin.projects.delete') }}" method="POST">
                    @csrf
                    <input type="hidden" name="project_id" id="project_id">
                    <div class="modal-caption">
                        <div class="mc-icon">
                            <img src="{{ asset('ui_assets/img/icons/trash.svg') }}" alt="">
                        </div>
                        <h3 class="mc-title"><strong>Delete Project</strong></h3>
                        <p class="mc-subtitle">Are you sure you wish to delete this
                            Project? This action will&nbsp;remove the
                            Project and can not be undone.</p>
                    </div>

                    <div class="modal-footer" style="flex-wrap: inherit; padding:0px;border:0px;">
                        <button class="btn btn-light js-modal-close"
                            style="padding: 10px 20px;min-height: 54px;border: 1px solid var(--btn-bg); border-radius: var(--r);background: var(--btn-bg);color: var(--btn-color);">Cancel</button>
                        <button class="btn" type="submit"
                            style="padding: 10px 20px;min-height: 54px;border: 1px solid var(--btn-bg); border-radius: var(--r);background: var(--btn-bg);color: var(--btn-color);">Delete
                            Project</button>
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $(".client-act-btn").click(function() {
            var id = $(this).data("id");
            $('#project_id').val(id);
        })
        
    });
</script>
