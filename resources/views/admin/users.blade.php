@extends('admin.layout.main')
@section('page-title')
    Users
@endsection

@section('page-caption')
    Users
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
                    <h1 class="page-caption">Users Database</h1>
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
                            <table class="table no-wrap">
                                <thead>
                                    <tr>
                                        <th>Full Name</th>
                                        <th>Email Address</th>
                                        <th>Project Name</th>
                                        <th>Project Status</th>
                                        <th>Referred By</th>
                                        <th>Role</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users as $user)
                                        @php
                                            $first_role = $user->roles()->first();
                                            $role_name = @$first_role->name;
                                        @endphp
                                        <tr>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>Lumi</td>
                                            <td>In-Progress</td>
                                            <td>{{ $user->referrer_id ? \App\Models\User::find($user->referrer_id)->name : 'None' }}
                                            </td>
                                            <td>{{ $role_name }}</td>
                                            <td>
                                                <button class="action-btn" data-id="{{ $user->id }}"
                                                    data-modal="#remove-user-modal"
                                                    {{ $role_name == "admin" ? "disabled" : "" }}>
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
                    </div>
                </div>
            </section>
        </div>
    </main>
    <div class="modal" id="remove-user-modal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <button class="modal-close" aria-label="Close modal"></button>
                <form action="{{ route('admin.users.destroy') }}" method="POST">
                    @csrf
                    <input type="hidden" name="user_id" id="user_id">
                    <div class="modal-caption">
                        <div class="mc-icon">
                            <img src="{{ asset('ui_assets/img/icons/trash.svg') }}" alt="">
                        </div>
                        <h3 class="mc-title">Delete User</h3>
                        <p class="mc-subtitle">Are you sure you wish to delete this
                            user? This action will&nbsp;remove the
                            user and can not be undone.</p>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-light js-modal-close">Cancel</button>
                        <button class="btn" type="submit">Delete
                            User</button>
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
        $(".action-btn").click(function() {
            var id = $(this).data("id");
            $('#user_id').val(id);
        })
    });
</script>
