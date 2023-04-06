@extends('admin.layout.main')
@section('page-title')
    Recordings
@endsection

@section('page-caption')
Recordings
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
                    <h1 class="page-caption">Recordings</h1>
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
                    <form action="{{ route('admin.recordings') }}" method="GET" class="ventted_search_form">
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
                                        <th>Recording</th>
                                        <th>Project Name</th>
                                        <th>URL</th>
                                        <th>Client Review</th>
                                        <th>Created Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recordings as $recording)
                                    <tr>
                                        <td>
                                            <a href="{{ route('admin.recording', $recording->bbb_recordings_uuid) }}">
                                                <img src="{{ asset('ui_assets/img/content-images/recording-placeholder.jpg') }}" alt="" width="135">
                                            </a>
                                        </td>
                                        <td style="max-width: 350px; word-wrap:break-word;">
                                            <a style="font-size: 16px !important;" href="{{ route('admin.project', $recording->project_uuid) }}">{{  $recording->project_name }}</a>
                                        </td>
                                        <td style="max-width: 350px; word-wrap:break-word;">
                                            <a style="font-size: 16px !important;" href="{{ route('admin.recording', $recording->bbb_recordings_uuid) }}">{{ route('admin.recording', $recording->bbb_recordings_uuid) }}</a>
                                            <br>
                                            <span style="color: #8C8C8C; font-size: 16px !important;">Client : {{$recording->client_name}} &nbsp;&nbsp;<br> Freelancer : {{$recording->developer_name}}</span>
                                        </td>
                                        <td>{{$recording->review}}
                                            <br>
                                            
                                            <div style="display:flex;width: 67px;font: normal normal normal 16px/20px Roboto;letter-spacing: 0px;">
                                                @for ($i = 0; $i < 5; $i++)
                                                    @if($i < round($recording->rating))
                                                        <div style="color: #1890FF;">★</div> 
                                                    @else
                                                        <div style="color: #D1D1D1;">★</div>
                                                    @endif
                                                @endfor                                                    
                                            </div>
                                        </td>

                                        <td style="text-align: right;width: 180px;">{{ date('l, F jS, Y', strtotime($recording->created_at)) }}
                                            <br><span style="color: #8C8C8C;">{{ date('h:i A', strtotime($recording->created_at)) }}</span>
                                        </td>

                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="row">
                            <div class="col-auto pagination-wrap">
                                <div class="float-right">
                                    {!! $recordings->appends(compact('perpage'))->links() !!}
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </section>
        </div>
    </main>
@endsection
