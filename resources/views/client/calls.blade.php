@extends('client.layout.main')
@section('page-title')
    Calls
@endsection

@section('page-caption')
    Calls
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
                    <span style="display: flex;align-items: center;">
                        <h1 class="page-caption" style="margin: 0px!important;">Calls History</h1>
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
                    <div style=" display: flex; justify-content: flex-end; ">
                        <div>
                            <label>Balance&nbsp;:&nbsp; <strong>{{ $remainTime }} Minutes</strong>  </label>                            
                        </div>
                    </div>

                    <div class="section-inner">
                        <div class="table-wrapper">
                            <table class="table clients_table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Project</th>
                                        <th>Duration </th>
                                        <th>Created</th>
                                        <th>Duration Used</th>
                                        <th>Amount Deposited</th>
                                        <th>Amount Used</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php   $index = 0; @endphp
                                    @foreach ($calls as $call)
                                        @php
                                            $used_amount = 0;
                                            if(isset($call->start_time) && isset($call->end_time)){
                                                $diff = ceil(abs(strtotime($call->end_time) - strtotime($call->start_time))/60);
                                                $used_amount = $diff * $call->pricePerMinute;
                                            }
                                        @endphp
                                        <tr>
                                            <td>{{ $index++ }}</td>
                                            <td>{{ $call->project_name }} </td>
                                            <td>{{ $call->duration }} Minutes</td>
                                            <td>{{ date('F jS, Y', strtotime($call->start_time)) }}</td>
                                            <td>{{ $call->diff_time??'00:00:00' }} </td>
                                            <td>{{ number_format(round(floatval($call->amount), 2), 2) }} USD</td>
                                            <td>{{ number_format(round(floatval($used_amount), 2), 2) }} USD</td>

                                            <td>{{ date('F jS, Y', strtotime($call->created_at)) }}</td>
                                            <td>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="row">
                            <div class="col-auto pagination-wrap">
                                <div class="float-right">
                                    {!! $calls->appends(compact('perpage'))->links() !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </main>
@endsection
