@extends('admin.layout.main')
@section('page-title')
Payout Methods
@endsection

@section('page-caption')
Payout Methods
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
                        <h1 class="page-caption" style="margin: 0px!important;">Payout Methods</h1>
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
                                        <th>Description</th>
                                        <th>Created On</th>
                                        <th>Updated On</th>
                                        <th>Status</th>
                                        {{-- <th>Action</th> --}}
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($methods as $method)
                                        <tr>
                                            <td>{{ $method->id }}</td>
                                            <td>{{ $method->name }}</td>
                                            <td>{{ $method->description }} </td>
                                            <td>{{ date('F jS, Y', strtotime($method->created_at)) }}</td>
                                            <td>{{ date('F jS, Y', strtotime($method->updated_at)) }}</td>
                                            <td>
                                                <label class="switch">
                                                    <input data-id="{{ $method->id }}" class="toggle-class"
                                                        type="checkbox" data-onstyle="success" data-offstyle="danger"
                                                        data-toggle="toggle" data-on="Active" data-off="InActive"
                                                        {{ $method->status ? 'checked' : '' }}>
                                                    <span class="slider round"></span>
                                                </label>
                                            </td>
                                            {{-- <td>
                                                <button class="client-act-btn" data-id="{{ $method->id }}"
                                                    data-modal="#remove-user-modal">
                                                    <svg class="btn-icon">
                                                        <use
                                                            xlink:href="{{ asset('ui_assets/img/icons-sprite.svg#trash') }}">
                                                        </use>
                                                    </svg>
                                                </button>
                                            </td> --}}
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
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
                    <input type="hidden" name="user_type" id="user_type" value="0">
                    <input type="hidden" name="user_id" id="user_id">
                    <div class="modal-caption">
                        <div class="mc-icon">
                            <img src="{{ asset('ui_assets/img/icons/trash.svg') }}" alt="">
                        </div>
                        <h3 class="mc-title"><strong>Delete Client</strong></h3>
                        <p class="mc-subtitle">Are you sure you wish to delete this
                            client? This action will&nbsp;remove the
                            client and can not be undone.</p>
                    </div>

                    <div class="modal-footer" style="flex-wrap: inherit; padding:0px;border:0px;">
                        <button class="btn btn-light js-modal-close"
                            style="padding: 10px 20px;min-height: 54px;border: 1px solid var(--btn-bg); border-radius: var(--r);background: var(--btn-bg);color: var(--btn-color);">Cancel</button>
                        <button class="btn" type="submit"
                            style="padding: 10px 20px;min-height: 54px;border: 1px solid var(--btn-bg); border-radius: var(--r);background: var(--btn-bg);color: var(--btn-color);">Delete
                            Client</button>
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
            $('#method_id').val(id);
        })
        
        $('.toggle-class').change(function() {
            var status = $(this).prop('checked') == true ? 1 : 0;
            var method_id = $(this).data('id');

            $.ajax({
                type: "GET",
                dataType: "json",
                url: "{{ route('admin.payout_methods.changeStatus') }}",
                data: {
                    'status': status,
                    'method_id': method_id
                },
                success: function(data) {
                    console.log(data.success)
                }
            });
        })
    });
</script>
