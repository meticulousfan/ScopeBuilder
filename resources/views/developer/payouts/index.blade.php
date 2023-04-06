@extends('developer.layout.main')
@section('page-title')
    Payouts
@endsection

@section('page-caption')
    My Payouts
@endsection

@section('content')
<link rel="stylesheet" href="{{ asset('ui_assets/css/main.css') }}">
    <header class="header">
        <div class="container">
            <div class="header-inner">
                <div class="header-block with-menu-opener">
                    <button class="menu-opener lg-visible-flex" aria-label="Show navigation">
                        <span class="bar"></span>
                        <span class="bar"></span>
                        <span class="bar"></span>
                    </button>
                    <h1 class="page-caption">My Payouts</h1>
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
                            <p class="card-caption">Total Payouts</p>
                            <p class="card-value">${{ number_format(round(floatval($totalPayouts), 2), 2) }}</p>
                        </div>

                        <div class="balance-info-card">
                            <div class="card-icon">
                                <img src="{{ asset('ui_assets/img/icons/wallet.svg') }}" alt="">
                            </div>
                            <p class="card-caption">Total Pending Payouts</p>
                            <p class="card-value">${{ number_format(round(floatval($pendingPayouts), 2), 2) }}</p>
                        </div>

                        <div class="balance-info-card">
                            <div class="card-icon">
                                <img src="{{ asset('ui_assets/img/icons/wallet.svg') }}" alt="">
                            </div>
                            <p class="card-caption">Total Balance</p>
                            <p class="card-value">${{ number_format(round(floatval($totalBalance), 2), 2) }}</p>
                        </div>

                        <div class="balance-info-card">
                            <div class="card-icon">
                                <img src="{{ asset('ui_assets/img/icons/wallet.svg') }}" alt="">
                            </div>
                            <p class="card-caption">Available for Withdrawal</p>
                            <p class="card-value">${{ number_format(round(floatval($availableBalance), 2), 2) }}</p>
                        </div>

                        @if ($minimumPayoutAmount > $availableBalance || count($payoutmethods) == 0)
                            <button class="btn " data-modal="#error-payout-modal">Request Payout</button>
                        @elseif (Auth::user()->paypal_email || Auth::user()->payoneer_email)
                            <button class="btn " data-modal="#request-payout-modal">Request Payout</button>
                        @else
                            <button class="btn " data-modal="#setup-payment-method-modal">Request Payout</button>
                        @endif
                    </div>
                </div>
            </section>
            <section class="default-section no-top-padding">
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

                    @if (session('error'))
                        <div class="card" style="margin-top: 2rem; margin-bottom: 3px;">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                            <div class="alert-body" style="margin-left: 10px; color: red;">
                                                <i style="top: 0px;" class="fas fa-exclamation-circle"></i>
                                                <span style="margin-left: 3px;">Oops! You've entered invalid amount.
                                                    Please try
                                                    again.</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="section-inner">
                        <div class="table-wrapper">
                            <table class="table no-wrap">
                                <thead>
                                    <tr>
                                        <th>Number</th>
                                        <th>Payout Method </th>
                                        <th>Payout Amount</th>
                                        <th>Created At</th>
                                        <th>Updated At</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $index = 1; @endphp
                                    @foreach ($payouts as $payout)
                                        @php
                                            $status = 'PENDING';
                                            $color='#e5e0e2';
                                            if ($payout->is_paid == 1) {
                                                $status = 'ACCEPTED';
                                                $color='#71cbd5';
                                            }
                                            if ($payout->is_paid == 2) {
                                                $status = 'REJECTED';
                                                $color='#ff91ba';
                                            }
                                        @endphp
                                        <tr>
                                            <td>{{ $index++ }}</td>
                                            <td>{{ $payout->method_name }}</td>
                                            <td>{{ number_format(round(floatval($payout->amount), 2), 2) }} USD</td>
                                            <td>{{ date('F jS, Y', strtotime($payout->created_at)) . ' at ' . date('h:i A', strtotime($payout->created_at)) }}
                                            <td>{{ date('F jS, Y', strtotime($payout->updated_at)) . ' at ' . date('h:i A', strtotime($payout->created_at)) }}
                                            <td>
                                                <span
                                                    style="background-color: {{ $color }};border-radius: 10rem;display: inline-block;padding: 5px 10px;font-size: 12px;font-weight: 500;line-height: 1.2;text-align: center;white-space: nowrap;vertical-align: baseline;">{{ $status }}</span>
                                            </td>
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
                                    {!! $payouts->appends(compact('perpage'))->links() !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </main>

    <div class="modal" id="error-payout-modal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <button class="modal-close" aria-label="Close modal"></button>

                <div class="modal-caption">
                    <div class="mc-icon">
                        <img src="{{ asset('ui_assets/img/icons/wallet.svg') }}" alt="">
                    </div>
                    <h3 class="mc-title">Request Payout</h3>
                    <p class="mc-subtitle">Sorry you cannot request payment <br class="sm-hidden"></p>
                </div>

                <div class="modal-footer">
                    <button class="btn js-modal-close">OK</button>
                </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal" id="request-payout-modal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <button class="modal-close" aria-label="Close modal"></button>

                <div class="modal-caption">
                    <div class="mc-icon">
                        <img src="{{ asset('ui_assets/img/icons/wallet.svg') }}" alt="">
                    </div>
                    <h3 class="mc-title">Request Payout</h3>
                    <p class="mc-subtitle">Please choose between your added payment methods <br class="sm-hidden">to
                        transfer your current balance funds to.</p>
                </div>

                <form action="{{ route('developer.request.payout') }}" method="POST">
                    @csrf
                    <div class="modal-form form">
                        <div class="form-fields">
                            <div class="input-field setting-form-field">
                                <div class="field-label">USD Amount
                                    ({{ number_format(round(floatval($minimumPayoutAmount), 2), 2) }}~{{ $maximumPayoutAmount > $availableBalance
                                        ? number_format(round(floatval($availableBalance), 2), 2)
                                        : number_format(round(floatval($maximumPayoutAmount), 2), 2) }}
                                    )
                                </div>
                                <input type="number" name="amount" value="{{ '0.00' }}"
                                    min="0" max="$availableBalance" step="0.01" required>
                            </div>

                            <div class="form-field input-field select-field setting-form-field">
                                <span>
                                    <label class="field-label">Payment Method</label>
                                    <select name="payment_method">
                                        @foreach ($payoutmethods as $payoutmethod)
                                            <option value="{{ $payoutmethod->id }}">
                                                {{ $payoutmethod->name }}</option>
                                        @endforeach
                                    </select>
                                </span>
                            </div>
                            {{-- <div class="form-field">
                                <div class="fields-group">
                                    <label class="radio">
                                        <input type="radio" name="payment_method" value="paypal" checked
                                            class="visually-hidden">
                                        <span class="fake-label">{{ Auth::user()->paypal_email }}</span>
                                    </label>
                                </div>
                            </div> --}}
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-light js-modal-close">Cancel</button>
                        <button class="btn">Request Payout</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal" id="setup-payment-method-modal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <button class="modal-close" aria-label="Close modal"></button>

                <div class="modal-caption">
                    <div class="mc-icon">
                        <img src="{{ asset('ui_assets/img/icons/wallet.svg') }}" alt="">
                    </div>
                    <h3 class="mc-title">Setup Payment Method</h3>
                    <p class="mc-subtitle">You currently can not transfer your funds since you've not attached a
                        payment
                        method with your account.</p>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-light js-modal-close">Cancel</button>
                    <a href="{{ route('developer.payment.settings') }}" class="btn">Setup Payment Methods</a>
                </div>
            </div>
        </div>
    </div>
@endsection
