@extends('admin.layout.main')
@section('page-title')
    Projects
@endsection

@section('page-caption')
    Projects
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
                    <h1 class="page-caption">Project Details</h1>
                </div>
            </div>
        </div>
    </header>
    <main class="page-main">
        <div class="page-content">
            <section class="default-section">
                <div class="container">
                    <div style="border: 1px solid #E8E8E8; opacity: 1; margin: 4px"></div>
                    <div style="margin: 0px 5px;">
                        <h4>{{ $project->name }}</h4>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <table class="table user_details_table title">
                                <tbody>
                                    <tr>
                                        <td>Status</td>
                                        <td>{{$project->project_type}}</td>
                                    </tr>
                                    <tr>
                                        <td>Estimated Cost</td>
                                         <td>{{ number_format(round(floatval($project->amount), 2), 2) }} USD</td>
                                    </tr>
                                    <tr>
                                        <td>Vetted Freelancer</td>
                                        <td>{{ $project->assigned_to_user_id?'Yes':'No' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Collaborators</td>
                                        <td>{{ $project->collaborativeNum }} Collaborators</td>
                                    </tr>
                                    <tr>
                                        <td>Client</td>
                                        <td><a href="{{ route('admin.client', $project->client_uuid) }}">{{$project->client_name}}</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Created On</td>
                                        <td>{{ date('F jS, Y', strtotime($project->created_at)) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Updated On</td>
                                        <td>{{ date('F jS, Y', strtotime($project->updated_at)) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>                        
                    </div>
                    <div style="margin: 15px 5px;">
                        <span style="display: flex;">
                            <h4>Calls</h4>
                            
                            @php $total_amount = 0; @endphp
                            @foreach($meetings as $meeting)
                            @php $total_amount += $meeting->amount; @endphp
                            @endforeach
                            <div style="width: 100px;height:26px; margin: 1px 16px;background: #1890FF 0% 0% no-repeat padding-box;border-radius:13px;opacity:1; text-align:center;align-items: center; display: grid;">
                                <div style="font-size:13px;color:white;">{{ $project->meetingNum}} | {{ number_format(round(floatval($total_amount),2),2) }} USD</div>
                            </div>
                        </span>
                        <table class="table user_details_table">
                            <thead>
                                <tr>
                                    <th>Project Name</th>
                                    <th>Duration</th>
                                    <th>Stripe ID</th>
                                    <th>Date</th>
                                    <th>Call Cost</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($meetings as $meeting)
                                <tr>
                                    <td><a href="{{ route('admin.recording', $meeting->bbb_meetings_uuid) }}">{{$meeting->project_name}}</a>
                                    </td>      
                                    <td>{{$meeting->duration}} Minutes</td>   
                                    <td>{{$meeting->stripe_id}}</td>                                  
                                    <td>{{ date('F jS, Y', strtotime($meeting->start_time))}}</td>
                                    <td>{{ number_format(round(floatval($meeting->amount),2),2) }} USD</td>
                                
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="row">
                            <div class="col-auto pagination-wrap">
                                <div class="float-right">
                                    {!! $meetings->appends(compact('perpage'))->links() !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div style="margin: 15px 5px;">
                        <span style="display: flex;">
                            <h4>Collaborators</h4>
                            <div style="width: 47px;height:26px; margin: 1px 16px;background: #1890FF 0% 0% no-repeat padding-box;border-radius:13px;opacity:1; text-align:center;align-items: center; display: grid;">
                                <div style="font-size:13px;color:white;">{{ $project->collaborativeNum}}</div>
                            </div>
                        </span>
                        <table class="table user_details_table">
                            <thead>
                                <tr>
                                    <th>Freelancer Name</th>
                                    <th>Joined Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($collaborators as $collaborator)
                                <tr>
                                    <td>{{$collaborator->collaborator_name}}</td>                                      
                                    <td>{{ date('F jS, Y', strtotime($collaborator->created_at))}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="row">
                            <div class="col-auto pagination-wrap">
                                <div class="float-right">
                                    {!! $meetings->appends(compact('perpage'))->links() !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </main>
@endsection
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
