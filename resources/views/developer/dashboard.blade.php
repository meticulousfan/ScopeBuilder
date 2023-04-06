@extends('developer.layout.main')
@section('page-title')
    Dashboard
@endsection

@section('page-caption')
Dashboard
@endsection

@section('content')

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
                        <h1 class="page-caption" style="margin: 0px!important;">Earning</h1>
                        <div
                            style="width: 47px;height:26px; margin: 1px 16px;background: #1890FF 0% 0% no-repeat padding-box;border-radius:13px;opacity:1; text-align:center;align-items: center; display: grid;">
                            <div style="font-size:13px;color:white;">{{ $totalNum }}</div>
                        </div>
                    </span>
                </div>
            </div>
        </div>
    </header>
    <main class="page-main">
        <div class="page-content">
            <section class="referral-header-section">
                <div class="container">
                    <div class="section-inner">
                        <div class="balance-info-card">
                            <div class="card-icon">
                                <img src="{{ asset('ui_assets/img/icons/wallet.svg') }}" alt="">
                            </div>
                            <p class="card-caption">Total Earnings</p>
                            <p class="card-value">${{ number_format(round(floatval($totalEarning), 2), 2) }}</p>
                        </div>

                        <div class="balance-info-card">
                            <div class="card-icon">
                                <img src="{{ asset('ui_assets/img/icons/wallet.svg') }}" alt="">
                            </div>
                            <p class="card-caption">Current Balance</p>
                            <p class="card-value">${{ number_format(round(floatval($currentBalance), 2), 2) }}</p>
                        </div>
                    </div>
                </div>
            </section>
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
                            <table class="table clients_table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Type</th>
                                        <th>Project</th>
                                        <th>Amount</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $index=0; @endphp
                                    @foreach ($histories as $earning)
                                        <tr>
                                            <td>{{ $index++ }}</td>
                                            <td>{{ $earning->type==0?'Referral':'Call'}}</td>
                                            <td>{{ $earning->project_name }}</td>
                                            <td>{{ number_format(round(floatval($earning->amount), 2), 2) }} USD</td>
                                            <td>{{ date('F jS, Y', strtotime($earning->updated_at)) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="row">
                            <div class="col-auto pagination-wrap">
                                <div class="float-right">
                                    {!! $histories->appends(compact('perpage'))->links() !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </main>
@endsection


@push('ui.script')
    <script type="text/javascript">
        localStorage.clear();
    </script>
@endpush