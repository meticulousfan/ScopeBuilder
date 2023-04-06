@extends('admin.layout.main')
@section('page-title')
    Freelancers
@endsection

@section('page-caption')
    Freelancers
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
                        <h1 class="page-caption" style="margin: 0px!important;">Freelancers</h1>
                        <div
                            style="width: 47px;height:26px; margin: 1px 16px;background: #1890FF 0% 0% no-repeat padding-box;border-radius:13px;opacity:1; text-align:center;align-items: center; display: grid;">
                            <div style="font-size:13px;color:white;">{{ $developerNum }}</div>
                        </div>
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
                    <div class="section-inner">
                        <div class="table-wrapper">
                            <table class="table clients_table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Projects</th>
                                        <th>Referrals</th>
                                        <th>Rating</th>
                                        <th>Take Calls</th>
                                        <th>Start Calls</th>
                                        <th>Payouts</th>
                                        <th>Created On</th>
                                        <th>Updated On</th>
                                        <th>Status</th>
                                        <th style="min-width: 80px">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($developers as $developer)
                                        <tr>
                                            <td>{{ $developer->id }}</td>
                                            <td>{{ $developer->name }}</td>
                                            <td>{{ $developer->projectNum }}</td>
                                            <td>{{ $developer->referralNum }}</td>
                                            <td>
                                                <div style="display:flex;width: 67px;font: normal normal normal 16px/20px Roboto;letter-spacing: 0px;">
                                                    @for ($i = 0; $i < 5; $i++)
                                                        @if($i < round($developer->rating))
                                                            <div style="color: #1890FF;">★</div> 
                                                        @else
                                                            <div style="color: #D1D1D1;">★</div>
                                                        @endif
                                                    @endfor                                                    
                                                </div>
                                            </td>
                                            <td>                                                
                                                <label class="switch">
                                                    <input data-id="{{ $developer->id }}" class="takeCalls-toggle-class"
                                                        type="checkbox" data-onstyle="success" data-offstyle="danger"
                                                        data-toggle="toggle" data-on="Active" data-off="InActive"
                                                        {{ $developer->can_take_calls ? 'checked' : '' }}>
                                                    <span class="slider round"></span>
                                                </label>
                                            </td>
                                            <td>                                                
                                                <label class="switch">
                                                    <input data-id="{{ $developer->id }}" class="startCalls-toggle-class"
                                                        type="checkbox" data-onstyle="success" data-offstyle="danger"
                                                        data-toggle="toggle" data-on="Active" data-off="InActive"
                                                        {{ $developer->can_start_calls ? 'checked' : '' }}>
                                                    <span class="slider round"></span>
                                                </label>
                                            </td>
                                            <td>{{ number_format(round(floatval($developer->totalPaidAmount), 2), 2) }} USD</td>
                                            <td>{{ date('F jS, Y', strtotime($developer->created_at)) }}</td>
                                            <td>{{ date('F jS, Y', strtotime($developer->updated_at)) }}</td>
                                            <td>
                                                <label class="switch">
                                                    <input data-id="{{ $developer->id }}" class="status-toggle-class"
                                                        type="checkbox" data-onstyle="success" data-offstyle="danger"
                                                        data-toggle="toggle" data-on="Active" data-off="InActive"
                                                        {{ $developer->status ? 'checked' : '' }}>
                                                    <span class="slider round"></span>
                                                </label>
                                            </td>
                                            <td>
                                                <a target="_blank" href="{{ route('admin.developer', $developer->uuid) }}"><img
                                                        src="{{ asset('ui_assets/img/icons/vuesax-bulk-document-text.svg') }}"></a>
                                                <button class="client-act-btn" data-id="{{ $developer->id }}"
                                                    data-modal="#remove-user-modal">
                                                    <svg class="btn-icon">
                                                        <use
                                                            xlink:href="{{ asset('ui_assets/img/icons-sprite.svg#trash') }}">
                                                        </use>
                                                    </svg>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="row">
                            <div class="col-auto pagination-wrap">
                                <div class="float-right">
                                    {!! $developers->appends(compact('perpage'))->links() !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </main>
    <div class="modal" id="remove-user-modal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <button class="modal-close" aria-label="Close modal"></button>
                <form action="{{ route('admin.users.delete') }}" method="POST">
                    @csrf
                    <input type="hidden" name="user_type" id="user_type" value="1">
                    <input type="hidden" name="user_id" id="user_id">
                    <div class="modal-caption">
                        <div class="mc-icon">
                            <img src="{{ asset('ui_assets/img/icons/trash.svg') }}" alt="">
                        </div>
                        <h3 class="mc-title"><strong>Delete Freelancer</strong></h3>
                        <p class="mc-subtitle">Are you sure you wish to delete this
                            Freelancer? This action will&nbsp;remove the
                            Freelancer and can not be undone.</p>
                    </div>

                    <div class="modal-footer" style="flex-wrap: inherit; padding:0px;border:0px;">
                        <button class="btn btn-light js-modal-close"
                            style="padding: 10px 20px;min-height: 54px;border: 1px solid var(--btn-bg); border-radius: var(--r);background: var(--btn-bg);color: var(--btn-color);">Cancel</button>
                        <button class="btn" type="submit"
                            style="padding: 10px 20px;min-height: 54px;border: 1px solid var(--btn-bg); border-radius: var(--r);background: var(--btn-bg);color: var(--btn-color);">Delete
                            Freelancer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal video-modal" id="video-modal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <button class="modal-close" aria-label="Close modal"></button>
                <div class="modal-video">
                    <div id="modal-video-iframe"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $(".client-act-btn").click(function() {
            var id = $(this).data("id");
            $('#user_id').val(id);
        })
        
        $('.status-toggle-class').change(function() {
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
        $('.takeCalls-toggle-class').change(function() {
            var status = $(this).prop('checked') == true ? 1 : 0;
            var user_id = $(this).data('id');

            $.ajax({
                type: "GET",
                dataType: "json",
                url: "{{ route('admin.user.changeCallStatus') }}",
                data: {
                    'type': '0',
                    'status': status,
                    'user_id': user_id
                },
                success: function(data) {
                    console.log(data.success)
                }
            });
        })
        $('.startCalls-toggle-class').change(function() {
            var status = $(this).prop('checked') == true ? 1 : 0;
            var user_id = $(this).data('id');

            $.ajax({
                type: "GET",
                dataType: "json",
                url: "{{ route('admin.user.changeCallStatus') }}",
                data: {
                    'type': '1',
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