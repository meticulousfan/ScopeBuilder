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
                    <h1 class="page-caption">Pendding Payouts</h1>
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
                                            style="text-decoration: none; color: #1890FF;">Pendding
                                            </a></li>
                                    <li><a href="{{ route('admin.payouts.accepted') }}" style="text-decoration: none;">Accepted</a></li>
                                    <li><a href="{{ route('admin.payouts.rejected') }}" style="text-decoration: none;">Rejected</a></li>
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
                                        <th>Created At </th>
                                        <th>Actions</th>
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
                                            <td>{{ date('F jS, Y', strtotime($payout->created_at)) . ' at ' . date('h:i A', strtotime($payout->created_at)) }}
                                            
                                            <td>
                                                <div class="actions">
                                                    <button type="button" class="btn btn-danger btn-small request-act-btn" data-id="{{$payout->id}}" data-email="{{$payout->user_email}}"
                                                    data-modal="#reject-payout-modal">Reject</button>
                                                    <button type="button" class="btn btn-primary btn-small request-act-btn"  data-id="{{$payout->id}}"  data-email="{{$payout->user_email}}"data-modal="#accept-payout-modal">Accept</button>
                                                </div>
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
    <div class="modal" id="accept-payout-modal" tabindex="-1">
       
        <div class="modal-dialog">
            <div class="modal-content">
                <button class="modal-close" aria-label="Close modal"></button>
                <form action="{{ route('admin.payout.action') }}" method="POST">
                    @csrf
                    <input type="hidden" name="request_id" class="request_id">
                    <input type="hidden" name="request_email" class="request_email">
                    <div class="modal-caption">
                        <div class="mc-icon">
                            <img src="{{ asset('ui_assets/img/icons/wallet.svg') }}" alt="">
                        </div>
                        <h3 class="mc-title"><strong>Accept request</strong></h3>
                        <p class="mc-subtitle">Are you sure you wish to accept this request? This action will accept the request and can not be undone.</p>
                            
                    </div>
                    <div class="modal-form form">
                        <div class="form-fields">
                            <div class="input-field setting-form-field">
                                <div class="field-label">Transaction ID / Notes
                                </div>
                                <input type="text" name="note" required>
                                <input type="hidden" name="is_paid" value="1" required>
                            </div>

                        </div>
                    </div>

                    <div class="modal-footer" style="flex-wrap: inherit; padding:0px;border:0px;">
                        <button class="btn btn-light js-modal-close"
                            style="padding: 10px 20px;min-height: 54px;border: 1px solid var(--btn-bg); border-radius: var(--r);background: var(--btn-bg);color: var(--btn-color);">Cancel</button>
                        <button class="btn" type="submit"
                            style="padding: 10px 20px;min-height: 54px;border: 1px solid var(--btn-bg); border-radius: var(--r);background: var(--btn-bg);color: var(--btn-color);">Accept
                            </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal" id="reject-payout-modal" tabindex="-1">
       
        <div class="modal-dialog">
            <div class="modal-content">
                <button class="modal-close" aria-label="Close modal"></button>
                <form action="{{ route('admin.payout.action') }}" method="POST">
                    @csrf
                    <input type="hidden" name="request_id" class="request_id">
                    <input type="hidden" name="request_email" class="request_email">
                    <div class="modal-caption">
                        <div class="mc-icon">
                            <img src="{{ asset('ui_assets/img/icons/wallet.svg') }}" alt="">
                        </div>
                        <h3 class="mc-title"><strong>Reject request</strong></h3>
                        <p class="mc-subtitle">Are you sure you wish to reject this request? This action will reject the request and can not be undone.</p>
                            
                    </div>
                    <div class="modal-form form">
                        <div class="form-fields">
                            <div class="input-field setting-form-field">
                                <div class="field-label">Transaction ID / Notes
                                </div>
                                <input type="text" name="note" required>
                                <input type="hidden" name="is_paid" value="2" required>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer" style="flex-wrap: inherit; padding:0px;border:0px;">
                        <button class="btn btn-light js-modal-close"
                            style="padding: 10px 20px;min-height: 54px;border: 1px solid var(--btn-bg); border-radius: var(--r);background: var(--btn-bg);color: var(--btn-color);">Cancel</button>
                        <button class="btn" type="submit"
                            style="padding: 10px 20px;min-height: 54px;border: 1px solid var(--btn-bg); border-radius: var(--r);background: var(--btn-bg);color: var(--btn-color);">Reject
                            </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $(".request-act-btn").click(function() {
            var id = $(this).data("id");
            var email = $(this).data("email");
            $('.request_id').val(id);
            $('.request_email').val(email);
        })
        
    });
</script>
