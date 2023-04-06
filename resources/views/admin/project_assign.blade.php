@extends('admin.layout.main')
@section('page-title')
    Project Assign
@endsection

@section('page-caption')
    Project Assign
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
                    <h1 class="page-caption">Project Assign</h1>
                </div>
            </div>
        </div>
    </header>

    <form method="POST" class="form" action="{{ route('admin.projects.assign.submit', $project->uuid) }}">
        @csrf
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

                        @if ($errors->any())
                            <div class="card" style="margin-top: 2rem; margin-bottom: 3px;">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            @if ($errors->any())
                                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                                    <div class="alert-body">
                                                        <i style="top: 0px;" class="fas fa-exclamation-circle"></i>
                                                        <span style="margin-left: 3px;">Oops! You've entered invalid data.
                                                            Please
                                                            try again.</span>
                                                        @foreach ($errors->all() as $error)
                                                            <div style="margin-top: 5px; margin-left: 15px;">
                                                                <i style="top: 0px;" class="fas fa-exclamation-circle"></i>
                                                                <span style="margin-left: 3px;"> {{ $error }} </span>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="section-inner">
                            <div class="form-fields">
                                <div class="form-field">
                                    <div class="input-field">
                                        <div class="field-label">Assign To</div>
                                        <select name="assigned_to_user_id" required>
                                            <option value="">- -Assign Freelancer- -</option>
                                            @foreach($developers as $developer)
                                            <option value="{{ $developer->id }}" @if(!empty($project->assigned_to_user_id) && $developer->id == $project->assigned_to_user_id) selected @endif>{{ $developer->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="form-field">
                                    <input type="submit" class="btn" value="Save Changes" />
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </main>
    </form>
@endsection
