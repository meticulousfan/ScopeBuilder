@extends('client.layout.main')
@section('page-title')
    Call Room
@endsection

@section('page-caption')
    Call Room
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
    @include('client.projects.calling')
    <div class="loader">
        <div></div>
        <div></div>
        <div></div>
        <div></div>
    </div>
    <iframe id="call-iframe" allow="geolocation *; microphone *; camera *; display-capture *;" allowFullScreen="true"
        webkitallowfullscreen="true" mozallowfullscreen="true"
        sandbox="allow-same-origin allow-scripts allow-modals allow-forms" scrolling="no"></iframe>
    <style>
        #call-iframe {
            width: 100%;
            height: 100vh;
            border: 0
        }

        .loader {
            display: inline-block;
            position: relative;
            width: 80px;
            height: 80px;
        }

        .loader div {
            position: absolute;
            top: 33px;
            width: 13px;
            height: 13px;
            border-radius: 50%;
            background: var(--blue-1000);
            animation-timing-function: cubic-bezier(0, 1, 1, 0);
        }

        .loader div:nth-child(1) {
            left: 8px;
            animation: loader1 0.6s infinite;
        }

        .loader div:nth-child(2) {
            left: 8px;
            animation: loader2 0.6s infinite;
        }

        .loader div:nth-child(3) {
            left: 32px;
            animation: loader2 0.6s infinite;
        }

        .loader div:nth-child(4) {
            left: 56px;
            animation: loader3 0.6s infinite;
        }

        @keyframes loader1 {
            0% {
                transform: scale(0);
            }

            100% {
                transform: scale(1);
            }
        }

        @keyframes loader3 {
            0% {
                transform: scale(1);
            }

            100% {
                transform: scale(0);
            }
        }

        @keyframes loader2 {
            0% {
                transform: translate(0, 0);
            }

            100% {
                transform: translate(24px, 0);
            }
        }
    </style>



    @push('ui.script')
        <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css" />
        <script type="text/javascript" src="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
        <script src="{{ asset('js/bootstrap.min.js') }}"></script>
        <script src="{{ asset('js/form.js?ver=' . time()) }}"></script>
        <script src="{{ asset('js/jquery.priceformat.min.js') }}"></script>
        <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
        <script type="text/javascript">
            const client = '{{ Auth::user()->hasRole('client') }}' == 1 ? true : false;
            const dev = '{{ Auth::user()->hasRole('freelancer') }}' == 1 ? true : false;

            window.onload = function joinMeeting() {
                $(".calling-dot").removeClass("red-dot");

                client ? showClientMeetingOptions() : null;
                dev ? showDevMeetingOptions() : null;

                const callMeetingID = '{{ $meetingRoom->bbb_meeting_id }}';
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const userId = '{{ Auth::id() }}'

                axios.post('/api/meeting/join', {
                        meeting_id: callMeetingID,
                        user_id: userId,
                        client
                    }, {
                        headers: {
                            'X-CSRF-TOKEN': csrfToken
                        }
                    }).then(function(response) {
                        document.getElementById("call-iframe").src = response.data.data.meeting_url;
                        // document.getElementByClass("page-sidebar").style.display = "none";
                        document.getElementsByClassName("loader")[0].style.display = "none";
                    })
                    .catch(function(error) {
                        console.log(error.response);
                    });
            }

            function endMeeting() {
                const callMeetingID = '{{ $meetingRoom->bbb_meeting_id }}';
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const userId = '{{ Auth::id() }}'
                const client = '{{ Auth::user()->hasRole('client') }}' == 1 ? true : false;

                axios.post('/api/meeting/end', {
                        meeting_id: callMeetingID,
                        user_id: userId,
                        client
                    }, {
                        headers: {
                            'X-CSRF-TOKEN': csrfToken
                        }
                    }).then(function(response) {
                        if (response.status == "200") {
                            clearInterval(checkStatusInterval);
                            showMeetingEnded();
                        }
                        // document.getElementById("end-button").setAttribute("disabled");
                    })
                    .catch(function(error) {
                        console.log(error.response);
                    });
            }

            function checkRunning() {
                const callMeetingID = '{{ $meetingRoom->bbb_meeting_id }}';
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                axios.post('/api/meeting/status', {
                        meeting_id: callMeetingID
                    }, {
                        headers: {
                            'X-CSRF-TOKEN': csrfToken
                        }
                    }).then(function(response) {
                        alertToMembers();
                        // Add red dot if meeting is running
                        response.data.data ? $(".calling-dot").addClass("red-dot") : $(".calling-dot").removeClass(
                            "red-dot");
                        return response.data.data;
                    })
                    .catch(function(error) {
                        console.log(error.response);
                    });

            }

            const checkStatusInterval = setInterval(() => {
                checkRunning();
            }, 10000);

            const countDownInterval = setInterval(() => {

            }, 1000);

            function showClientMeetingOptions() {
                $("#newMeeting").addClass("d-none");
                $("#joinMeeting").addClass("d-none");
                $("#joinCall").addClass("d-none");
                $("#calling-modal").addClass("ongoing-call");
                $("#callOngoingDev").addClass("d-none");
                $("#callOngoing").removeClass("d-none");
                $("#callersClientSlider").slick("refresh");
            }

            function showDevMeetingOptions() {
                $("#newMeeting").addClass("d-none");
                $("#joinMeeting").addClass("d-none");
                $("#joinCall").addClass("d-none");
                $("#calling-modal").addClass("ongoing-call");
                $("#callOngoingDev").removeClass("d-none");
                $("#callOngoing").addClass("d-none");
            }

            function checkFeedbackSubmission() {
                location.href = "/client/projects";
            }

            function goToMeeting(uuid) {
                location.href = "/client/call/" + uuid;
            }

            function goToMeetingCheck() {
                // client
            }
            window.PUSHER_APP_KEY = '{{ config('broadcasting.connections.pusher.key') }}';
            window.APP_DEBUG = {{ config('app.debug') ? 'true' : 'false' }};
            var online_call_members = [];
            Echo.join('call_room.{{ $meetingRoom->bbb_meeting_id }}')
                .here((users) => {
                    online_call_members = users;
                    updateUserList();
                })
                .joining((user) => {
                    online_call_members.push(user);
                    updateUserList();
                })
                .leaving((user) => {
                    online_call_members.pop(user);
                    updateUserList();
                });

            function contains(array, obj) {
                var flag = false;
                for (var i = 0; i < array.length; i++) {
                    if (array[i]['id'] == obj) {
                        flag = true;
                        break;
                    }
                }
                return flag;
            }

            function alertToMembers() {
                if (!contains(online_call_members, "{{ $meetingRoom->developer_id }}")) {
                    $.ajax({
                        type: "POST",
                        dataType: "json",
                        url: "{{ route('api.meeting.alert') }}",
                        data: {
                            'meeting_id': "{{ $meetingRoom->bbb_meeting_id }}"
                        },
                        success: function(data) {},
                        error: function(data) {}
                    });
                }
            }

            function getTwoName(text) {
                text = text.match(/[A-Z]/g);
                let label = '';
                for (let i = 0; i < text.length && i < 2; i++)
                    label += text[i];
                return label;
            }
            var handler;

            function updateUserList() {
                var str = '';
                for (var i = 0; i < online_call_members.length; i++) {
                    var name = online_call_members[i]['name']
                    let text = `{{ __('frontend.addAsCollaborator') }}`;
                    let result = text.replace("%NAME%", online_call_members[i]['name']);
                    $('#addcollaboratorDiv').html(`<a href="javascript:void(0)" @click="addcollaborator()" id="addcollaboratorBtn" style="color: white;text-decoration: underline;"
                >`+result+`</a>`)
                    str += `<div class="caller-user">
                            <div class="caller-user-circle">
                                <div class="caller-user-circle-inner">` + getTwoName(online_call_members[i]['name']) + `</div>
                            </div>
                            <div class="caller-fullname">` + online_call_members[i]['name'] + `</div>
                        </div>`;
                }
                $('#callersClientSlider').html(str);

                if (online_call_members.length > 1) {
                    handler = setInterval(countDownTime, 1000);
                } else {
                    clearInterval(handler);
                }
            }

            function countDownTime() {
                var time_string = $("#timer").text();
                var seconds = str2seconds(time_string);
                if (seconds > 0) {
                    seconds2str(seconds - 1);
                } else {
                    clearInterval(handler);
                    endMeeting();
                }
            }

            function str2seconds(time_string) {
                var split_time = time_string.split(':');
                var seconds = (+split_time[0]) * 60 * 60 + (+split_time[1]) * 60 + (+split_time[2]);
                return seconds;
            }

            function seconds2str(seconds) {
                var str = new Date(seconds * 1000).toISOString().substring(11, 19);
                $("#timer").text(str);
            }
        </script>
    @endpush
@endsection
