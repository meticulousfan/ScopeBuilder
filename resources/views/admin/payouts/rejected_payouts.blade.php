@extends('admin.layout.main')
@section('page-title')
    Payouts
@endsection

@section('page-caption')
    Freelancer Payouts
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
                    <h1 class="page-caption">Rejected Payouts</h1>
                </div>
            </div>
        </div>
    </header>
    <main class="page-main">
        <div class="page-content">
            <section class="default-section">
                <div class="container">
                    <div class="section-inner">
                            <div class="page-nav">
                                <ul>
                                    <li><a href="{{ route('admin.payouts') }}"
                                            style="text-decoration: none;">Pendding
                                            </a></li>
                                    <li><a href="{{ route('admin.payouts.accepted') }}" style="text-decoration: none;">Accepted</a></li>
                                    <li><a href="{{ route('admin.payouts.rejected') }}" style="text-decoration: none; color: #1890FF;">Rejected</a></li>
                                </ul>
                            </div>
                            <div style="height: 30px;"></div>
                        <div class="table-wrapper">
                            <table class="table no-wrap">
                                <thead>
                                    <tr>
                                        <th>Number</th>
                                        <th>Freelancer Name</th>
                                        <th>Payout Method </th>
                                        <th>Payout Amount</th>
                                        <th>Email </th>
                                        <th>Transaction ID / Note</th>
                                        <th>Created At </th>
                                        <th>Updated At </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $index = 1; @endphp
                                    @foreach ($payouts as $payout)
                                        <tr>
                                            <td>{{ $index++ }}</td>                                                
                                            <td><a href="{{ route('admin.developer', $payout->user_uuid) }}">{{$payout->user_name}}</a>
                                            </td>
                                            <td>{{ $payout->method_name }}</td>
                                            <td>{{ number_format(round(floatval($payout->amount), 2), 2) }} USD</td>
                                            <td>{{ $payout->email }}</td>
                                            <td>{{ $payout->note }}</td>
                                            <td>{{ date('F jS, Y', strtotime($payout->created_at))}} <br> at  {{ date('h:i A', strtotime($payout->created_at)) }}
                                            </td>
                                            <td>{{ date('F jS, Y', strtotime($payout->updated_at))}} <br> at  {{ date('h:i A', strtotime($payout->updated_at)) }}
                                            </td>
                                            
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="row">
                            <div class="col-auto pagination-wrap">
                                <div class="float-right">
                                    {!! $payouts->appends(compact('perpage'))->links() !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </main>
@endsection
