<div class="project-wrap">
    @php
        $row_number = 1;
    @endphp
    @if(isset($pagesInformation) && count($pagesInformation))
        @foreach($pagesInformation as $page) 
            @php
                $indexKey = $loop->index+1;
                $name = str_replace(' ', '_',$page['page']);
                $users = $page['users'];  
                $pageIndex = 0;     
                $duplicateID = random_int(100000, 999999);
            @endphp
            @foreach($users as $user)
                @php
                    $userName           = $user['user'];
                    $information        = $user['information'];
                    $actions            = $user['actions'];
                    $informationmockup = [];
                    if(!empty($user['permockup'])){
                        $informationmockup      = $user['permockup'];
                    }
                      
                    $uniqueId = random_int(100000, 999999);
                    $uniqueId2 = random_int(100000, 999999);        
                @endphp
                <div class="card card-project" style="display: none;" data-number="{{$row_number}}">
                    <div class="card-header">
                        <div class="row block-top">
                            <div class="col">
                                <h1 class="block-caption-heading show-on-mobile">Mobile</h1>
                                <h1 class="block-caption-heading show-on-web">Web</h1>
                                <h1 class="block-caption-heading show-on-both">Both</h1>
                            </div>
                        </div>
                        
                        <h1 class="block-caption">{{$page['page']}}</h1>
                        <h3 class="page-user">{{$userName}}</h3>

                        <div class="popover-block">
                            <span class="block-opener">
                                <svg class="btn-icon"><use xlink:href="{{ asset('ui_assets/img/icons-sprite.svg') }}#info"></use></svg>
                            </span>
                            <div class="block-hidden-content">
                                <p>
                                <strong>Lorem ipsum</strong> - dolor sit amet, consectetur adipisicing elit. Facilis doloremque vitae, nisi. Mollitia asperiores saepe pariatur repellat ullam voluptates neque!
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="column col-md-6 mb-4 pinfowrap @if(!$loop->first) copyto @else copyfrom @endif" data-copy="{{$duplicateID}}">         
                                <div class="question-block">
                                    <div class="block-top">                                       
                                        <h3 class="page-user_info">Information Displayed</h3>
                                        <div class="block-note">
                                            <p>Please define what information is displayed to this user.</p>
                                        </div>
                                    </div>
                                    <span>
                                        <div class="block-content">
                                            <div class="block-form form">
                                                <div class="form-fields">
                                                    <div class="form-field">
                                                        <div class="values-component">
                                                            <div class="values-wrap">
                                                            @if($information)
                                                                @foreach($information as $info)
                                                                    <div class="values-list informationfield" id="{{$uniqueId+$loop->index}}">
                                                                        <div class="item">
                                                                            <div class="input-field" style="width: -webkit-fill-available;">
                                                                                <textarea class="expanding-textarea" rows="1" name="page[{{$name}}][{{$userName}}][user][]">{{$info['text']}}</textarea>
                                                                            </div>
                                                                            <button type="button" class="action-btn rminfofield @if($loop->first) d-none2 @endif" data-id="{{$uniqueId+$loop->index}}">
                                                                                <svg class="btn-icon"><use xlink:href="{{ asset('ui_assets/img/icons-sprite.svg') }}#trash"></use></svg>
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            @else
                                                                <div class="values-list informationfield" id="{{$uniqueId}}">
                                                                    <div class="item">
                                                                        <div class="input-field" style="width: -webkit-fill-available;">
                                                                            <textarea class="expanding-textarea" rows="1" name="page[{{$name}}][{{$userName}}][user][]"></textarea>
                                                                        </div>
                                                                        <button type="button" class="action-btn rminfofield d-none2" data-id="{{$uniqueId}}">
                                                                            <svg class="btn-icon"><use xlink:href="{{ asset('ui_assets/img/icons-sprite.svg') }}#trash"></use></svg>
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        </div>

                                                            <div id="page_{{$name}}_{{$userName}}_user_{{$pageIndex}}_Error" class="invalid-feedback {{$uniqueId}}" style="color: red;"></div>
                                                            <button type="button" class="add-btn infoadd" data-id="{{$uniqueId}}">
                                                                <svg class="btn-icon"><use xlink:href="{{ asset('ui_assets/img/icons-sprite.svg') }}#plus"></use></svg>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </span>
                                </div>
                            </div>
                            <div class="column col-md-6 mb-4 actwrap @if(!$loop->first) copyto @else copyfrom @endif" data-copy="{{$duplicateID}}">  
                                <div class="question-block">
                                    <div class="block-top">
                                        <h3 class="page-user_info">Performable Actions</h3>
                                        <div class="block-note">
                                            <p>Please define what actions this user can perform.</p>
                                        </div>
                                    </div>
                                    <span>
                                        <div class="block-content">
                                            <div class="block-form form">
                                                <div class="form-fields">
                                                    <div class="form-field">
                                                        <div class="values-component">
                                                            <div class="values-wrap">
                                                                @if($actions)
                                                                    @foreach($actions as $info)    
                                                                    <div class="values-list perfomableinput" id="{{$uniqueId2+$loop->index}}">
                                                                        <div class="item">
                                                                            <div class="input-field" style="width: -webkit-fill-available;">
                                                                                 <textarea class="expanding-textarea" rows="1" name="action[{{$name}}][{{$userName}}][user][]">{{$info['text']}}</textarea>
                                                                            </div>
                                                                            <button type="button" class="action-btn rminfofield d-none2" data-id="{{$uniqueId2+$loop->index}}" >
                                                                                <svg class="btn-icon"><use xlink:href="{{ asset('ui_assets/img/icons-sprite.svg') }}#trash"></use></svg>
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                    @endforeach
                                                                @else
                                                                <div class="values-list perfomableinput" id="{{$uniqueId2}}">
                                                                    <div class="item">
                                                                        <div class="input-field" style="width: -webkit-fill-available;">
                                                                            <textarea class="expanding-textarea" rows="1" name="action[{{$name}}][{{$userName}}][user][]"></textarea>
                                                                        </div>
                                                                        <button type="button" class="action-btn rminfofield d-none2" data-id="{{$uniqueId2}}" >
                                                                            <svg class="btn-icon"><use xlink:href="{{ asset('ui_assets/img/icons-sprite.svg') }}#trash"></use></svg>
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                                @endif
                                                            </div>

                                                            <div id="action_{{$name}}_{{$userName}}_user_{{$pageIndex}}_Error" class="invalid-feedback {{$uniqueId2}}" style="color: red;"></div>
                                                            <button type="button" class="add-btn perfomableadd" data-id="{{$uniqueId2}}">
                                                                <svg class="btn-icon"><use xlink:href="{{ asset('ui_assets/img/icons-sprite.svg') }}#plus"></use></svg>
                                                            </button>                                            
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </span>
                                </div>
                            </div> 
                            @if($loop->remaining>0)
                                <div class="column col-md-6 mb-4">
                                    <div class="question-block">
                                        <div class="block-top">
                                            <h3 class="block-caption">
                                                Duplicate Information
                                            </h3>
                                            <div class="block-note">
                                                <p>
                                                    Please select whether you'd like to duplicate this information for the next user.
                                                </p>
                                            </div>
                                            <div class="popover-block"><span class="block-opener"><svg class="btn-icon"><use xlink:href="{{ asset('ui_assets/img/icons-sprite.svg') }}#info"></use></svg></span>
                                                <div class="block-hidden-content">
                                                    <p><strong>Lorem ipsum</strong> - dolor sit amet, consectetur adipisicing elit. Facilis doloremque vitae, nisi. Mollitia asperiores saepe pariatur repellat ullam voluptates neque!
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="block-content">
                                            <div class="block-form form">
                                                <div class="form-fields">
                                                    <div class="form-field">
                                                        <div class="fields-group">
                                                            <label class="checkbox">
                                                                <input type="checkbox" class="visually-hidden duplicate" data-copy="{{$duplicateID}}"> 
                                                                <span class="fake-label">Duplicate Information</span></label>
                                                                <input type="hidden" class="duplicate-data-changed" value="0" />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if($project->mockup=="0" && $loop->iteration==1)
                                <div class="column col-md-12 mb-4">
                                    <div class="question-block">
                                        <div class="block-top">
                                            <h3 class="block-caption">Mock Up or Example</h3>
                                            <div class="block-note">
                                                <p>Please provide a link for the mockup of this page, if you have any.</p>
                                            </div>
                                        </div>

                                        <span>
                                            <div class="block-content">
                                                <div class="block-form form">
                                                    <div class="form-fields">
                                                        <div class="form-field">
                                                            <div class="values-component">
                                                                <div class="values-wrap">
                                                                @if($informationmockup)
                                                                    @foreach($informationmockup as $info)
                                                                    <div class="values-list mockupinput" id="{{$uniqueId2}}">
                                                                        <div class="item">
                                                                            <div class="input-field" style="width: -webkit-fill-available;">
                                                                                <input type="text" name="permockup[{{$name}}][{{$userName}}][user][]" value="{{$info['text']}}" /></div>
                                                                        </div>
                                                                    </div>
                                                                    @endforeach
                                                                @else
                                                                    <div class="values-list mockupinput" id="{{$uniqueId2}}">
                                                                        <div class="item">
                                                                            <div class="input-field" style="width: -webkit-fill-available;">
                                                                                <input type="text" name="permockup[{{$name}}][{{$userName}}][user][]" /></div>
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                                </div>

                                                                <div id="action_{{$name}}_{{$userName}}_user_{{$pageIndex}}_Error" class="invalid-feedback" style="color: red;"></div>                                    
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </span>
                                    </div>
                                </div>        
                            @endif
                        </div>
                    </div>
                </div>
                @php
                    $row_number = $row_number+1;
                @endphp
            @endforeach
        @endforeach
    <!-----------------   If data not exists     ------------------->
    @else
    @foreach($pages as $page)
        @php
            $indexKey = $loop->index+1;
            $name = str_replace(' ', '_',$page);
            $users = Session::get('formdata');  
            $pageIndex = 0;     
            $duplicateID = random_int(100000, 999999);
        @endphp
        @foreach($userTypes[$name] as $user)
        @php
            $uniqueId = random_int(100000, 999999);
            $uniqueId2 = random_int(100000, 999999);        
        @endphp
        <div class="card card-project" style="display: none;" data-number="{{$row_number}}">
            <div class="card-header">
                <div class="row block-top">
                    <div class="col">
                        <h1 class="block-caption-heading show-on-mobile">Mobile</h1>
                        <h1 class="block-caption-heading show-on-web">Web</h1>
                        <h1 class="block-caption-heading show-on-both">Both</h1>
                    </div>
                </div>

                <h1 class="block-caption">{{$page}}</h1>
                <h3 class="page-user">{{$user}}</h3>

                <div class="popover-block">
                    <span class="block-opener">
                        <svg class="btn-icon"><use xlink:href="{{ asset('ui_assets/img/icons-sprite.svg') }}#info"></use></svg>
                    </span>
                    <div class="block-hidden-content">
                        <p>
                        <strong>Lorem ipsum</strong> - dolor sit amet, consectetur adipisicing elit. Facilis doloremque vitae, nisi. Mollitia asperiores saepe pariatur repellat ullam voluptates neque!
                        </p>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="column col-md-6 mb-4 pinfowrap @if(!$loop->first) copyto @else copyfrom @endif" data-copy="{{$duplicateID}}">
                        <div class="question-block">
                            <div class="block-top">                
                                <h3 class="page-user_info">Information Displayed</h3>
                                <div class="block-note">
                                    <p>Please define what information is displayed to this user.</p>
                                </div>
                            </div>
                            <span>
                                <div class="block-content">
                                    <div class="block-form form">
                                        <div class="form-fields">
                                            <div class="form-field">
                                                <div class="values-component">
                                                    <div class="values-wrap">
                                                        <div class="values-list informationfield" id="{{$uniqueId}}">
                                                            <div class="item">
                                                                <div class="input-field" style="width: -webkit-fill-available;">
                                                                    <textarea class="expanding-textarea" rows="1" name="page[{{$name}}][{{$user}}][user][]"></textarea>
                                                                </div>
                                                                <button type="button" class="action-btn rminfofield d-none2" data-id="{{$uniqueId}}">
                                                                    <svg class="btn-icon"><use xlink:href="{{ asset('ui_assets/img/icons-sprite.svg') }}#trash"></use></svg>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div id="page_{{$name}}_{{$user}}_user_{{$pageIndex}}_Error" class="invalid-feedback {{$uniqueId}}" style="color: red;"></div>
                                                    <button type="button" class="add-btn infoadd" data-id="{{$uniqueId}}">
                                                        <svg class="btn-icon"><use xlink:href="{{ asset('ui_assets/img/icons-sprite.svg') }}#plus"></use></svg>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </span>
                        </div>
                    </div>
                    <div class="column col-md-6 mb-4 actwrap @if(!$loop->first) copyto @else copyfrom @endif" data-copy="{{$duplicateID}}"> 
                        <div class="question-block">
                            <div class="block-top">
                                <h3 class="page-user_info">Performable Actions</h3>
                                <div class="block-note">
                                    <p>Please define what actions this user can perform.</p>
                                </div>
                            </div>
                            <span>
                                <div class="block-content">
                                    <div class="block-form form">
                                        <div class="form-fields">
                                            <div class="form-field">
                                                <div class="values-component">
                                                    <div class="values-wrap">
                                                        <div class="values-list perfomableinput" id="{{$uniqueId2}}">
                                                            <div class="item">
                                                                <div class="input-field" style="width: -webkit-fill-available;">
                                                                    <textarea class="expanding-textarea" rows="1" name="action[{{$name}}][{{$user}}][user][]"></textarea>
                                                                </div>
                                                                <button type="button" class="action-btn rminfofield d-none2" data-id="{{$uniqueId2}}" >
                                                                    <svg class="btn-icon"><use xlink:href="{{ asset('ui_assets/img/icons-sprite.svg') }}#trash"></use></svg>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div id="action_{{$name}}_{{$user}}_user_{{$pageIndex}}_Error" class="invalid-feedback {{$uniqueId2}}" style="color: red;"></div>
                                                    <button type="button" class="add-btn perfomableadd" data-id="{{$uniqueId2}}">
                                                        <svg class="btn-icon"><use xlink:href="{{ asset('ui_assets/img/icons-sprite.svg') }}#plus"></use></svg>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </span>
                        </div>
                    </div>

                    @if($loop->remaining>0) 
                        <div class="column col-md-6 mb-4">
                            <div class="question-block">
                                <div class="block-top">
                                    <h3 class="block-caption">
                                        Duplicate Information
                                    </h3>
                                    <div class="block-note">
                                        <p>
                                            Please select whether you'd like to duplicate this information for the next user.
                                        </p>
                                    </div>
                                    <div class="popover-block"><span class="block-opener"><svg class="btn-icon"><use xlink:href="{{ asset('ui_assets/img/icons-sprite.svg') }}#info"></use></svg></span>
                                        <div class="block-hidden-content">
                                            <p><strong>Lorem ipsum</strong> - dolor sit amet, consectetur adipisicing elit. Facilis doloremque vitae, nisi. Mollitia asperiores saepe pariatur repellat ullam voluptates neque!
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="block-content">
                                    <div class="block-form form">
                                        <div class="form-fields">
                                            <div class="form-field">
                                                <div class="fields-group">
                                                    <label class="checkbox">
                                                        <input type="checkbox" class="visually-hidden duplicate" data-copy="{{$duplicateID}}"> 
                                                        <span class="fake-label">Duplicate Information</span></label>
                                                        <input type="hidden" class="duplicate-data-changed" value="0" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($loop->iteration==1)
                        <div class="column col-md-12 mb-4">
                            <div class="question-block">
                                <div class="block-top">
                                    <h3 class="block-caption">Mock Up or Example</h3>
                                    <div class="block-note">
                                        <p>Please provide a link for the mockup of this page, if you have any.</p>
                                    </div>
                                </div>

                                <span>
                                    <div class="block-content">
                                        <div class="block-form form">
                                            <div class="form-fields">
                                                <div class="form-field">
                                                    <div class="values-component">
                                                        <div class="values-wrap">
                                                            <div class="values-list mockupinput" id="{{$uniqueId2}}">
                                                                <div class="item">
                                                                    <div class="input-field" style="width: -webkit-fill-available;">
                                                                        <input type="text" name="permockup[{{$name}}][{{$user}}][user][]" /></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                        <div id="action_{{$name}}_{{$user}}_user_{{$pageIndex}}_Error" class="invalid-feedback" style="color: red;"></div>                                    
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </span>
                            </div>
                        </div>    
                    @endif
                </div>
            </div>
        </div>
            @php
                $row_number = $row_number+1;
            @endphp
        @endforeach  
    @endforeach
    @endif
</div>