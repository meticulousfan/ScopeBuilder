@extends('client.layout.main')
@section('page-title')
    @if($mode_type=='edit') Edit Existing Project
    @elseif($mode_type=='create') Add Existing Project
    @endif
@endsection

@section('page-caption')
    My Projects
@endsection
@push('ui.style')
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/form.css') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100&family=Roboto:wght@100;300;400;500;700;900&display=swap" rel="stylesheet">
@endpush
@php
    $webList            = \App\Models\ClientProject::WEB_TECH;
    $mobList            = \App\Models\ClientProject::MOB_TECH;  

    if($mode_type=='edit'){
        $projectList        = unserialize($project->example_projects);

        $webFrameworks      = $project->web_frameworks ? unserialize($project->web_frameworks): []; 
        $mobileFrameworks   = $project->mobile_frameworks ? unserialize($project->mobile_frameworks) : [];
    }

    if($mode_type=='edit'){
        $form_action = route('client.projects_existing.update', $project->uuid);
    } else{
        $form_action = route('client.projects_existing.store');
    }

    $show_type = 'mobile';
    if($mode_type=='edit' && !empty($project->tech_type)) $show_type = $project->tech_type;
@endphp 
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
                        @if($mode_type=='edit')
                        <span class="show-on-mobile">{{ __('frontend.edit_existing_mobile_project') }}</span>
                        <span class="show-on-web">{{ __('frontend.edit_existing_web_project') }}</span>
                        <span class="show-on-both">{{ __('frontend.edit_existing_both_project') }}</span>
                        @elseif($mode_type=='create')
                        <span class="show-on-mobile">{{ __('frontend.add_existing_mobile_project') }}</span>
                        <span class="show-on-web">{{ __('frontend.add_existing_web_project') }}</span>
                        <span class="show-on-both">{{ __('frontend.add_existing_both_project') }}</span>
                        @endif
                    </h1>
                </div>
                <div class="buttons pr-3">
                    <button type="button" value="Save For Later" class="btn btn-primary later-h-btn d-none" id="savelater">Save For Later</button>
                </div>
            </div>
        </div>
    </header>
    <main class="page-main">
        <div class="page-content">
            @if (session('success'))
                <div class="card" style="margin-top: 2rem; margin-bottom: 3px; margin-left: 2rem;">
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
            <section class="default-section with-footer px-3 {{ $show_type }}-project@php if($mode_type=='edit' && $project->left_step=='6') echo ' project-wrap'; @endphp" id="clientproject">
                <input type="hidden" id="tech_type" value="{{ $show_type }}" />
                <!--input type="hidden" name="tech_type" class="tech_type_bind" value="mobile" /-->
                
                <div class="container-fluid">
                    <div class="card shadow-sm border-0 pt-4 pb-0 px-3 mb-5">
                        <div class="loader-con d-none justify-content-center h-100 w-100 align-items-center"><div id="loading"></div></div>
                        <div class="card-body pt-3 pb-0 px-2">
                            @php
                            $itr_arr = array('mobile', 'web', 'both');
                            @endphp

                            @foreach($itr_arr as $ia)
                                @php
                                if($mode_type=='edit'){
                                    $userTypes = $pages = $permission = $pagesInformation = [];
                                    if(!empty($project_details[$ia])){
                                        $userTypes = !empty($project_details[$ia]['user_types']) ? unserialize($project_details[$ia]['user_types']) : [];
                                        $pages = !empty($project_details[$ia]['pages']) ? unserialize($project_details[$ia]['pages']) : [];
                                        $permission = !empty($project_details[$ia]['pages_permission']) ? unserialize($project_details[$ia]['pages_permission']) : [];
                                        $pagesInformation = !empty($project_details[$ia]['pages_information']) ? unserialize($project_details[$ia]['pages_information']) : [];
                                    }
                                }
                                @endphp
                            <div class="project-type-container {{ $ia }}-container">
                                <div class="col-md-7 px-0">
                                    <div class="question-block">
                                        <form action="{{ $form_action }}" method="POST" data-formid="step_no_1" class="projectforms form" @if($mode_type=='edit' && $project->is_draft) style="display:none;" @endif>
                                            @csrf
                                            <input type="hidden" name="left_step" value="1">
                                            <input type="hidden" name="is_draft" value="0">
                                            <div class="row block-top">
                                                <div class="col">
                                                    <h3 class="block-caption">What is the name of your existing project?</h3>
                                                </div>
                                                <div class="col-auto">
                                                   <div class="popover-block">
                                                        <span class="block-opener">
                                                            <svg class="btn-icon"><use xlink:href="{{ asset('ui_assets/img/icons-sprite.svg') }}#info"></use></svg>
                                                        </span> 
                                                        <div class="block-hidden-content">
                                                            <p><strong >Lorem ipsum</strong> - dolor sit amet, consectetur
                                                                adipisicing elit. Facilis doloremque vitae, nisi. Mollitia
                                                                asperiores saepe pariatur repellat ullam voluptates neque!
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <p>Please enter a suitable name for your project.</p>
                                                </div>   
                                                <div class="form-group col-md-12">
                                                    <div class="inner custom-field-design border  rounded px-2 pt-2">
                                                      <label for="name" class="small">Project Name</label>
                                                      <input class="form-control border-0"  type="text" name="name" id="nameInput" value="{{$project->name ?? old('name')}}"> 
                                                      <div class="invalid-feedback" id="nameError"></div>    
                                                    </div>        
                                                </div>                           
                                            </div>

                                            <div class="row block-top">
                                                <div class="col">
                                                    <h3 class="block-caption">What is your project description?</h3>
                                                </div>
                                                <div class="col-auto">
                                                   <div class="popover-block">
                                                        <span class="block-opener">
                                                            <svg class="btn-icon"><use xlink:href="{{ asset('ui_assets/img/icons-sprite.svg') }}#info"></use></svg>
                                                        </span> 
                                                        <div class="block-hidden-content">
                                                            <p><strong >Lorem ipsum</strong> - dolor sit amet, consectetur
                                                                adipisicing elit. Facilis doloremque vitae, nisi. Mollitia
                                                                asperiores saepe pariatur repellat ullam voluptates neque!
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>                             
                                                <div class="col-md-12">
                                                    <p>Please provide a description of your existing project</p>
                                                </div>   
                                                <div class="form-group col-md-12">
                                                    <div class="inner custom-field-design border  rounded px-2 pt-2">
                                                        <label for="name" class="small">Project Description</label>
                                                        <textarea class="form-control border-0" rows="3" name="description" id="descriptionInput">{{$project->description ?? old('description')}}</textarea>
                                                        <div class="invalid-feedback" id="descriptionError"></div>     
                                                    </div>       
                                                </div>                           
                                            </div>

                                            <div class="row block-top">
                                                <div class="col">
                                                    <h3 class="block-caption">What is your code repository link?</h3>
                                                </div>
                                                <div class="col-auto">
                                                   <div class="popover-block">
                                                        <span class="block-opener">
                                                            <svg class="btn-icon"><use xlink:href="{{ asset('ui_assets/img/icons-sprite.svg') }}#info"></use></svg>
                                                        </span> 
                                                        <div class="block-hidden-content">
                                                            <p><strong >Lorem ipsum</strong> - dolor sit amet, consectetur
                                                                adipisicing elit. Facilis doloremque vitae, nisi. Mollitia
                                                                asperiores saepe pariatur repellat ullam voluptates neque!
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <p>Please provide your code repository link</p>
                                                </div>   
                                                <div class="form-group col-md-12">
                                                    <div class="inner custom-field-design border  rounded px-2 pt-2">
                                                      <label for="code_repository_link" class="small">Code Repository Link</label>
                                                      <input class="form-control border-0"  type="text" name="code_repository_link" id="code_repository_link_input" value="{{$project->code_repository_link ?? old('code_repository_link')}}"> 
                                                      <div class="invalid-feedback" id="code_repository_linkError"></div>    
                                                    </div>        
                                                </div>                           
                                            </div>  

                                            <div class="row block-top">
                                                <div class="col">
                                                    <h3 class="block-caption">Do you have mock up designs for the changes to existing project?</h3>
                                                </div>
                                                <div class="col-auto">
                                                   <div class="popover-block">
                                                        <span class="block-opener">
                                                            <svg class="btn-icon"><use xlink:href="{{ asset('ui_assets/img/icons-sprite.svg') }}#info"></use></svg>
                                                        </span> 
                                                        <div class="block-hidden-content">
                                                            <p><strong >Lorem ipsum</strong> - dolor sit amet, consectetur
                                                                adipisicing elit. Facilis doloremque vitae, nisi. Mollitia
                                                                asperiores saepe pariatur repellat ullam voluptates neque!
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>   
                                               <div class="col-md-12">
                                                    <p>Please enter a suitable description for your project.</p>
                                                </div>                                                
                                            </div>

                                            <div class="block-form form mb-4 pb-2 block-top">
                                                <div class="form-fields">
                                                    <div class="form-field">
                                                        <span>
                                                            <div class="fields-group">
                                                                <label class="radio">
                                                                    <input type="radio" name="mockup" value="1" class="visually-hidden " @if($mode_type=='edit' && $project->mockup) {{"checked"}}  @endif/> <span class="fake-label">Yes</span>
                                                                </label>
                                                                <label class="radio">
                                                                    <input id="mockupInput" type="radio" name="mockup" value="0" class="visually-hidden" @if($mode_type=='edit' && !$project->mockup) {{"checked"}}  @endif/> <span class="fake-label">NO</span>
                                                                </label>                                                                
                                                            </div>                                                            
                                                        </span>                                                        
                                                    </div>
                                                </div>
                                                <div class="invalid-feedback" id="mockupError"></div>     
                                            </div>

                                            <div class="custom-file-upload block-top d-none" id="mockupfile">
                                                <div class="item-wrapper one">
                                                    <div class="item">
                                                        <div class="item-inner">
                                                                <div class="item-content">
                                                                    <div class="image-upload image-upload-wrap"> 
                                                                        <label style="cursor: pointer;" for="file_upload"> <img src="" alt="" class="uploaded-image">
                                                                            <div class="h-100">
                                                                                <div class="dplay-tbl">
                                                                                    <div class="dplay-tbl-cell"> 
                                                                                        <i class="fa fa-cloud-upload"></i>
                                                                                        <h5><b>Choose Your Image to Upload</b></h5>
                                                                                        <h6 class="mt-10 mb-70">Or Drop Your Image Here</h6>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <input data-required="image" type="file" name="mockup_file" id="file_upload" class="image-input" data-traget-resolution="image_resolution" onchange="readURL(this);">
                                                                        </label> 
                                                                    </div>
                                                                    <div class="file-upload-content" style="display:none;">                                                                
                                                                       <div class="image-title-wrap">
                                                                              <button type="button" onclick="removeUpload()" class="remove-image btn btn-success">Remove - <span class="image-title text-white">Uploaded Image</span></button>
                                                                        </div>
                                                                    </div>
                                                                </div>                                                                
                                                            </div>                                                           
                                                    </div>                                                   
                                                </div>
                                               <div class="row block-top mt-3">
                                                        <div class="col">
                                                            <h3 class="block-caption">Add Figma URL?</h3>
                                                        </div>
                                                        <div class="col-auto">
                                                           <div class="popover-block">
                                                                <span class="block-opener">
                                                                    <svg class="btn-icon"><use xlink:href="{{ asset('ui_assets/img/icons-sprite.svg') }}#info"></use></svg>
                                                                </span> 
                                                                <div class="block-hidden-content">
                                                                    <p><strong >Lorem ipsum</strong> - dolor sit amet, consectetur
                                                                        adipisicing elit. Facilis doloremque vitae, nisi. Mollitia
                                                                        asperiores saepe pariatur repellat ullam voluptates neque!
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>                             
                                                        <div class="col-md-12">
                                                            <p>Add Figma URL</p>
                                                        </div>   
                                                        <div class="form-group col-md-12">
                                                            <div class="inner custom-field-design border  rounded px-2 pt-2">
                                                              <label for="mockup_url" class="small">Figma URL</label>
                                                              <input class="form-control border-0" type="url" name="mockup_url" id="mockup_urlInput" value="{{$project->mockup_url ?? old('mockup_url')}}"> 
                                                              <div class="invalid-feedback" id="mockup_urlError"></div>    
                                                            </div>        
                                                        </div>                           
                                                    </div> 
                                            </div>

                                            <div class="row block-top">
                                                <div class="col">
                                                    <h3 class="block-caption">Do you have any example projects you would like to share?</h3>
                                                </div>
                                                <div class="col-auto">
                                                   <div class="popover-block">
                                                        <span class="block-opener">
                                                            <svg class="btn-icon"><use xlink:href="{{ asset('ui_assets/img/icons-sprite.svg') }}#info"></use></svg>
                                                        </span> 
                                                        <div class="block-hidden-content">
                                                            <p><strong >Lorem ipsum</strong> - dolor sit amet, consectetur
                                                                adipisicing elit. Facilis doloremque vitae, nisi. Mollitia
                                                                asperiores saepe pariatur repellat ullam voluptates neque!
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>                             
                                                <div class="col-md-12">
                                                    <p>Please use the form below to add links to projects you want to share.</p>
                                                </div>                                                                            
                                            </div>

                                            <div class="row block-top" id="sh_card">
                                                @if($mode_type=='edit')
                                                    @foreach($projectList as $list)
                                                    <div class="form-group col-md-12" id="shmain_{{$loop->index+1}}">   
                                                        <div class="multi-value-list border rounded">  
                                                            <div class="multi-value-item p-3">                                   
                                                              <input class="input-field w-100"  type="text" name="example_projects[]" value="{{$list}}">    
                                                               <button type="button" class="action-btn d-none ml-3 mt-2">
                                                                    <svg  class="btn-icon"><use  xlink:href="{{ asset('ui_assets/img/icons-sprite.svg') }}#trash"></use>
                                                                </svg>
                                                                </button>                                                     
                                                            </div>                                                     
                                                        </div>
                                                    </div>  
                                                    @endforeach
                                                @else
                                                    <div class="form-group col-md-12" id="shmain_1">   
                                                        <div class="multi-value-list border rounded">  
                                                            <div class="multi-value-item p-3">                                   
                                                              <input class="input-field w-100"  type="text" name="example_projects[]" id="example_projectsInput">    
                                                               <button type="button" class="action-btn d-none ml-3 mt-2">
                                                                    <svg  class="btn-icon"><use  xlink:href="{{ asset('ui_assets/img/icons-sprite.svg') }}#trash"></use>
                                                                </svg>
                                                                </button>                                                     
                                                            </div>                                                     
                                                        </div>
                                                    </div>
                                                @endif

                                                <div class="invalid-feedback" id="example_projectsError"></div>        
                                            </div>

                                            <div class="button-bottom ">                               
                                                <button id="addmoreexample" type="button" class="btn btn-primary btn-lg py-3">
                                                    <svg  class="btn-icon"><use  xlink:href="{{ asset('ui_assets/img/icons-sprite.svg') }}#plus"></use></svg>
                                                </button>
                                            </div>       
                                        </form>
                                        
                                        <form action="{{ $form_action }}" method="POST" data-formid="step_no_2" class="projectforms form" @if($mode_type=='edit' && $project->is_draft && $project->left_step==2) style="display:block;" @else style="display:none;" @endif>
                                            @csrf
                                            <input type="hidden" name="left_step" value="2">
                                            <input type="hidden" name="is_draft" value="0">
                                            <div class="row block-top">
                                                <div class="col">
                                                    <h3 class="block-caption">What is your existing project?</h3>
                                                </div>
                                                <div class="col-auto">
                                                   <div class="popover-block">
                                                        <span class="block-opener">
                                                            <svg class="btn-icon"><use xlink:href="{{ asset('ui_assets/img/icons-sprite.svg') }}#info"></use></svg>
                                                        </span> 
                                                        <div class="block-hidden-content">
                                                            <p><strong >Lorem ipsum</strong> - dolor sit amet, consectetur
                                                                adipisicing elit. Facilis doloremque vitae, nisi. Mollitia
                                                                asperiores saepe pariatur repellat ullam voluptates neque!
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>   
                                               <div class="col-md-12">
                                                    <p>Please choose whether this will be a mobile based project, a web based project or both.</p>
                                                </div>                                                
                                            </div>
                                            <div class="block-form form mb-4 pb-2 block-top">
                                                <div class="form-fields">
                                                    <div class="form-field">
                                                        <span>
                                                            <div class="fields-group">
                                                                <label class="radio">
                                                                    <input type="radio" name="type" value="mobile" class="visually-hidden" @if( ($mode_type=='edit' && $project->type=='mobile') || ($mode_type=='create') ) checked @endif/> <span class="fake-label">Mobile</span>
                                                                </label>
                                                                <label class="radio">
                                                                    <input type="radio" name="type" value="web" class="visually-hidden" @if($mode_type=='edit' && $project->type=='web') checked @endif/> <span class="fake-label">Web</span>
                                                                </label>
                                                                 <label class="radio">
                                                                    <input type="radio" name="type" value="both" class="visually-hidden" @if($mode_type=='edit' && $project->type=='both') checked @endif/> <span class="fake-label">Both</span>
                                                                </label>
                                                            </div>                                                            
                                                        </span>                                                        
                                                    </div>
                                                </div>
                                                <div class="invalid-feedback" id="mockupError"></div>     
                                            </div>

                                            <div class="same-functionality-wrap d-none">
                                                <div class="row block-top">
                                                    <div class="col">
                                                        <h3 class="block-caption">Do both mobile and web have the exact same functionality?</h3>
                                                    </div>                                    
                                                </div>
                                                <div class="block-form form mb-4 pb-2 block-top">
                                                    <div class="form-fields">
                                                        <div class="form-field">
                                                            <span>
                                                                <div class="fields-group">
                                                                    <label class="radio">
                                                                        <input type="radio" name="both_same_functionality" value="1" class="visually-hidden" @if( ($mode_type=='edit' && $project->both_same_functionality=='1') || ($mode_type=='create') ) checked @endif/> <span class="fake-label">Yes</span>
                                                                    </label>
                                                                    <label class="radio">
                                                                        <input type="radio" name="both_same_functionality" value="0" class="visually-hidden" @if($mode_type=='edit' && $project->both_same_functionality=='0') checked @endif/> <span class="fake-label">No</span>
                                                                    </label>
                                                                </div>                                                            
                                                            </span>                                                        
                                                        </div>
                                                    </div>
                                                    <div class="invalid-feedback" id="mockupError2"></div>     
                                                </div>
                                            </div>

                                            <div class="row block-top">
                                                <div class="col">
                                                    <h3 class="block-caption">Where are we adding to the existing project?</h3>
                                                </div>
                                                <div class="col-auto">
                                                   <div class="popover-block">
                                                        <span class="block-opener">
                                                            <svg class="btn-icon"><use xlink:href="{{ asset('ui_assets/img/icons-sprite.svg') }}#info"></use></svg>
                                                        </span> 
                                                        <div class="block-hidden-content">
                                                            <p><strong >Lorem ipsum</strong> - dolor sit amet, consectetur
                                                                adipisicing elit. Facilis doloremque vitae, nisi. Mollitia
                                                                asperiores saepe pariatur repellat ullam voluptates neque!
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>                             
                                                <div class="col-md-12">
                                                    <p>Please describe what you are doing to existing project</p>
                                                </div>   
                                                <div class="form-group col-md-12">
                                                    <div class="inner custom-field-design border  rounded px-2 pt-2">
                                                        <label for="name" class="small">Please provide details for what needs done to existing project.</label>
                                                        <textarea class="form-control border-0" rows="3" name="where_add" id="where_addInput">{{$project->where_add ?? old('where_add')}}</textarea>
                                                        <div class="invalid-feedback" id="where_addError"></div>     
                                                    </div>       
                                                </div>                           
                                            </div>
                                        </form>

                                        <form action="{{ $form_action }}" method="POST" data-formid="step_no_3" class="projectforms form" @if($mode_type=='edit' && $project->is_draft && $project->left_step==3) style="display:block;" @else style="display:none;" @endif>
                                            @csrf
                                            <input type="hidden" name="left_step" value="3">
                                            <input type="hidden" name="is_draft" value="0">

                                            <div class="row block-top">
                                                <div class="col">
                                                    <h1 class="block-caption-heading">{{ ucfirst($ia) }}</h1>
                                                </div>
                                            </div>

                                            <div class="row block-top">
                                                <div class="col">
                                                    <h3 class="block-caption">Which users are we adding functionality to?</h3>
                                                </div>
                                                <div class="col-auto">
                                                   <div class="popover-block">
                                                        <span class="block-opener">
                                                            <svg class="btn-icon"><use xlink:href="{{ asset('ui_assets/img/icons-sprite.svg') }}#info"></use></svg>
                                                        </span> 
                                                        <div class="block-hidden-content">
                                                            <p><strong >Lorem ipsum</strong> - dolor sit amet, consectetur
                                                                adipisicing elit. Facilis doloremque vitae, nisi. Mollitia
                                                                asperiores saepe pariatur repellat ullam voluptates neque!
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>   
                                               <div class="col-md-12">
                                                    <p>Please enter the number of different user types that we adding functionality.</p>
                                                </div>                                                
                                            </div>
                                            <div class="block-form form mb-4 pb-2 block-top">
                                                <div class="form-fields form">
                                                    <div class="form-field input-field select-field">                                                        
                                                        <span>
                                                            <label class="field-label"> Number of User Types</label>
                                                            <select id="user_type_select" name="number_of_user_types">
                                                                @foreach(range(1,10) as $type)
                                                                    <option value="{{$type}}" @if($mode_type=='edit' && isset($project_details[$ia]) && isset($project_details[$ia]['number_of_user_types']) && $project_details[$ia]['number_of_user_types']==$type) selected @endif>{{$type}} User Type</option>
                                                                @endforeach
                                                            </select>
                                                        </span>                                                        
                                                    </div>
                                                </div>                                                
                                            </div>
                                            <div class="row block-top">
                                                <div class="col">
                                                    <h3 class="block-caption">Define the user types.</h3>
                                                </div>
                                                <div class="col-auto">
                                                   <div class="popover-block">
                                                        <span class="block-opener">
                                                            <svg class="btn-icon"><use xlink:href="{{ asset('ui_assets/img/icons-sprite.svg') }}#info"></use></svg>
                                                        </span> 
                                                        <div class="block-hidden-content">
                                                            <p><strong >Lorem ipsum</strong> - dolor sit amet, consectetur
                                                                adipisicing elit. Facilis doloremque vitae, nisi. Mollitia
                                                                asperiores saepe pariatur repellat ullam voluptates neque!
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>                             
                                                <div class="col-md-12">
                                                    <p>Based on your selection above, please define the different user types of this project.</p>
                                                </div>   
                                                <div class="form-group col-md-12">
                                                    @if(isset($userTypes) && count($userTypes))
                                                        @foreach($userTypes as $user)
                                                            <div id="user_type_field_{{$loop->index+1}}" class="user_type_field inner custom-field-design border rounded px-2 pt-2 mt-3">
                                                              <label for="user-types" class="small">User Type {{$loop->index+1}}</label>
                                                              <input class="form-control border-0"  type="text" name="user_types[]" id="user_typesInput" value="{{$user}}">                                                            
                                                            </div>                        
                                                        @endforeach    
                                                    @else
                                                    <div id="user_type_field_1" class="user_type_field inner custom-field-design border rounded px-2 pt-2 mt-3">
                                                        <label for="user-types" class="small">User Type 1</label>
                                                        <input class="form-control border-0"  type="text" name="user_types[]" id="user_typesInput">                                                            
                                                    </div> 
                                                    @endif                                                          
                                                    <div class="invalid-feedback" id="user_typesError"></div>  
                                                </div>
                                            </div>
                                        </form>

                                        <form action="{{ $form_action }}" method="POST" data-formid="step_no_4" class="projectforms form" @if($mode_type=='edit' && $project->is_draft && $project->left_step==4) style="display:block;" @else style="display:none;" @endif>
                                            @csrf
                                            <input type="hidden" name="left_step" value="4">
                                            <input type="hidden" name="is_draft" value="0">

                                            <div class="row block-top">
                                                <div class="col">
                                                    <h1 class="block-caption-heading">{{ ucfirst($ia) }}</h1>
                                                </div>
                                            </div>

                                            <div class="row block-top">
                                                <div class="col">
                                                    <h3 class="block-caption">How many pages are we adding functionality or making changes to?</h3>
                                                </div>
                                                <div class="col-auto">
                                                   <div class="popover-block">
                                                        <span class="block-opener">
                                                            <svg class="btn-icon"><use xlink:href="{{ asset('ui_assets/img/icons-sprite.svg') }}#info"></use></svg>
                                                        </span> 
                                                        <div class="block-hidden-content">
                                                            <p><strong >Lorem ipsum</strong> - dolor sit amet, consectetur
                                                                adipisicing elit. Facilis doloremque vitae, nisi. Mollitia
                                                                asperiores saepe pariatur repellat ullam voluptates neque!
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>   
                                               <div class="col-md-12">
                                                    <p>Please enter the number of different pages for this project.</p>
                                                </div>                                                
                                            </div>
                                            <div class="block-form form mb-4 pb-2 block-top">
                                                <div class="form-fields form">
                                                    <div class="form-field input-field select-field">
                                                        <span>
                                                            <label class="field-label">Number of Pages</label>
                                                            <select id="page_no_select" name="number_of_pages">
                                                                @foreach(range(1,50) as $type) 
                                                                    <option value="{{$type}}" @if($mode_type=='edit' && isset($project_details[$ia]) && isset($project_details[$ia]['number_of_pages']) && $type==$project_details[$ia]['number_of_pages']) selected @endif>{{ $type }} Page</option> 
                                                                @endforeach
                                                            </select>                                               
                                                        </span>                                                        
                                                    </div>
                                                </div>                                                
                                            </div>
                                            <div class="row block-top">
                                                <div class="col">
                                                    <h3 class="block-caption">Define those pages of this project.</h3>
                                                </div>
                                                <div class="col-auto">
                                                   <div class="popover-block">
                                                        <span class="block-opener">
                                                            <svg class="btn-icon"><use xlink:href="{{ asset('ui_assets/img/icons-sprite.svg') }}#info"></use></svg>
                                                        </span> 
                                                        <div class="block-hidden-content">
                                                            <p><strong >Lorem ipsum</strong> - dolor sit amet, consectetur
                                                                adipisicing elit. Facilis doloremque vitae, nisi. Mollitia
                                                                asperiores saepe pariatur repellat ullam voluptates neque!
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>                             
                                                <div class="col-md-12">
                                                    <p>Based on your selection above, please define the names for the different pages of this project.</p>
                                                </div>
                                                @if(isset($pages) && count($pages))
                                                    @foreach($pages as $page)
                                                        <div class="col-md-6 page_no_field" id="page_no_field_{{ $loop->index+1 }}">
                                                            <div class="form-group">                                                    
                                                                <div class="inner custom-field-design border rounded px-2 pt-2 mt-3">
                                                                    <label for="pages" class="small">Page {{$loop->index+1}} Name</label>
                                                                    <input class="form-control border-0" type="text" name="pages[]" id="pagesInput" value="{{$page}}">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @else
                                                    <div class="col-md-6 page_no_field" id="page_no_field_1">
                                                        <div class="form-group">                                                    
                                                            <div class="inner custom-field-design border rounded px-2 pt-2 mt-3">
                                                                <label for="pages" class="small">Page 1 Name</label>
                                                                <input class="form-control border-0" type="text" name="pages[]" id="pagesInput">
                                                            </div>
                                                        </div>   
                                                    </div> 
                                                @endif
                                                <div class="invalid-feedback" id="pagesError"></div>                     
                                            </div> 
                                        </form>
                                        
                                    </div>
                                </div>
                                
                                <form action="{{ $form_action }}" method="POST" data-formid="step_no_5" class="projectforms form" @if($mode_type=='edit' && $project->is_draft && $project->left_step==5) style="display:block;" @else style="display:none;" @endif>
                                    @csrf
                                    <input type="hidden" name="left_step" value="5">
                                    <input type="hidden" name="is_draft" value="0">
                                    <div class="row block-top">
                                        <div class="col">
                                            <h1 class="block-caption-heading">{{ ucfirst($ia) }}</h1>
                                        </div>
                                    </div>

                                    <div class="page-tab" id="permission">
                                        @if($mode_type=='edit')
                                            @if($project->type=="both" && $project->both_same_functionality=='0')
                                                @if($project->tech_type=="web")
                                                    @if($ia=='web' && $project->left_step>=5 && $project->is_draft==1)
                                                        @include('client.projects_existing.user-permission')
                                                    @endif

                                                    @if($ia=='mobile' && $project->is_draft==1)
                                                        @include('client.projects_existing.user-permission')
                                                    @endif
                                                @else
                                                    @if($ia=='mobile' && $project->left_step>=5 && $project->is_draft==1)
                                                        @include('client.projects_existing.user-permission')
                                                    @endif
                                                @endif
                                            @else
                                                @if($project->left_step>=5 && $project->is_draft==1)
                                                    @include('client.projects_existing.user-permission')
                                                @endif
                                            @endif
                                        @endif
                                    </div>
                                </form>
                                
                                <form action="{{ $form_action }}" method="POST" data-formid="step_no_6" class="projectforms form" @if($mode_type=='edit' && $project->is_draft && $project->left_step==6) style="display:block;" @else style="display:none;" @endif>
                                    @csrf
                                    <input type="hidden" name="left_step" value="6">
                                    <input type="hidden" name="is_draft" value="0">
                                    <input type="hidden" name="project_number" class="project_number" value="{{ $project->project_number ?? 1 }}">
                                    <div class="page-tab">
                                        <div id="pageinfo">
                                            @if($mode_type=='edit')
                                                @if($project->type=="both" && $project->both_same_functionality=='0')
                                                    @if($project->tech_type=="web")
                                                        @if($ia=='web' && $project->left_step>=6 && $project->is_draft==1)
                                                            @include('client.projects_existing.user-pageinfo')
                                                        @endif

                                                        @if($ia=='mobile' && $project->is_draft==1)
                                                            @include('client.projects_existing.user-pageinfo')
                                                        @endif
                                                    @else
                                                        @if($ia=='mobile' && $project->left_step>=6 && $project->is_draft==1)
                                                            @include('client.projects_existing.user-pageinfo')
                                                        @endif
                                                    @endif
                                                @else
                                                    @if($project->left_step>=6 && $project->is_draft==1)
                                                        @include('client.projects_existing.user-pageinfo')
                                                    @endif
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                </form>
                                
                                <form action="{{ $form_action }}" method="POST" data-formid="step_no_7" class="projectforms form" @if($mode_type=='edit' && $project->is_draft && $project->left_step==7) style="display:block;" @else style="display:none;" @endif>
                                    @csrf
                                    <input type="hidden" name="left_step" value="7">
                                    <input type="hidden" name="is_draft" value="0">  
                                    <input type="hidden" name="type" id="project_type_7" value=""> 

                                    <div class="row">
                                        <div class="col">
                                            <div class="page-tab">
                                                <div class="tab-content">
                                                    <div class="question-block">
                                                            <div class="block-top">               
                                                                <h3 class="block-caption">Budget</h3>
                                                            </div>

                                                            <div class="row block-content">
                                                                <div class="form-group col-md-12">   
                                                                    <div class="multi-value-list border rounded">  
                                                                        <div class="multi-value-item p-3">
                                                                          <input class="input-field w-100 budget" type="text" name="budget" id="budget" value="{{ $project->budget ?? old('budget') }}"> 
                                                                        </div>                                                     
                                                                    </div>
                                                                </div>  
                                                                <div class="invalid-feedback" id="budgetError"></div>        
                                                            </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col">
                                            <div class="page-tab">
                                                <div class="tab-content">
                                                    <div class="question-block">
                                                            <div class="block-top">               
                                                                <h3 class="block-caption">Do you want to be contacted by Expert Vetted Freelancers?</h3>
                                                            </div>
                                                            
                                                            <div class="block-content">
                                                                <div class="form-fields">
                                                                    <div class="form-field">
                                                                        <span>
                                                                            <div class="fields-group">
                                                                                <label class="radio">
                                                                                    <input type="radio" name="contact_developer" value="1" class="visually-hidden" required @if($mode_type=='edit' && $project->contact_developer=='1') checked @endif/> <span class="fake-label">Yes</span>
                                                                                </label>
                                                                                <label class="radio">
                                                                                    <input type="radio" name="contact_developer" value="0" class="visually-hidden" required @if( ($mode_type=='edit' && $project->contact_developer=='0') || ($mode_type=='create') ) checked @endif/> <span class="fake-label">No</span>
                                                                                </label>
                                                                            </div>
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                                <div class="invalid-feedback" id="developerError"></div> 
                                                            </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="page-tab">
                                        <div class="tab-content" id="@php echo ($mode_type=='edit') ? 'pageinfo_update' : 'pageinfo'; @endphp">

                                            <div class="column techlist" id="weblist" @if( ( $mode_type=='edit' && in_array($project->type, ['mobile']) ) || ( $mode_type=='create' ) ) style="display:none;" @endif>
                                                <div class="question-block">
                                                    <div class="block-top">               
                                                        <h3 class="block-caption">Web - What frameworks would you prefer?</h3>
                                                        <div class="block-note">
                                                            <p>Please choose from the list provided the frameworks.</p>
                                                        </div>
                                                        <div class="popover-block">
                                                            <span class="block-opener">
                                                                <svg class="btn-icon"><use xlink:href="{{ asset('ui_assets/img/icons-sprite.svg') }}#info"></use></svg>
                                                            </span> 
                                                            <div class="block-hidden-content">
                                                                <p><strong >Lorem ipsum</strong> - dolor sit amet, consectetur
                                                                        adipisicing elit. Facilis doloremque vitae, nisi. Mollitia
                                                                        asperiores saepe pariatur repellat ullam voluptates neque!
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="block-content">
                                                        <div class="block-form form">
                                                            <div class="form-fields">
                                                                <div class="form-field">
                                                                    <span>
                                                                        <div class="fields-group">   
                                                                        @foreach($webList as $list)                                
                                                                            <label class="checkbox"><input type="checkbox" class="visually-hidden" value="{{$list}}" name="web_frameworks[]" @if($mode_type=='edit' && in_array($list,$webFrameworks)) checked @endif/> <span class="fake-label">{{$list}}</span></label>                                                                   
                                                                        @endforeach
                                                                        </div>
                                                                        <div id="web_frameworksError" class="invalid-feedback" style="color: red;"></div>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="column techlist" id="mobilelist" @if( ( $mode_type=='edit' && in_array($project->type, ['web']) ) || ( $mode_type=='create' ) ) style="display:none;" @endif>
                                                <div class="question-block">
                                                    <div class="block-top">               
                                                        <h3 class="block-caption">Mobile - What frameworks would you prefer?</h3>
                                                        <div class="block-note">
                                                            <p>Please choose from the list provided the frameworks.</p>
                                                        </div>
                                                        <div class="popover-block">
                                                            <span class="block-opener">
                                                                <svg class="btn-icon"><use xlink:href="{{ asset('ui_assets/img/icons-sprite.svg') }}#info"></use></svg>
                                                            </span> 
                                                            <div class="block-hidden-content">
                                                                <p><strong >Lorem ipsum</strong> - dolor sit amet, consectetur
                                                                        adipisicing elit. Facilis doloremque vitae, nisi. Mollitia
                                                                        asperiores saepe pariatur repellat ullam voluptates neque!
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="block-content">
                                                        <div class="block-form form">
                                                            <div class="form-fields">
                                                                <div class="form-field">
                                                                    <span>
                                                                        <div class="fields-group">    
                                                                        @foreach($mobList as $list)                                          
                                                                            <label class="checkbox"><input type="checkbox" class="visually-hidden" value="{{$list}}" name="mobile_frameworks[]" @if($mode_type=='edit' && in_array($list,$mobileFrameworks)) checked @endif/> <span class="fake-label">{{$list}}</span></label>                                                                  
                                                                        @endforeach   
                                                                        </div>
                                                                        <div id="mobile_frameworksError" class="invalid-feedback" style="color: red;"></div>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
            
                                        </div>
                                    </div>
                                </form>
                            </div>
                            @endforeach

                            <div class="question-block-footer py-5 px-3 mt-5 d-flex flex-wrap">
                                <div class="col-auto">
                                    <a href="#" id="backbutton_projects" class="btn btn-primary btn-cancel btn-lg step-btn-back" data-step="6" data-url="{{ route('client.projects') }}" style="display: none;">Back</a>

                                    @if($mode_type=='edit')
                                        <a href="#" id="backbutton" class="btn btn-primary btn-cancel btn-lg" data-step="@if($project->is_draft==0) 1 @else {{$project->left_step}} @endif" data-url="{{ route('client.projects') }}">@if($project->is_draft==0) {{'Cancel'}} @else {{'Back'}} @endif</a>
                                    @else
                                        <a href="#" id="backbutton" class="btn btn-primary btn-cancel btn-lg step-btn-back" data-step="1" data-url="{{ route('client.projects') }}">Cancel</a>
                                    @endif
                                </div>
                                
                                <div class="footer-progress mx-auto">
                                    @if($mode_type=='edit')
                                        <ul>
                                            <li class="step_1"><span class="percentage">14%</span><span class="dots"></span></li>
                                            <li class="step_2 @if($project->is_draft==1 && $project->left_step==2) active @endif"><span class="percentage">28%</span> <span  class="dots"></span></li>

                                            @php
                                            if($project->type=="both" && $project->both_same_functionality=='0'){
                                                $repeat_arr = ['mobile', 'web'];
                                            } else{
                                                $repeat_arr = [$project->tech_type];
                                            }
                                            @endphp
                                            
                                            @foreach($repeat_arr as $ia)
                                                <li data-project-type="{{ $ia }}" class="step_3 @if(isset($project_details[$ia]) && $project_details[$ia]['is_draft']==1 && $project_details[$ia]['left_step']==3 && $project_details[$ia]['tech_type']==$ia) active @endif"><span class="percentage">42%</span> <span  class="dots"></span></li>
                                                <li data-project-type="{{ $ia }}" class="step_4 @if(isset($project_details[$ia]) && $project_details[$ia]['is_draft']==1 && $project_details[$ia]['left_step']==4 && $project_details[$ia]['tech_type']==$ia) active @endif"><span class="percentage">56%</span> <span  class="dots"></span></li>
                                                <li data-project-type="{{ $ia }}" class="step_5 @if(isset($project_details[$ia]) && $project_details[$ia]['is_draft']==1 && $project_details[$ia]['left_step']==5 && $project_details[$ia]['tech_type']==$ia) active @endif"><span class="percentage">70%</span> <span  class="dots"></span></li>
                                                
                                                @php
                                                $count_pp = 0;
                                                if(!empty($project_details[$ia]) && !empty($project_details[$ia]['pages_permission'])){
                                                $pagesper = unserialize($project_details[$ia]['pages_permission']);
                                                if(!empty($pagesper)){
                                                    foreach($pagesper as $keyp=>$pp){
                                                        if($keyp=='tech_type') unset($pagesper['tech_type']);
                                                        elseif($keyp=='project_type') unset($pagesper['project_type']);
                                                        else{
                                                            foreach($pp as $pp2){
                                                                $count_pp = $count_pp+1;
                                                            }
                                                        }
                                                    }
                                                }

                                                $cr_project_num = !empty($project->project_number) ? $project->project_number : 1;
                                                @endphp

                                                @for($i=1; $i<=$count_pp; $i++)
                                                    <li data-project-type="{{ $ia }}" class="step_6 @php 
                                                    if(isset($project_details[$ia]) && $project_details[$ia]['left_step']==6 && $project_details[$ia]['is_draft']==1 && $i==$cr_project_num && $ia==$project_details[$ia]['tech_type']) echo ' active'; 
                                                    @endphp" data-divide="{{$i}}"><span class="percentage">84%</span> <span  class="dots"></span></li>
                                                @endfor

                                                @php } else{ @endphp
                                                    <li data-project-type="{{ $ia }}" class="step_6 @php 
                                                    if(isset($project_details[$ia]) && $project_details[$ia]['left_step']==6 && $project_details[$ia]['is_draft']==1 && $i==$cr_project_num && $ia==$project_details[$ia]['tech_type']) echo ' active'; 
                                                    @endphp" data-divide="1"><span class="percentage">84%</span> <span  class="dots"></span></li>
                                                @php } @endphp
                                            @endforeach

                                            <li class="step_7 @if($project->is_draft==1 && $project->left_step==7) active @endif"><span class="percentage">100%</span> <span  class="dots"></span></li>
                                        </ul>
                                    @else
                                        <ul>
                                            <li class="step_1 active"><span class="percentage">14%</span><span class="dots"></span></li>
                                            <li class="step_2"><span class="percentage">28%</span> <span class="dots"></span></li>
                                            <li class="step_3" data-project-type="mobile"><span class="percentage">42%</span> <span class="dots"></span></li>
                                            <li class="step_4" data-project-type="mobile"><span class="percentage">56%</span> <span class="dots"></span></li>
                                            <li class="step_5" data-project-type="mobile"><span class="percentage">70%</span> <span class="dots"></span></li>
                                            <li class="step_6" data-project-type="mobile" data-divide="1"><span class="percentage">84%</span> <span class="dots"></span></li>
                                            <li class="step_7"><span class="percentage">100%</span> <span class="dots"></span></li>
                                        </ul>
                                    @endif
                                    
                                    @if($mode_type=='edit' && $project->is_draft==1)
                                        <p id="steptext">{{ $project->left_step }} of 7 Steps Remaining</p>
                                    @else
                                        <p id="steptext">7 of 7 Steps Remaining</p>
                                    @endif
                                </div>
                                <div class="col-auto">
                                    <button type="button" class="btn btn-lg step-btn" id="submitbtn_projects" data-step="6" style="display: none;">Next</button>

                                    @if($mode_type=='edit')
                                        <button type="button" class="btn btn-lg step-btn" id="submitbtn" data-step="@if($project->is_draft==1) {{$project->left_step}} @else 1 @endif">Next</button>
                                    @else
                                        <button type="button" class="btn btn-lg step-btn" id="submitbtn" data-step="1">Next</button>
                                    @endif
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </section>
        </div>
    </main>  
</div>
<!-- Complete Project Modal -->
<div class="modal" id="questionnaire-completed-modal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <button class="modal-close" aria-label="Close modal"></button>

            <div class="modal-caption">
                <h3 class="mc-title">Project Questionnaire Completed</h3>
                <p class="mc-subtitle">Congratulations on completing your questionnaire. In order <br class="sm-hidden">to add this project to your dashboard, Scope Builder requires a <br class="sm-hidden">small payment fees of $10.00.</p>
            </div>

            <div class="big-modal-icon">
                <img src="/ui_assets/img/icons/wallet.svg" alt="">
            </div>

            <div class="modal-footer">
                <a href="{{ route('client.projects') }}" class="btn">Proceed To Payment</a>
            </div>
        </div>
    </div>
</div>   

@endsection
@push('ui.script')
<script src="{{ asset('js/form_existing.js') }}"></script>
<script src="{{ asset('js/jquery.priceformat.min.js') }}"></script>

<script type="text/javascript">
$(function(){
    $('.budget').priceformat({
      defaultValue: "{{ ( $mode_type=='edit' && $project->budget>'0.00' ) ? preg_replace('/[^\d]/', '', $project->budget) : 0 }}",
      decimalSeparator:'.',
      thousandsSeparator:','
    });
});
</script>

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


.loader-con  #loading {
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
@endpush