@foreach($pages as $page)
    <div class="tab-content @if(!$loop->first) mt-3 @endif">            
        <div class="column" style="width: 120%;">
            <div class="question-block">
                <div class="block-top">
                    @php
                        $indexKey = $loop->index+1;
                        $name = str_replace(' ', '_',$page);

                        if(!empty($permission[$name])){
                            $permissionList = $permission[$name];
                        }else{
                            $permissionList = [];
                        }
                    @endphp
                    <h3 class="block-caption"> Page {{$indexKey}} - {{$page}} Page</h3>
                    <div class="block-note">
                        <p> Please choose which users will have access to this page.</p>
                    </div>
                    <div class="popover-block">
                        <label class="checkbox">
                            <input type="checkbox" class="visually-hidden checkall" id="{{$loop->index+1}}" /> 
                            <span class="fake-label" style="border: none;">Check All</span>
                        </label>
                    </div>
                </div>
                <div class="block-content">
                    <div class="block-form form">
                        <div class="form-fields">
                            <div class="form-field">
                                <span>
                                    <div class="fields-group">
                                        @foreach($userTypes as $type)
                                            <label class="checkbox">
                                                <input type="checkbox" class="visually-hidden checkme_{{$indexKey}}" value="{{$type}}" name="{{$name}}[]" @if(!empty($permissionList) && in_array($type,$permissionList)) checked @endif/> 
                                                <span class="fake-label">{{$type}}</span>
                                            </label> 
                                        @endforeach                                         
                                    </div>
                                    <div id="{{$name}}Error" class="invalid-feedback" style="color: red;"></div>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endforeach

