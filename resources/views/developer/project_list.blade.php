@extends('developer.layout.main')
@section('page-title')
    Projects
@endsection

@section('page-caption')
    Projects
@endsection

@section('content')
    @include('developer.layout.header')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css" integrity="sha256-DF7Zhf293AJxJNTmh5zhoYYIMs2oXitRfBjY+9L//AY=" crossorigin="anonymous">
 
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
                                        <th>Client Name</th>
                                        <th>Client Email</th>
                                        <th style="width:300px">Project Name</th>
                                        <th>Project Type</th>
                                        <th>Project PDF</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($projects as $project)
                                        @php
                                            $user_data = DB::table('users')->where(['id' => $project->user_id])->first();
                                        @endphp
                                        <tr>
                                            <td>{{ $user_data->name }}</td>
                                            <td>{{ $user_data->email }}</td>
                                            <td>{{ $project->name }}</td>
                                            <td>{{ ucfirst($project->project_type) }}</td>
                                            <td>
                                                @if($project->contact_developer=='1')
                                                    @if($project->project_type=='existing')
                                                        <a target="_blank" href="{{ route('client.projects_existing.download.pdf', $project->uuid) }}">View</a>
                                                    @else
                                                        <a target="_blank" href="{{ route('client.projects.download.pdf', $project->uuid) }}">View</a>
                                                    @endif
                                                @endif
                                            </td>
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
@endsection
