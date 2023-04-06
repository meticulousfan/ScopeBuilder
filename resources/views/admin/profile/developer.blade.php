@extends('admin.layout.main')
@section('page-title')
    Freelancer
@endsection

@section('page-caption')
    Freelancer
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
                    <h1 class="page-caption">Freelancer Details</h1>
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
                            <input data-id="{{ $developer->id }}" class="toggle-class"
                                type="checkbox" data-onstyle="success" data-offstyle="danger"
                                data-toggle="toggle" data-on="Active" data-off="InActive"
                                {{ $developer->status ? 'checked' : '' }}>
                            <span class="slider round"></span>
                        </label>
                    </div>
                    <div style="margin: 0px 5px;">
                        <h4>{{ $developer->name }}</h4>
                    </div>
                    <div class="row">
                        <div class="col-md-4 md-6">
                            <table class="table user_details_table title">
                                <tbody>
                                    <tr>
                                        <td>Rating</td>
                                        <td>  
                                            <div style="display:inline-flex;width: 67px;font: normal normal normal 16px/20px Roboto;letter-spacing: 0px;">
                                                @for ($i = 0; $i < 5; $i++)
                                                    @if($i < round($developer->rating))
                                                        <div style="color: #1890FF;">★</div> 
                                                    @else
                                                        <div style="color: #D1D1D1;">★</div>
                                                    @endif
                                                @endfor                                                    
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Payout</td>
                                         <td>{{ number_format(round(floatval($developer->totalPaidAmount),2),2) }} USD</td>
                                    </tr>
                                    <tr>
                                        <td>Created On</td>
                                        <td>{{ date('F jS, Y', strtotime($developer->created_at)) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Updated On</td>
                                        <td>{{ date('F jS, Y', strtotime($developer->updated_at)) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div style="margin: 15px 5px;">
                        <span style="display: flex;">
                            <h4>Projects</h4>
                            <div style="width: 47px;height:26px; margin: 1px 16px;background: #1890FF 0% 0% no-repeat padding-box;border-radius:13px;opacity:1; text-align:center;align-items: center; display: grid;">
                                <div style="font-size:13px;color:white;">{{ $developer->projectNum}}</div>
                            </div>
                        </span>
                        <table class="table user_details_table">
                            <thead>
                                <tr>
                                    <th>Project Name</th>
                                    <th>Client Name</th>
                                    <th style="width:300px;">Created On</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($projects as $project)
                                <tr>
                                    <td>{{$project->name}}</td>    
                                    <td><a href="{{ route('admin.client', $project->client_uuid) }}">{{$project->client_name}}</a>
                                        </td>                                
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
                            <h4>Referrals</h4>
                            <div style="width: 47px;height:26px; margin: 1px 16px;background: #1890FF 0% 0% no-repeat padding-box;border-radius:13px;opacity:1; text-align:center;align-items: center; display: grid;">
                                <div style="font-size:13px;color:white;">{{ $developer->referralNum}}</div>
                            </div>
                        </span>
                        <table class="table user_details_table">
                            <thead>
                                <tr>
                                    <th>Client Name</th>
                                    <th>Total Projects</th>
                                    <th style="width:300px;">Joined On</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($referrals as $referral)
                                <tr>
                                    <td><a href="{{ route('admin.client', $referral->client_uuid) }}">{{$referral->client_name}}</a></td>    
                                    <td>{{$referral->projectNum}}</td>                                
                                    <td>{{ date('l, F jS, Y', strtotime($referral->updated_at)) . ' at ' . date('h:i A', strtotime($referral->updated_at)) }}
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
                                <div style="font-size:13px;color:white;">{{$developer->meetingNum}}</div>
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
                            <h4>Payout</h4>
                            <div style="width: 47px;height:26px; margin: 1px 16px;background: #1890FF 0% 0% no-repeat padding-box;border-radius:13px;opacity:1; text-align:center;align-items: center; display: grid;">
                                <div style="font-size:13px;color:white;">{{ $developer->payoutNum}}</div>
                            </div>
                        </span>
                        <table class="table user_details_table">
                            <thead>
                                <tr>
                                    <th style="width:300px;">Date</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Rating</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($payouts as $payout)
                                <tr>                                  
                                    <td>{{ date('l, F jS, Y', strtotime($payout->created_at)) . ' at ' . date('h:i A', strtotime($payout->created_at)) }}
                                    </td>
                                    <td>{{ number_format(round(floatval($payout->amount),2),2) }} USD</td>
                                    <td>
                                        {{$payout->is_paid == 1?'PAID':'RENDING'}}
                                    </td>                            
                                    <td> 
                                        <div style="display:inline-flex;width: 67px;font: normal normal normal 16px/20px Roboto;letter-spacing: 0px;">
                                            @for ($i = 0; $i < 5; $i++)
                                                @if($i < round($payout->rating))
                                                    <div style="color: #1890FF;">★</div> 
                                                @else
                                                    <div style="color: #D1D1D1;">★</div>
                                                @endif
                                            @endfor                                                    
                                        </div>
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