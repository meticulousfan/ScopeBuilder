@if (isset($project))
    <div class="calling-wrap">
        <a class="calling-icon-box" id="callingIconOutside" href="javascript:void(0)">
            <div class="calling-dot red-dot"></div>
            <img src="/ui_assets/img/icons/iconmonstr-delivery-8.svg" alt="call-ringing-icon">
        </a>
    </div>
    <div class="calling-msg">
        <div class="calling-msg-content">
            NEED A HAND!?
        </div>
    </div>

    <div class="modal custom-modal calling" tabindex="-1" id="calling-modal" role="dialog">
        <div class="calling-wrap">
            <a class="calling-icon-box calling" href="javascript:void(0)">
                <div class="calling-dot red-dot"></div>
                <img src="/ui_assets/img/icons/iconmonstr-delivery-8.svg" alt="call-icon">
            </a>
        </div>
        <div class="calling-msg calling">
            <div class="calling-msg-content calling">
                NEED A HAND!?
            </div>
        </div>
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">

                    <div id="newMeeting" class="calling-modal-1">
                        <div class="custom-heading block-h">
                            <div class="icon-wrap phone-icon-wrap">
                                <img src="/ui_assets/img/icons/phone.svg" alt="call-icon">
                            </div>
                            <div class="custom-heading-text">{{ __('frontend.hireSupportAgent') }}</div>
                        </div>
                        <div class="custom-heading ">
                            <div class="custom-heading-content">{{ __('frontend.meetingContent') }}</div>
                        </div>
                        <div class="duration-block block-h block-border">
                            <div class="sec-heading">{{ __('frontend.meetingDuration') }}</div>
                            <div class="form-field input-field select-field">
                                <select name="duration_select" id="duration_select" class="no-border-select" v-model="meetingDuration">
                                    <option value="30">30 {{ __('frontend.minutes') }}</option>
                                    <option value="45">45 {{ __('frontend.minutes') }}</option>
                                    <option value="50">50 {{ __('frontend.minutes') }}</option>
                                    <option value="55">55 {{ __('frontend.minutes') }}</option>
                                    <option value="60">60 {{ __('frontend.minutes') }}</option>
                                </select>
                            </div>
                        </div>
                        @if(isset($remainTime)  && $remainTime >0)
                        <div style="color: #1890FF; margin: 10px; font-weight: 500;">{{$remainTime  }} minutes will be from unused time</div>
                        <input type="hidden" value="{{$remainTime??'0'}}" id="remain_time">
                        @endif
                        <div class="duration-block block-h block-border">
                            <div class="sec-heading">{{ __('frontend.meetingSearch') }}</div>
                            <div class="form">
                                <div class="input-field">
                                    <input type="text" class="input" value="" name="search" id="searchStr"
                                        placeholder="{{ __('frontend.meetingSearchPlaceholder') }}">
                                </div>
                            </div>
                        </div>
                        <div class="invite-block block-h block-border">
                            <div class="sec-heading">{{ __('frontend.inviteDeveloper') }}</div>
                            <div class="round-rows-block" id="developerList">
                            </div>
                        </div>
                        <div class="button-block">
                            <a href="javascript:void(0)" onclick="showJoinMeeting()"
                                class="btn custom-btn-primary btn-block no-height">{{ __('frontend.next') }}</a>
                        </div>
                    </div>

                    <div id="joinMeeting" class="calling-modal-2 d-none">
                        <div class="custom-heading block-h">
                            <div class="icon-wrap phone-icon-wrap">
                                <img src="/ui_assets/img/icons/phone.svg" alt="call-icon">
                            </div>
                            <div class="custom-heading-text">{{ __('frontend.joinMeeting') }}</div>
                        </div>
                        <div class="duration-block block-h block-border">
                            <div class="sec-heading">{{ __('frontend.displayName') }}</div>
                            <div class="duration-text">Paul Elliott</div>
                        </div>
                        <div class="invite-block block-h block-border">
                            <div class="sec-heading">{{ __('frontend.meetingSettings') }}</div>
                            <div class="round-rows-block">
                                <div class="round-row radio-row">
                                    <div class="option-text">{{ __('frontend.useWebcam') }}</div>
                                    <div class="option-action">
                                        <input type="radio" value="1" v-model="webCam">
                                    </div>
                                </div>
                                <div class="round-row radio-row">
                                    <div class="option-text">{{ __('frontend.noWebcam') }}</div>
                                    <div class="option-action">
                                        <input type="radio" value="0" v-model="webCam">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="button-block">
                            <a href="javascript:void(0)" onclick="showMeetingPayment()"
                                class="btn custom-btn-primary btn-block no-height">{{ __('frontend.next') }}</a>
                                
                        </div>
                    </div>

                    <div id="meetingPayment" class="calling-modal-3 p-3 text-center d-none">
                        <div class="wallet-icon-block">
                            <div class="wallet-icon-cont pay-primary">
                                <svg class="btn-icon">
                                    <use xlink:href="{{ asset('ui_assets/img/icons-sprite.svg') }}#wallet"></use>
                                </svg>
                            </div>
                        </div>
                        <div class="custom-heading-text custom-heading-lg mb-3">{{ __('frontend.meetingPayment') }}
                        </div>
                        <div class="seconary-paragraph mb-4">{{ __('frontend.meetingCreatedSuccessMessage') }}</div>
                        <div class="pay-amount">$@{{ (meetingDuration-remainTime) * price }}.00</div>
                        <div class="seconary-paragraph mb-3">@{{ meetingDuration-remainTime }} {{ __('frontend.mins') }} *
                            @{{ price.toFixed(2) }}
                            {{ __('frontend.usdPerMin') }}</div>
                            
                        @if(isset($remainTime)  && $remainTime >0)
                        <div style="color: #1890FF; margin: 10px; font-weight: 500;">{{$remainTime  }} minutes will be from unused time</div>
                        <input type="hidden" value="{{$remainTime??'0'}}" id="remain_time">
                        @endif
                        <div class="button-block">
                            <!-- <a href="javascript:void(0)" onclick="showPaymentSuccess()" class="btn custom-btn-primary btn-block paybtn-lg">{{ __('frontend.proceedToPay') }}</a> -->
                            <a href="javascript:void(0)" onclick="showPayment()"
                                class="btn custom-btn-primary btn-block paybtn-lg">{{ __('frontend.proceedToPay') }}</a>
                        </div>
                    </div>

                    <div id="payment" class="calling-modal-3 p-3 text-center d-none">
                        <div class="form-group col-md-12">
                            <!-- <label for="name" class="small">Card holder name</label> -->
                            <input class="form-control" type="text" name="name" placeholder="Card holder name"
                                id="card-holder-name">
                            <div class="invalid-feedback" id="nameError"></div>
                        </div>
                        <!-- Stripe Elements Placeholder -->
                        <div class="form-group col-md-12">
                            <div id="card-element" class="form-control mb-4"></div>
                        </div>
                        <div class="button-block">
                            <a href="javascript:void(0)" id="card-button"
                                class="btn custom-btn-primary btn-block paybtn-lg">{{ __('frontend.proceedToPay') }}</a>
                        </div>
                    </div>

                    <div id="paymentLoading" class="calling-modal-3 p-3 text-center d-none">
                        <div class="wallet-icon-block">
                            <div id="loading"></div>
                        </div>
                        <div class="custom-heading-text custom-heading-lg mb-3">{{ __('frontend.loadingPayment') }}
                        </div>
                        <div class="seconary-paragraph mb-4">{{ __('frontend.loadingPaymentMessage') }}</div>
                    </div>

                    <div id="paymentSuccess" class="calling-modal-3 p-3 text-center d-none">
                        <div class="wallet-icon-block">
                            <div class="wallet-icon-cont pay-success">
                                <svg class="btn-icon">
                                    <use xlink:href="{{ asset('ui_assets/img/icons-sprite.svg') }}#wallet"></use>
                                </svg>
                            </div>
                        </div>
                        <div class="custom-heading-text custom-heading-lg mb-3">{{ __('frontend.paymentSuccess') }}
                        </div>
                        <div class="seconary-paragraph mb-4">{{ __('frontend.meetingPaymentSuccessMessage') }}</div>
                        <div class="button-block">
                            <a href="javascript:void(0)" @click="createMeeting()"
                                class="btn custom-btn-primary btn-block paybtn-lg">{{ __('frontend.continue') }}</a>
                        </div>
                    </div>

                    <div id="paymentFailed" class="calling-modal-3 p-3 text-center d-none">
                        <div class="wallet-icon-block">
                            <div class="wallet-icon-cont pay-failed">
                                <svg class="btn-icon">
                                    <use xlink:href="{{ asset('ui_assets/img/icons-sprite.svg') }}#wallet"></use>
                                </svg>
                            </div>
                        </div>
                        <div class="custom-heading-text custom-heading-lg mb-3">{{ __('frontend.paymentFailed') }}
                        </div>
                        <div class="seconary-paragraph mb-4">{{ __('frontend.paymentFailedMessage') }}</div>
                        <div class="button-block">
                            <a href="javascript:void(0)" onclick="showPayment()"
                                class="btn custom-btn-primary btn-block paybtn-lg">{{ __('frontend.goBack') }}</a>
                        </div>
                    </div>

                    <div id="joinCall" class="calling-modal-4 text-center d-none">
                        <div class="timer-block">@{{ convertTimeString() }}</div>
                        <div class="callers-block-wrap">
                            <div class="callers-block-single">
                                <div class="caller-user">
                                    <div class="caller-user-circle single-circle">
                                        <div class="caller-user-circle-inner">PE</div>
                                    </div>
                                    <div class="caller-fullname">Paul Elliott</div>
                                </div>
                            </div>
                        </div>
                        <div class="button-block">
                            <input type="button" @click="goToMeeting()"
                                class="btn btn-block custom-btn-primary btn-mdm" disabled="disabled"
                                value="{{ __('frontend.waitingDev') }}">
                        </div>
                    </div>

                    <div id="callOngoing" class="calling-modal-4 text-center d-none">
                        <div class="timer-block" id="timer">@{{ convertTimeString() }}</div>
                        <div class="callers-block-wrap">
                            <div class="scroll-caller-btn-wrap">
                                <a href="javascript:void(0)" class="scroll-caller prev-caller" id="clientPrevCaller">
                                    < </a>
                            </div>
                            <div class="callers-block" id="callersClientSlider"
                                style="display: flex;justify-content:space-between">

                            </div>
                            <div class="scroll-caller-btn-wrap">
                                <a href="javascript:void(0)" class="scroll-caller next-caller"
                                    id="clientNextCaller">></a>
                            </div>
                        </div>
                        <div id="extendDuration" class="extend-duration-block d-none">
                            <div id="extendDurationBox" class="extend-duration-inner">
                                <div class="custom-heading block-h">
                                    <div class="icon-wrap phone-icon-wrap">
                                        <img src="/ui_assets/img/icons/phone.svg" alt="call-icon">
                                    </div>
                                    <div class="custom-heading-text">{{ __('frontend.extendDuration') }}</div>
                                </div>
                                <div class="extend-duration-block block-h block-border">
                                    <a @click="calculateDuration('decrease')" href="javascript:void(0)"
                                        class="btn square-btn duration-btn dr-minus"><img
                                            src="/ui_assets/img/icons/minus.svg" alt="decrease-icon"></a>
                                    <div class="extend-text-container">
                                        <div class="sec-heading">{{ __('frontend.incDecMinutes') }}</div>
                                        <div class="duration-text">@{{ extendedDuration }}
                                            {{ __('frontend.minutes') }}</div>
                                    </div>
                                    <a @click="calculateDuration('increase')" href="javascript:void(0)"
                                        class="btn square-btn duration-btn dr-plus"><img
                                            src="/ui_assets/img/icons/add.svg" alt="increase-icon"></a>
                                </div>
                                <div class="button-block">
                                    <!-- <a href="javascript:void(0)" class="btn custom-btn-primary btn-block btn-mdm">{{ __('frontend.saveChanges') }}</a> -->
                                    <a href="javascript:void(0)" @click="showPaymentForExtendedDuration()"
                                        class="btn custom-btn-primary btn-block btn-mdm">{{ __('frontend.proceedToPay') }}</a>
                                </div>
                                <div class="seconary-paragraph">{{ __('frontend.extendChargesNote') }}
                                    (@{{ extendedDuration }} Mins * @{{ price }} USD / Min)
                                    {{ __('frontend.extendChargesNoteSecondPart') }}</div>
                            </div>
                            <div id="extendPayment" class="extend-duration-inner d-none w-100">
                                <div class="form-group col-md-12">
                                    <!-- <label for="name" class="small">Card holder name</label> -->
                                    <input class="form-control" type="text" name="name"
                                        placeholder="Card holder name" id="extend-card-holder-name">
                                    <div class="invalid-feedback" id="nameError"></div>
                                </div>
                                <!-- Stripe Elements Placeholder -->
                                <div class="form-group col-md-12">
                                    <div id="extend-card-element" class="form-control mb-4"></div>
                                </div>
                                <div class="button-block">
                                    <a href="javascript:void(0)" id="extend-card-button"
                                        class="btn custom-btn-primary btn-block paybtn-lg">{{ __('frontend.proceedToPay') }}</a>
                                </div>
                            </div>

                            <div id="extendPaymentLoading" class="extend-duration-inner d-none w-100">
                                <div class="wallet-icon-block">
                                    <div id="extendLoading"></div>
                                </div>
                                <div class="custom-heading-text custom-heading-lg mb-3">
                                    {{ __('frontend.loadingPayment') }}
                                </div>
                                <div class="seconary-paragraph mb-4">{{ __('frontend.loadingPaymentMessage') }}</div>
                            </div>

                            <div id="extendPaymentSuccess" class="extend-duration-inner d-none w-100">
                                <div class="wallet-icon-block">
                                    <div class="wallet-icon-cont pay-success">
                                        <svg class="btn-icon">
                                            <use xlink:href="{{ asset('ui_assets/img/icons-sprite.svg') }}#wallet">
                                            </use>
                                        </svg>
                                    </div>
                                </div>
                                <div class="custom-heading-text custom-heading-lg mb-3">
                                    {{ __('frontend.paymentSuccess') }}
                                </div>
                                <div class="seconary-paragraph mb-4">{{ __('frontend.meetingPaymentSuccessMessage') }}
                                </div>
                                <div class="button-block">
                                    <a href="javascript:void(0)" @click="addExtendDuration()"
                                        class="btn custom-btn-primary btn-block paybtn-lg">{{ __('frontend.continue') }}</a>
                                </div>
                            </div>

                            <div id="extendPaymentFailed" class="extend-duration-inner d-none w-100">
                                <div class="wallet-icon-block">
                                    <div class="wallet-icon-cont pay-failed">
                                        <svg class="btn-icon">
                                            <use xlink:href="{{ asset('ui_assets/img/icons-sprite.svg') }}#wallet">
                                            </use>
                                        </svg>
                                    </div>
                                </div>
                                <div class="custom-heading-text custom-heading-lg mb-3">
                                    {{ __('frontend.paymentFailed') }}
                                </div>
                                <div class="seconary-paragraph mb-4">{{ __('frontend.paymentFailedMessage') }}</div>
                                <div class="button-block">
                                    <a href="javascript:void(0)" onclick="showExtendPayment()"
                                        class="btn custom-btn-primary btn-block paybtn-lg">{{ __('frontend.goBack') }}</a>
                                </div>
                            </div>
                        </div>
                        <div id="addcollaboratorDiv">
                        </div>
                        <div class="button-block extend-call-btns" style="justify-content: space-around;">
                            @if ($extendDuration)
                                <a href="javascript:void(0)" onclick="showExtendDuration()"
                                    class="btn btn-block custom-btn-primary btn-mdm">{{ __('frontend.extendDuration') }}</a>
                            @endif
                            <a href="javascript:void(0)" onClick="endMeeting()"
                                class="btn custom-btn-danger btn-mdm end-call" id="endcallBtn"
                                style="width: {{ $extendDuration ? 'unset' : '100%' }}">{{ __('frontend.end') }}</a>
                        </div>
                    </div>

                    <div id="meetingEnded" class="calling-modal-2 d-none">
                        <div class="custom-heading block-h pl-0 pr-0">
                            <div class="icon-wrap phone-icon-wrap">
                                <img src="/ui_assets/img/icons/phone.svg" alt="call-icon">
                            </div>
                            <div class="custom-heading-text">{{ __('frontend.meetingFinished') }}</div>
                        </div>
                        <div class="pb-2 pt-2">{{ __('frontend.feedbackNote') }}</div>
                        <div class="duration-block block-h block-border">
                            <div class="sec-heading">{{ __('frontend.review') }}</div>
                            <textarea class="form-control feedback" placeholder="{{ __('frontend.writeSomething') }}"></textarea>
                        </div>
                        <div class="duration-block block-h block-border">
                            <div class="sec-heading">{{ __('frontend.rating') }}</div>
                            <div class="duration-text feedback-stars">
                                <i class="fa fa-star filled"></i>
                                <i class="fa fa-star filled"></i>
                                <i class="fa fa-star filled"></i>
                                <i class="fa fa-star filled"></i>
                                <i class="fa fa-star"></i>
                            </div>
                        </div>
                        <div class="button-block">
                            <button type="button" class="btn custom-btn-primary btn-block no-height"
                                onClick="checkFeedbackSubmission()">{{ __('frontend.submit') }}</button>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    @push('ui.script')
        <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css" />
        <script type="text/javascript" src="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
        <script src="https://js.stripe.com/v3/"></script>
        <script src="https://cdn.jsdelivr.net/npm/vue@2/dist/vue.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

        <script>
            var developerId;
            var transactionId=0;
            var vueApp = new Vue({
                el: '#calling-modal',
                data: {
                    remainTime:{{ isset($remainTime) ? $remainTime : 0 }},
                    meetingDuration: {{ isset($meetingRoom) ? $meetingRoom->duration : 30 }},
                    extendedDuration: 10,
                    webCam: '',
                    payForExtendedDuration: false,
                    projectID: '{{ $project->uuid }}',
                    price: {{ $pricePerCallMinute }},
                },
                methods: {
                    sendPayment(paymentMethod) {
                        var self = this;
                        var amountToPay;

                        if (self.payForExtendedDuration) {
                            amountToPay = self.extendedDuration * self.price;
                        } else {
                            amountToPay = (self.meetingDuration-self.remainTime) * self.price;
                        }

                        axios.post('/client/finalize-payment', {
                                paymentMethod: paymentMethod,
                                amount: amountToPay
                            }, {
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                }
                            }).then(function(response) {
                                console.log(response);
                                var status = 0;
                                if (response.data.status == 'succeeded') {
                                    status = 2;
                                    showPaymentSuccess()
                                } else {
                                    status = 1;
                                    showPaymentFailed()
                                }

                                axios.post('/client/save-transaction', {
                                    project_id: '{{ $project->uuid }}',
                                    type: 2,
                                    amount: amountToPay,
                                    stripe_id: paymentMethod.id,
                                    status: status

                                }, {
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                    }
                                }).then(function(response) {
                                    console.log(response.data);
                                    transactionId = response.data.id;
                                });
                            })
                            .catch(function(error) {
                                console.log(error);
                                showPaymentFailed()
                            });

                    },

                    sendExtendPayment(paymentMethod) {
                        var self = this;
                        var amountToPay;
                        amountToPay = self.extendedDuration * self.price;

                        axios.post('/client/finalize-payment', {
                                paymentMethod: paymentMethod,
                                amount: amountToPay
                            }, {
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                }
                            }).then(function(response) {
                                console.log(response);
                                if (response.data.status == 'succeeded') {
                                    showExtendPaymentSuccess()
                                } else {
                                    showExtendPaymentFailed()
                                }
                            })
                            .catch(function(error) {
                                console.log(error);
                                showExtendPaymentFailed()
                            });

                    },

                    convertTimeString() {
                        var minutes = parseInt(this.meetingDuration % 60, 10);
                        var hours = parseInt(this.meetingDuration / 60, 10);

                        minutes = minutes < 10 ? "0" + minutes : minutes;
                        hours = hours < 10 ? "0" + hours : hours;

                        return hours + ":" + minutes + ":00"

                    },

                    calculateDuration(method) {
                        if (method == 'increase') {
                            this.extendedDuration += 10
                        } else {
                            if (this.extendedDuration > 10) {
                                this.extendedDuration -= 10
                            }

                        }
                    },

                    showPaymentForExtendedDuration() {
                        $("#extendDurationBox").addClass("d-none");
                        $("#extendPayment").removeClass("d-none");
                    },

                    createMeeting() {
                        $("#paymentSuccess").addClass("d-none");
                        $("#joinCall").removeClass("d-none");
                        $("#calling-modal").addClass("ongoing-call");

                        var self = this;

                        var meetingDuration;

                        if (self.payForExtendedDuration) {
                            meetingDuration = self.extendedDuration;
                        } else {
                            meetingDuration = self.meetingDuration;
                        }

                        axios.post('/api/meeting/create', {
                                developer_id: developerId,
                                meeting_id: self.projectID,
                                duration: meetingDuration,
                                transaction_id: transactionId
                            }, {
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                }
                            }).then(function(response) {
                                // Create BBB meeting here
                                axios.post('/api/meeting/start', {
                                        meeting_id: response.data.data.meeting.bbb_meeting_id
                                    }, {
                                        headers: {
                                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                        }
                                    }).then(function(response) {
                                        console.log(response.data);
                                    })
                                    .catch(function(error) {
                                        console.log(error.response);
                                    });
                            })
                            .catch(function(error) {
                                console.log(error.response);
                            });
                    },

                    goToMeeting() {
                        var self = this;
                        location.href = "/client/call/" + self.projectID;
                    },
                    
                    addcollaborator(){
                        axios.post('/client/projects/adduserbyid', {
                            project_id: {{ isset($meetingRoom) ? $meetingRoom->project_id:'0' }},
                            developer_id: {{ isset($meetingRoom) ? $meetingRoom->developer_id:'0' }},
                        }, {
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        }).then(function(response) {
                            $('#addcollaboratorDiv').html(``);
                            $('#endcallBtn').css('width','100%');
                        })
                    },

                    addExtendDuration() {
                        var self = this;
                        developerId = 52;
                        axios.post('/api/meeting/extend', {
                            developer_id: developerId,
                            meeting_id: self.projectID,
                            extendDuration: self.extendedDuration
                        }, {
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        }).then(function(response) {
                            var time_string = $("#timer").text();
                            var split_time = time_string.split(':');
                            var seconds = (+split_time[0]) * 60 * 60 + (+split_time[1] + self
                                .extendedDuration) * 60 + (+
                                split_time[2]);

                            var str = new Date(seconds * 1000).toISOString().substring(11, 19);
                            $("#timer").text(str);

                            $("#extendPaymentSuccess").addClass("d-none");
                            $("#extendDurationBox").removeClass("d-none");
                            $("#extendDuration").addClass("d-none");
                        })

                    },

                }
            })
        </script>

        <script>
            var handler;
            $(document).ready(function() {
                // Remove Red Dot
                $(".calling-dot").removeClass("red-dot");
                $("#callersClientSlider").slick({
                    infinite: false,
                    slidesToShow: 2,
                    slidesToScroll: 2,
                    prevArrow: $("#clientPrevCaller"),
                    nextArrow: $("#clientNextCaller"),
                });
            });
        </script>
        <script>
            window.PUSHER_APP_KEY = '{{ config('broadcasting.connections.pusher.key') }}';
            window.APP_DEBUG = {{ config('app.debug') ? 'true' : 'false' }};
        </script>
        <script src="{{ asset('js/app.js') }}"></script>
        <script>
            const publicKey = '{{ env('STRIPE_KEY') }}'
            const stripe = Stripe(publicKey);

            const elements = stripe.elements();
            const extendElements = stripe.elements();
            // const cardElement = elements.create('card');

            var style = {
                base: {
                    iconColor: '#c4f0ff',
                    color: '#32325d',
                    fontWeight: '500',
                    fontFamily: 'Roboto, Open Sans, Segoe UI, sans-serif',
                    fontSize: '16px',
                    fontSmoothing: 'antialiased',
                    ':-webkit-autofill': {
                        color: '#fce883',
                    },
                    '::placeholder': {
                        color: '#87BBFD',
                    },
                },
                invalid: {
                    // iconColor: '#fa755a',
                    // color: '#fa755a',
                },
            };

            var cardElement = elements.create('card', {
                style: style,
                hidePostalCode: true
            });

            var extendCardElement = extendElements.create('card', {
                style: style,
                hidePostalCode: true
            });

            // cardElement.mount('#card-element');

            cardElement.mount('#card-element');
            extendCardElement.mount('#extend-card-element');

            const cardHolderName = document.getElementById('card-holder-name');
            const extendCardHolderName = document.getElementById('extend-card-holder-name');

            const cardButton = document.getElementById('card-button');
            const extendCardButton = document.getElementById('extend-card-button');



            cardButton.addEventListener('click', async (e) => {
                const {
                    paymentMethod,
                    error
                } = await stripe.createPaymentMethod(
                    'card', cardElement, {
                        billing_details: {
                            name: cardHolderName.value
                        }
                    }
                );

                if (error) {
                    // Display "error.message" to the user...
                    console.log(error)
                } else {
                    // The card has been verified successfully...
                    console.log(paymentMethod)
                    showPaymentLoading()
                    this.vueApp.sendPayment(paymentMethod);
                }
            });

            extendCardButton.addEventListener('click', async (e) => {
                const {
                    paymentMethod,
                    error
                } = await stripe.createPaymentMethod(
                    'card', extendCardElement, {
                        billing_details: {
                            name: extendCardHolderName.value
                        }
                    }
                );

                if (error) {
                    // Display "error.message" to the user...
                    console.log(error)
                } else {
                    // The card has been verified successfully...
                    console.log(paymentMethod)
                    showExtendPaymentLoading()
                    this.vueApp.sendExtendPayment(paymentMethod);
                }
            });


            var developerList = [];
            $(document).ready(function() {
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                $.ajax({
                    type: "POST",
                    dataType: "json",
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    url: "{{ route('client.developers') }}",
                    data: {
                        'project_uuid': '{{ $project->uuid }}',
                    },
                    success: function(data) {
                        developerList = data;
                        changeList();
                    }
                });
            });
            $("#searchStr").on("input", function() {
                changeList();
            });

            function select_developer(val) {
                developerId = val;
            }
            var onlineUsers = [];

            function changeList() {
                var limit = {{ $numberOfDevsAvailable ?? 50 }};
                var searchStr = $('#searchStr').val();
                var developerListContent = '';
                var num = 0;
                for (var i = 0; i < developerList.length; i++) {
                    var one = developerList[i];
                    var dev_id = one['id'];
                    if (onlineUsers.includes(dev_id)) {
                        var color = 'green';
                        var rating = one['rating'];
                        if (rating == 0) {
                            color = 'green';
                            rating = 'New';
                        } else if (rating < 50) {
                            color = 'red';
                            rating += '% Success Rate';
                        } else if (rating < 76) {
                            color = '#afaf0c';
                            rating += '% Success Rate';
                        } else if (rating < 90) {
                            color = 'orange';
                            rating += '% Success Rate';
                        } else {
                            color = 'green';
                            rating += '% Success Rate';
                        }

                        //
                        if ((searchStr == '' || one['name'].toLowerCase().indexOf(searchStr.toLowerCase()) != -1) &&
                            num < limit) {
                            num++;
                            console.log(onlineUsers);

                            developerListContent +=
                                `<div class="round-row radio-row" style="justify-content: space-between;">
                                        <div class="invitee-user-col1">
                                            <div class="profile-pic-wrap">
                                            <img src="{{ asset('ui_assets/img/content-images/user-avatar.jpg') }}" alt="` +
                                one['name'] + `">
                                            </div>
                                        </div>
                                        <div class="invitee-user-col2">
                                            <div class="username">` + one['name'] + `</div>
                                            <div class="user-skill">Web & Mobile Apps</div>
                                            <div class="success-rate" style="color: ` + color + `;">` + rating + `</div>
                                        </div>
                                        <div class="invitee-user-col3">
                                            <span class="success-rate" style="color: ` + color + `;">` + rating + `</span>
                                        </div>
                                        <div class="invitee-user-col4">
                                            <span class="available" id="online_dev` + dev_id +
                                `">{{ __('frontend.availableNow') }}</span>
                                        </div>
                                        <div class="invitee-user-col5">
                                            <input type="radio" name="invitee_dev"  onclick="select_developer(this.value)" value="` +
                                dev_id + `">
                                        </div>
                                        </div>
                                        `;
                        }
                    }
                }

                for (var i = 0; i < developerList.length; i++) {
                    var one = developerList[i];
                    var dev_id = one['id'];
                    if (!onlineUsers.includes(dev_id)) {
                        var color = 'green';
                        var rating = one['rating'];
                        if (rating == 0) {
                            color = 'green';
                            rating = 'New';
                        } else if (rating < 50) {
                            color = 'red';
                            rating += '% Success Rate';
                        } else if (rating < 76) {
                            color = '#afaf0c';
                            rating += '% Success Rate';
                        } else if (rating < 90) {
                            color = 'orange';
                            rating += '% Success Rate';
                        } else {
                            color = 'green';
                            rating += '% Success Rate';
                        }

                        //
                        if ((searchStr == '' || one['name'].toLowerCase().indexOf(searchStr.toLowerCase()) != -1) &&
                            num < limit) {
                            num++;

                            developerListContent +=
                                `<div class="round-row radio-row" style="justify-content: space-between;">
                                    <div class="invitee-user-col1">
                                        <div class="profile-pic-wrap">
                                        <img src="{{ asset('ui_assets/img/content-images/user-avatar.jpg') }}" alt="` +
                                one['name'] + `">
                                        </div>
                                    </div>
                                    <div class="invitee-user-col2">
                                        <div class="username">` + one['name'] + `</div>
                                        <div class="user-skill">Web & Mobile Apps</div>
                                        <div class="success-rate" style="color: ` + color + `;">` + rating + `</div>
                                    </div>
                                    <div class="invitee-user-col3">
                                        <span class="success-rate" style="color: ` + color + `;">` + rating + `</span>
                                    </div>
                                    <div class="invitee-user-col4">
                                        <span class="unavailable" id="online_dev` + dev_id +
                                `">{{ __('frontend.unavailable') }}</span>
                                    </div>
                                    <div class="invitee-user-col5">
                                        <input type="radio" name="invitee_dev"  onclick="select_developer(this.value)"  value="` +
                                dev_id + `">
                                    </div>
                                    </div>
                                `;
                        }
                    }
                }
                $('#developerList').html(developerListContent);
            }
            Echo.channel('developer_room.{{ Auth::user()->id }}')
                .listen('MeetingAlert', (e) => {
                    var url = "{{ env('APP_URL') }}/client/call/" + e.meeting_uid;
                    if (window.location.href != url)
                        window.location = url;
                })
        </script>
        <script src="{{ asset('js/client-project.js') }}"></script>

        </html>

        <style scoped>
            /* Loader */
            #loading,
            #extendLoading {
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
        </style>
    @endpush
@endif
