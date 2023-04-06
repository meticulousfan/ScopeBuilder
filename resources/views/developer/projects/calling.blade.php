@if (isset($project))
    <div class="calling-wrap">
        <a class="calling-icon-box" id="callingIconOutside" href="javascript:void(0)">
            <div class="calling-dot red-dot"></div>
            <img src="/ui_assets/img/icons/iconmonstr-delivery-8.svg" alt="call-ringing-icon">
        </a>
    </div>

    <div class="modal custom-modal calling" tabindex="-1" id="calling-modal" role="dialog">
        <div class="calling-wrap">
            <a class="calling-icon-box calling" href="javascript:void(0)">
                <div class="calling-dot red-dot"></div>
                <img src="/ui_assets/img/icons/iconmonstr-delivery-8.svg" alt="call-icon">
            </a>
        </div>
        <div class="modal-dialog" role="document" style="margin-right: 100px;">
            <div class="modal-content">
                <div class="modal-body">
                    <div id="callOngoingDev" class="calling-modal-4 text-center">
                        <div class="timer-block" id="timer">@{{ convertTimeString() }}</div>
                        <div class="callers-block-wrap">
                            <div class="scroll-caller-btn-wrap">
                                <a href="javascript:void(0)" class="scroll-caller prev-caller" id="devPrevCaller">
                                    < </a>
                            </div>
                            <div class="callers-block" id="callersDevSlider"
                                style="display: flex;justify-content:space-between">
                            </div>
                            <div class="scroll-caller-btn-wrap">
                                <a href="javascript:void(0)" class="scroll-caller next-caller" id="devNextCaller">></a>
                            </div>
                        </div>
                        <div class="button-block">
                            <a href="javascript:void(0)" onClick="leaveMeeting()"
                                class="btn btn-block custom-btn-danger btn-mdm">{{ __('frontend.leaveMeeting') }}</a>
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
            var vueApp = new Vue({
                el: '#calling-modal',
                data: {
                    meetingDuration: {{$meetingRoom->duration}},
                    extendedDuration: 10,
                    webCam: '',
                    payForExtendedDuration: false,
                    projectID: '{{ $project->uuid }}'
                },
                methods: {
                    sendPayment(paymentMethod) {
                        var self = this;
                        var amountToPay;

                        if (self.payForExtendedDuration) {
                            amountToPay = self.extendedDuration;
                        } else {
                            amountToPay = self.meetingDuration;
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
                                if (response.data.status == 'succeeded') {
                                    showPaymentSuccess()
                                } else {
                                    showPaymentFailed()
                                }
                            })
                            .catch(function(error) {
                                console.log(error);
                                showPaymentFailed()
                            });

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
                    convertTimeString() {
                        var minutes = parseInt(this.meetingDuration % 60, 10);
                        var hours = parseInt(this.meetingDuration / 60, 10);

                        minutes = minutes < 10 ? "0" + minutes : minutes;
                        hours = hours < 10 ? "0" + hours : hours;

                        return hours + ":" + minutes + ":00"

                    },
                }
            })
        </script>

        <script>
            $(document).ready(function() {
                // Remove Red Dot
                $(".calling-dot").removeClass("red-dot");
                $("#calling-modal").addClass("ongoing-call");
                $("#callersDevSlider").slick({
                    infinite: false,
                    slidesToShow: 2,
                    slidesToScroll: 2,
                    prevArrow: $("#devPrevCaller"),
                    nextArrow: $("#devNextCaller"),
                });
            });


            function leaveMeeting() {
                location.href = "/freelancer/projects";
            }
        </script>


        </html>

        <style scoped>
            /* Loader */
            #loading {
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
