@extends('client.layout.main')

@section('page-title')
    {{ __('frontend.questionnaire_response_payment') }}
@endsection

@section('page-caption')
    {{ __('frontend.questionnaire_response_payment') }}
@endsection

@push('ui.style')
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/form.css?ver=' . time()) }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100&family=Roboto:wght@100;300;400;500;700;900&display=swap"
        rel="stylesheet">
@endpush

@section('content')
    <div class="bg-light">
        <header class="header">
            <div class="container-fluid">
                <div class="header-inner px-3">
                    <div class="header-block with-menu-opener">
                        <button class="menu-opener lg-visible-flex" aria-label="Show navigation">
                            <span class="bar"></span>
                            <span class="bar"></span>
                            <span class="bar"></span>
                        </button>
                        <h1 class="page-caption font-weight-bold">
                            <span class="show-on-mobile">{{ __('frontend.questionnaire_response_payment') }}</span>
                            <span class="show-on-web">{{ __('frontend.questionnaire_response_payment') }}</span>
                            <span class="show-on-both">{{ __('frontend.questionnaire_response_payment') }}</span>
                        </h1>
                    </div>
                    <div class="buttons pr-3">
                        <button type="button" value="Save For Later" class="btn btn-primary later-h-btn d-none"
                            id="@if (!auth()->check()) {{ 'savelater' }} @endif">Save For Later</button>
                    </div>
                </div>
            </div>
        </header>
        <main class="page-main" style="font-family: 'Montserrat' !important">
            <div class="page-content">

                <div class="container">
                    <div class="row justify-content-center">

                        <div class="col-md-9 bg-white shadow-sm border-0 pt-4 pb-3 px-3 mb-5">
                            @if (session('success'))
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="alert alert-success" role="alert">
                                            {{ session('success') }}
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if (session('message'))
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="alert alert-success" role="alert">
                                            {{ session('message') }}
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if (session('error'))
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="alert alert-danger" role="alert">
                                            {{ session('error') }}
                                        </div>
                                    </div>
                                </div>
                            @endif
                            <div class="font-weight-bold">
                                <p>You will be charged ${{ number_format($userQuestionnaire->total, 2) }} for
                                    {{ $questionnaire->name }} Questionnaire Responses</p>
                            </div>
                            <div class="card border-0">
                                <form action="{{ route('client.questionnaire.pay', ['id' => $questionnaire->id]) }}"
                                    method="post" id="payment-form">
                                    @csrf
                                    <div class="form-group">
                                        <div class="">
                                            <label for="card-element">
                                                Enter your credit card information
                                            </label>
                                        </div>
                                        <div class="card-body">
                                            <div id="card-element" class="form-control pt-2">
                                                <!-- A Stripe Element will be inserted here. -->
                                            </div>
                                            <!-- Used to display form errors. -->
                                            <div id="card-errors" role="alert"></div>
                                            <input type="hidden" name="questionnaire" value="{{ $questionnaire->id }}" />
                                            <input type="hidden" name="user_questionnaire"
                                                value="{{ $userQuestionnaire->id }}" />
                                            <input type="hidden" name="payment_method" class="payment-method">
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-center">
                                        <button class="btn btn-sm btn-primary"
                                            style="
                                              padding: 10px 20px;
                                              min-height: 54px;
                                              border: 1px solid var(--btn-bg);
                                              border-radius: var(--r);
                                              background: var(--btn-bg);
                                              color: var(--btn-color);
                                            "
                                            type="submit">
                                            {{ __('frontend.proceedToPay') }}
                                        </button>
                                    </div>
                            </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
@endsection

@push('ui.script')
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/form.js?ver=' . time()) }}"></script>
    <script src="{{ asset('js/jquery.priceformat.min.js') }}"></script>
    <style scoped>
        /* Loader */
        .card.loading-active {
            max-height: 600px;
            overflow: hidden;
        }

        .loader-con {
            background: #fff;
            position: absolute;
            top: 0;
            left: 0;
            z-index: 9;
            border-radius: 0.25rem;
        }


        .loader-con #loading {
            margin: 0 auto;
            display: inline-block;
            width: 70px;
            height: 70px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: #002766;
            animation: spin 1s ease-in-out infinite;
            -webkit-animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to {
                -webkit-transform: rotate(360deg);
            }
        }

        @-webkit-keyframes spin {
            to {
                -webkit-transform: rotate(360deg);
            }
        }
    </style>

    <script src="https://js.stripe.com/v3/"></script>
    <script>
        // Create a Stripe client.
        var stripe = Stripe('{{ env('STRIPE_KEY') }}');

        // Create an instance of Elements.
        var elements = stripe.elements();

        // Custom styling can be passed to options when creating an Element.
        // (Note that this demo uses a wider set of styles than the guide below.)
        var style = {
            base: {
                color: '#32325d',
                lineHeight: '18px',
                fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
                fontSmoothing: 'antialiased',
                fontSize: '16px',
                '::placeholder': {
                    color: '#aab7c4'
                }
            },
            invalid: {
                color: '#fa755a',
                iconColor: '#fa755a'
            }
        };

        // Create an instance of the card Element.
        var card = elements.create('card', {
            style: style
        });

        // Add an instance of the card Element into the `card-element` <div>.
        card.mount('#card-element');

        let paymentMethod = null

        // Handle real-time validation errors from the card Element.
        card.addEventListener('change', function(event) {
            var displayError = document.getElementById('card-errors');
            if (event.error) {
                displayError.textContent = event.error.message;
            } else {
                displayError.textContent = '';
            }
        });

        // Handle form submission.
        var form = document.getElementById('payment-form');
        form.addEventListener('submit', function(event) {
            // event.preventDefault();

            $('button.pay').attr('disabled', true)
            if (paymentMethod) {
                return true
            }
            stripe.confirmCardSetup(
                "{{ $intent->client_secret }}", {
                    payment_method: {
                        card: card,
                        billing_details: {
                            name: '{{ Auth::user()->name }}'
                        }
                    }
                }
            ).then(function(result) {
                if (result.error) {
                    $('#card-errors').text(result.error.message)
                    $('button.pay').removeAttr('disabled')
                } else {
                    paymentMethod = result.setupIntent.payment_method
                    $('.payment-method').val(paymentMethod)
                    $('#payment-form').submit()
                }
            })
            return false

            // stripe.createToken(card).then(function(result) {
            //     if (result.error) {
            //         // Inform the user if there was an error.
            //         var errorElement = document.getElementById('card-errors');
            //         errorElement.textContent = result.error.message;
            //     } else {
            //         // Send the token to your server.
            //         stripeTokenHandler(result.token);
            //     }
            // });
        });

        // Submit the form with the token ID.
        // function stripeTokenHandler(token) {
        //     // Insert the token ID into the form so it gets submitted to the server
        //     var form = document.getElementById('payment-form');
        //     var hiddenInput = document.createElement('input');
        //     hiddenInput.setAttribute('type', 'hidden');
        //     hiddenInput.setAttribute('name', 'stripeToken');
        //     hiddenInput.setAttribute('value', token.id);
        //     form.appendChild(hiddenInput);

        //     // Submit the form
        //     form.submit();
        // }
    </script>
@endpush
