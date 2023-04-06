@extends('admin.layout.main')
@section('page-title')
    Clients
@endsection

@section('page-caption')
    Clients
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
                    <h1 class="page-caption">Client Details</h1>
                </div>
            </div>
        </div>
    </header>
    <main class="page-main">
        <div class="page-content">
            <section class="default-section">
                <div class="container">
                    <div style="border: 1px solid #E8E8E8; opacity: 1; margin: 4px"></div>
                    <div style="margin: 15px 5px;">    
                        <label class="switch">
                            <input data-id="{{ $client->id }}" class="toggle-class"
                                type="checkbox" data-onstyle="success" data-offstyle="danger"
                                data-toggle="toggle" data-on="Active" data-off="InActive"
                                {{ $client->status ? 'checked' : '' }}>
                            <span class="slider round"></span>
                        </label>
                    </div>
                    <div style="margin: 0px 5px;">
                        <h4>{{ $client->name }}</h4>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <table class="table user_details_table title">
                                <tbody>
                                    <tr>
                                        <td>Calls Duration</td>
                                        <td>{{ $client->totalDuration >60?number_format(round(floatval($client->totalDuration/60))).' Hours':number_format($client->totalDuration).' Minutes' }} </td>
                                    </tr>
                                    <tr>
                                        <td>Total Spent</td>
                                         <td>{{ number_format(round(floatval($client->totalAmount),2),2) }} USD</td>
                                    </tr>
                                    <tr>
                                        <td>Created On</td>
                                        <td>{{ date('F jS, Y', strtotime($client->created_at)) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Updated On</td>
                                        <td>{{ date('F jS, Y', strtotime($client->updated_at)) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="col-md-4">
                            <table class="table user_details_table title">
                                <tbody>
                                    <tr>
                                        <td>Country</td>
                                        <td>{{ $client->country_name }}</td>
                                    </tr>
                                    <tr>
                                        <td>Time Zone</td>
                                        <td>{{ $client->timezone??'GMT-4' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Language</td>
                                        <td>{{ $client->language=='en'?'English':$client->language }}</td>
                                    </tr>
                                    <tr>
                                        <td>Verified</td>
                                        <td>{{ $client->is_email_verified==1?'Yes':'No' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div style="margin: 15px 5px;">
                        <span style="display: flex;">
                            <h4>Projects</h4>
                            <div style="width: 47px;height:26px; margin: 1px 16px;background: #1890FF 0% 0% no-repeat padding-box;border-radius:13px;opacity:1; text-align:center;align-items: center; display: grid;">
                                <div style="font-size:13px;color:white;">{{ $client->projectNum}}</div>
                            </div>
                        </span>
                        <table class="table user_details_table">
                            <thead>
                                <tr>
                                    <th>Project Name</th>
                                    <th style="width:300px;">Created On</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($projects as $project)
                                <tr>
                                    <td>{{$project->name}}</td>                                    
                                    <td>{{ date('l, F jS, Y', strtotime($project->created_at)) . ' at ' . date('h:i A', strtotime($project->created_at)) }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="row">
                            <div class="col-auto pagination-wrap">
                                <div class="float-right">
                                    {!! $projects->appends(compact('perpage'))->links() !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div style="margin: 15px 5px;">
                        <span style="display: flex;">
                            <h4>Calls</h4>
                            <div style="width: 47px;height:26px; margin: 1px 16px;background: #1890FF 0% 0% no-repeat padding-box;border-radius:13px;opacity:1; text-align:center;align-items: center; display: grid;">
                                <div style="font-size:13px;color:white;">{{ $client->meetingNum}}</div>
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
                                    <td>{{$meeting->project_name}}</td>    
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
                </div>
            </section>
        </div>
    </main>
@endsection
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('.toggle-class').change(function() {
            var status = $(this).prop('checked') == true ? 1 : 0;
            var user_id = $(this).data('id');

            $.ajax({
                type: "GET",
                dataType: "json",
                url: "{{ route('admin.user.changeStatus') }}",
                data: {
                    'status': status,
                    'user_id': user_id
                },
                success: function(data) {
                    console.log(data.success)
                }
            });
        })
    });
</script>