@extends('admin.layout.main')
@section('page-title')
    Ventted Freelancer Projects
@endsection

@section('page-caption')
    Ventted Freelancer Projects
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
                    <h1 class="page-caption">Ventted Freelancer Projects</h1>
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
                    <form action="{{ route('admin.venttedprojects') }}" method="GET" class="ventted_search_form">
                        <div class="form">
                            <div class="input-field">
                                <input type="text" class="input" value="{{$search}}" name="search" placeholder="Search Something…" >
                            </div>
                        </div>
                    </form>
                    <div class="section-inner">
                        <div class="table-wrapper">
                            <table class="table ventted_projects_table">
                                <thead>
                                    <tr>
                                        <th>Project</th>
                                        <th>Client</th>
                                        <th>Freelancer</th>
                                        <th>Date Added</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($projects as $project)
                                        @php
                                            $user_data = DB::table('users')
                                                ->where(['id' => $project->user_id])
                                                ->first();
                                        @endphp
                                        @if(isset($user_data))
                                        <tr>
                                            <td>{{ $project->name }}
                                            </td>
                                            <td>{{ $user_data->email }}
                                                <br>
                                                <a target="_blank"
                                                    href="{{ route('admin.client', $project->client_uuid) }}">VIEW
                                                    CLIENT PROFILE</a>
                                            </td>
                                            <td>
                                                @if (!empty($project->assigned_to_user_id))
                                                    @foreach($developers AS $developer)
                                                        @if($developer->id == $project->assigned_to_user_id)
                                                        {{ $developer->email }}
                                                        <br>
                                                        <a target="_blank"
                                                            href="{{ route('developer.profile', $developer->uuid) }}">VIEW
                                                            Freelancer PROFILE</a>
                                                        @endif
                                                    @endforeach
                                                @else
                                                    <label>None</label>
                                                @endif
                                            </td>
                                            <td>{{ date('l, F jS, Y', strtotime($project->created_at)) . ' at ' . date('h:i A', strtotime($project->created_at)) }}
                                            </td>

                                        </tr>
                                        @endif
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
@endsection
