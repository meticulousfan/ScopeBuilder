@extends('client.layout.main')
@section('page-title')
    Create Project
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
    $projectList        = unserialize($project->example_projects);
    $userTypes          = $project->user_types ? unserialize($project->user_types) : [];    
    $pages              = $project->pages ? unserialize($project->pages) : [];
    $permission         = $project->pages_permission ? unserialize($project->pages_permission) : [];    
    $pagesInformation   = $project->pages_information ? unserialize($project->pages_information) : [];    
    $webList            = \App\Models\ClientProject::WEB_TECH;
    $mobList            = \App\Models\ClientProject::MOB_TECH;   
    $webFrameworks      = $project->web_frameworks ? unserialize($project->web_frameworks): []; 
    $mobileFrameworks   = $project->mobile_frameworks ? unserialize($project->mobile_frameworks) : [];
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
                    <h1 class="page-caption font-weight-bold">Add New Project</h1>
                </div>
                 <div class="buttons pr-3">
                    <button type="button" value="Save For Later" class="btn btn-primary later-h-btn @if($project->left_step==1) d-none @endif" id="savelater">Save For Later</button>
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
            <section class="default-section with-footer px-3 mobile-project@php if($project->left_step=='6') echo ' project-wrap'; @endphp" id="clientproject">
                <input type="hidden" id="tech_type" value="mobile" />
                <div class="container-fluid">
                        <div class="card shadow-sm border-0 pt-4 pb-0 px-3 mb-5">
                            <div class="loader-con d-none justify-content-center h-100 w-100 align-items-center"><div id="loading"></div></div>
                            <div class="card-body pt-3 pb-0 px-2">
                                <div class="col-md-7 px-0">
                                    <div class="question-block">
                                        <form action="{{ route('client.projects.update',$project->uuid) }}" method="POST" id="step_no_1" class="projectforms form" @if($project->is_draft) style="display:none;" @endif>
                                          @csrf                                          
                                          <input type="hidden" name="left_step" value="1">
                                          <input type="hidden" name="is_draft" value="0">
                                            <div class="row block-top">
                                                <div class="col">
                                                    <h3 class="block-caption">What is the name of the project?</h3>
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
                                                    <p>Please enter a suitable description for your project.</p>
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
                                                    <h3 class="block-caption">Do you have mock up designs for the project?</h3>
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
                                                                    <input type="radio" name="mockup" value="1" class="visually-hidden" @if($project->mockup) {{"checked"}}  @endif/> <span class="fake-label">Yes</span>
                                                                </label>
                                                                <label class="radio">
                                                                    <input id="mockupInput" type="radio" name="mockup" value="0" class="visually-hidden" @if(!$project->mockup) {{"checked"}}  @endif/> <span class="fake-label">NO</span>
                                                                </label>                                                                
                                                            </div>                                                            
                                                        </span>                                                        
                                                    </div>
                                                </div>
                                                <div class="invalid-feedback" id="mockupError"></div>     
                                            </div>  
                                            <div class="custom-file-upload block-top @if(!$project->mockup) d-none @endif " id="mockupfile">
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
                                                              <input class="form-control border-0"  type="url" name="mockup_url" id="mockup_urlInput" value="{{$project->mockup_url ?? old('mockup_url')}}"> 
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
                                                <div class="invalid-feedback" id="example_projectsError"></div>        
                                            </div>                     
                                            <div class="button-bottom ">                               
                                                <button id="addmoreexample" type="button" class="btn btn-primary btn-lg py-3">
                                                    <svg  class="btn-icon"><use  xlink:href="{{ asset('ui_assets/img/icons-sprite.svg') }}#plus"></use></svg>
                                                </button>
                                            </div>       
                                        </form>
                                        <form action="{{ route('client.projects.update',$project->uuid) }}" method="POST" id="step_no_2" class="projectforms form" @if($project->is_draft && $project->left_step==2) style="display:block; @else style="display:none; @endif">
                                          @csrf                                          
                                            <input type="hidden" name="left_step" value="2">
                                            <input type="hidden" name="is_draft" value="0">
                                            <div class="row block-top">
                                                <div class="col">
                                                    <h3 class="block-caption">What is the type of your project?</h3>
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
                                                                    <input type="radio" name="type" value="mobile" class="visually-hidden " @if($project->type=='mobile') checked @endif/> <span class="fake-label">Mobile</span>
                                                                </label>
                                                                <label class="radio">
                                                                    <input type="radio" name="type" value="web" class="visually-hidden" @if($project->type=='web') checked @endif/> <span class="fake-label">Web</span>
                                                                </label>
                                                                 <label class="radio">
                                                                    <input type="radio" name="type" value="both" class="visually-hidden" @if($project->type=='both') checked @endif/> <span class="fake-label">Both</span>
                                                                </label>                                                                
                                                            </div>                                                            
                                                        </span>                                                        
                                                    </div>
                                                </div>
                                                <div class="invalid-feedback" id="mockupError"></div>     
                                            </div>
                                        </form>
                                        <form action="{{ route('client.projects.update',$project->uuid) }}" method="POST" id="step_no_3" class="projectforms form" @if($project->is_draft && $project->left_step==3) style="display:block; @else style="display:none; @endif">
                                          @csrf                                          
                                            <input type="hidden" name="left_step" value="3">
                                            <input type="hidden" name="is_draft" value="0">
                                            <div class="row block-top">
                                                <div class="col">
                                                    <h3 class="block-caption">How many types of users will this project have?</h3>
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
                                                    <p>Please enter the number of different user types this project will have.</p>
                                                </div>                                                
                                            </div>
                                            <div class="block-form form mb-4 pb-2 block-top">
                                                <div class="form-fields form">
                                                    <div class="form-field input-field select-field">                                                        
                                                        <span>
                                                            <label class="field-label"> Number of User Types</label>
                                                            <select id="user_type_select" name="number_of_user_types">
                                                                @foreach(range(1,10) as $type)
                                                                    <option value="{{$type}}" @if($project->number_of_user_types==$type) selected @endif>{{$type}} User Type</option> 
                                                                @endforeach
                                                            </select>                                               
                                                        </span>                                                        
                                                    </div>
                                                </div>                                                
                                            </div>
                                            <div class="row block-top">
                                                <div class="col">
                                                    <h3 class="block-caption">Define the user types of your project.</h3>
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
                                                    @if(count($userTypes))
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
                                         <form action="{{ route('client.projects.update',$project->uuid) }}" method="POST" id="step_no_4" class="projectforms form" @if($project->is_draft && $project->left_step==4) style="display:block; @else style="display:none; @endif">
                                          @csrf                                          
                                            <input type="hidden" name="left_step" value="4">
                                            <input type="hidden" name="is_draft" value="0">
                                            <div class="row block-top">
                                                <div class="col">
                                                    <h3 class="block-caption">How many pages will this project have?</h3>
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
                                                                    <option value="{{$type}}" @if($project->number_of_pages==$type) selected @endif> {{$type}} Page</option> 
                                                                @endforeach
                                                            </select>                                               
                                                        </span>                                                        
                                                    </div>
                                                </div>                                                
                                            </div>
                                            <div class="row block-top">
                                                <div class="col">
                                                    <h3 class="block-caption">Define the pages of your project.</h3>
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
                                                @if(count($pages))                                                                                 
                                                    @foreach($pages as $page)
                                                        <div class="col-md-6 page_no_field" id="page_no_field_{{$loop->index+1}}">
                                                            <div class="form-group">                                                    
                                                                    <div class="inner custom-field-design border rounded px-2 pt-2 mt-3">
                                                                      <label for="pages" class="small">Page {{$loop->index+1}} Name</label>
                                                                      <input class="form-control border-0"  type="text" name="pages[]" id="pagesInput" value="{{$page}}">                                                            
                                                                    </div>
                                                            </div>   
                                                        </div>  
                                                    @endforeach 
                                                @else
                                                    <div class="col-md-6 page_no_field" id="page_no_field_1">
                                                        <div class="form-group">                                                    
                                                                <div class="inner custom-field-design border rounded px-2 pt-2 mt-3">
                                                                  <label for="pages" class="small">Page 1 Name</label>
                                                                  <input class="form-control border-0"  type="text" name="pages[]" id="pagesInput">                                                            
                                                                </div>
                                                        </div>   
                                                    </div>
                                                @endif
                                                 <div class="invalid-feedback" id="pagesError"></div>                     
                                            </div> 
                                        </form>
                                    </div>
                                </div> 
                                <form action="{{ route('client.projects.update',$project->uuid) }}" method="POST" id="step_no_5" class="projectforms form" @if($project->is_draft && $project->left_step==5) style="display:block; @else style="display:none; @endif">
                                    @csrf                                    
                                    <input type="hidden" name="left_step" value="5">
                                    <input type="hidden" name="is_draft" value="0">  
                                    <div class="page-tab" id="permission">
                                        @if($project->left_step>=5 && $project->is_draft==1)
                                                @include('client.projects.update.user-permission')
                                        @endif
                                    </div>
                                </form>    
                                <form action="{{ route('client.projects.update',$project->uuid) }}" method="POST" id="step_no_6" class="projectforms form" @if($project->is_draft && $project->left_step==6) style="display:block; @else style="display:none; @endif">
                                    @csrf                                    
                                    <input type="hidden" name="left_step" value="6">
                                    <input type="hidden" name="is_draft" value="0">
                                    <input type="hidden" name="project_number" class="project_number" value="1"> 
                                    <div class="page-tab">
                                        <div id="pageinfo">
                                            @if($project->left_step>=6 && $project->is_draft==1)
                                                @include('client.projects.update.user-pageinfo')
                                            @endif
                                        </div>
                                    </div>
                                </form>
                                <form action="{{ route('client.projects.update',$project->uuid) }}" method="POST" id="step_no_7" class="projectforms form" @if($project->is_draft && $project->left_step==7) style="display:block; @else style="display:none; @endif">
                                    @csrf
                                    <input type="hidden" name="left_step" value="7">
                                    <input type="hidden" name="is_draft" value="0">  
                                    <input type="hidden" name="type" id="project_type_7" value="{{$project->type}}">

                                    <div class="row">
                                        <div class="col">
                                            <div class="page-tab">
                                                <div class="tab-content">
                                                    <div class="column techlist">
                                                        <div class="question-block">
                                                            <div class="block-top">               
                                                                <h3 class="block-caption">Budget</h3>
                                                            </div>

                                                            <div class="row block-content">
                                                                <div class="form-group col-md-12">   
                                                                    <div class="multi-value-list border rounded">  
                                                                        <div class="multi-value-item p-3">
                                                                          <input class="input-field w-100" type="text" name="budget" id="budget" value="{{$project->budget}}"> 
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
                                    </div>

                                    <div class="row">
                                        <div class="col">
                                            <div class="page-tab">
                                                <div class="tab-content">
                                                    <div class="column techlist">
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
                                                                                    <input type="radio" name="contact_developer" value="1" class="visually-hidden" required @if($project->contact_developer=='1') checked @endif/> <span class="fake-label">Yes</span>
                                                                                </label>
                                                                                <label class="radio">
                                                                                    <input type="radio" name="contact_developer" value="0" class="visually-hidden" required @if($project->contact_developer=='0') checked @endif/> <span class="fake-label">No</span>
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
                                    </div>

                                    <div class="page-tab">
                                        <div class="tab-content" id="pageinfo_update">
                                            <div class="column techlist" id="weblist" @if(in_array($project->type,['mobile'])) style="display:none;" @endif>
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
                                                                            <label class="checkbox"><input type="checkbox" class="visually-hidden" value="{{$list}}" name="web_frameworks[]" @if(in_array($list,$webFrameworks)) checked @endif/> <span class="fake-label">{{$list}}</span></label>                                                                   
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
                                                <div class="column techlist" id="mobilelist" @if(in_array($project->type,['web'])) style="display:none;" @endif>
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
                                                                            <label class="checkbox"><input type="checkbox" class="visually-hidden" value="{{$list}}" name="mobile_frameworks[]" @if(in_array($list,$mobileFrameworks)) checked @endif/> <span class="fake-label">{{$list}}</span></label>                                                                  
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
                   
                                <div class="question-block-footer py-5 px-3 mt-5 d-flex flex-wrap">
                                    <div class="col-auto">
                                        <a href="#" id="backbutton_projects" class="btn btn-primary btn-cancel btn-lg step-btn-back" data-step="6" data-url="{{ route('client.projects') }}" style="display: none;">Back</a>
                                        <a href="#" id="backbutton" class="btn btn-primary btn-cancel btn-lg" data-step="@if($project->is_draft==0) 1 @else {{$project->left_step}} @endif" data-url="{{ route('client.projects') }}">@if($project->is_draft==0) {{'Cancel'}} @else {{'Back'}} @endif</a>
                                    </div>
                                    <div class="footer-progress mx-auto">
                                    

                                        <ul>
                                            <li id="pc_1" @if($project->is_draft==0) class="active" @endif><span class="percentage">14%</span><span class="dots"></span></li>
                                            <li id="pc_2" @if($project->left_step==2 && $project->is_draft==1) class="active" @endif><span class="percentage">28%</span> <span  class="dots"></span></li>
                                            <li id="pc_3" @if($project->left_step==3 && $project->is_draft==1) class="active" @endif><span class="percentage">42%</span> <span  class="dots"></span></li>
                                            <li id="pc_4" @if($project->left_step==4 && $project->is_draft==1) class="active" @endif><span class="percentage">56%</span> <span  class="dots"></span></li>
                                            <li id="pc_5" @if($project->left_step==5 && $project->is_draft==1) class="active" @endif><span class="percentage">70%</span> <span  class="dots"></span></li>
                                            
                                            @php
                                            $count_pp = 0;
                                            $pagesper = unserialize($project->pages_permission);
                                            if(!empty($pagesper)){
                                                foreach($pagesper as $pp){
                                                    foreach($pp as $pp2){
                                                        $count_pp = $count_pp+1;
                                                    }
                                                }
                                            }

                                            $cr_project_num = !empty($project->project_number) ? $project->project_number : 1;
                                            @endphp

                                            @for($i=1; $i<=$count_pp; $i++)
                                                <li class="step_6@php 
                                                if($project->left_step==6 && $project->is_draft==1 && $i==$cr_project_num) echo ' active'; 
                                                @endphp" data-divide="{{$i}}"><span class="percentage">84%</span> <span  class="dots"></span></li>
                                            @endfor

                                            <li id="pc_7" @if($project->left_step==7 && $project->is_draft==1) class="active" @endif><span class="percentage">100%</span> <span  class="dots"></span></li>
                                        </ul>
                                        @if($project->is_draft==1)
                                            <p id="steptext">{{$project->left_step}} of 7 Steps Remaining</p>
                                        @else
                                            <p id="steptext">7 of 7 Steps Remaining</p>
                                        @endif
                                    </div>
                                    <div class="col-auto">
                                        <button type="button" class="btn btn-lg step-btn" id="submitbtn_projects" data-step="6" style="display: none;">Next</button>
                                        <button type="button" class="btn btn-lg step-btn" id="submitbtn" data-step="@if($project->is_draft==1) {{$project->left_step}} @else 1 @endif">Next</button>
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
<script src="{{ asset('js/form.js') }}"></script>
<script src="{{ asset('js/jquery.priceformat.min.js') }}"></script>

<script type="text/javascript">
$(function(){
    $('#budget').priceformat({
      defaultValue: '{{$project->budget}}',
      decimalSeparator: '.',
      thousandsSeparator: ','
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