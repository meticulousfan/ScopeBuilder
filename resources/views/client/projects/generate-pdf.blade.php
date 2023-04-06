<!DOCTYPE html>
<html lang="en" style="margin: 0; padding: 0">
   <head>
      <meta charset="UTF-8" />
      <meta http-equiv="X-UA-Compatible" content="IE=edge" />
      <meta name="viewport" content="width=device-width, initial-scale=1.0" />
      <meta http-equiv="Content-Type" content="text/html charset=UTF-8" />      
      <title>Scope Builder | PDF</title>
      <link rel="icon" href="{{ asset('ui_assets/img/favicon.png') }}" type="image/x-icon" />
      <style type="text/css">    
         p{
         font-family: 'spfpro';   
         font-size: 16px;
         line-height: 24px;
         margin-bottom: 15px; 
         text-align: justify;
         margin-top: 0; 
         color: #212529c4;
         font-weight: normal;
         }   
      .thirdheading{
         font-family: 'spfpro';   
         font-size:14px;
         line-height: 19px;
         margin-bottom: 15px; 
         text-align: justify;
         margin-top: 0; 
         color: #212529;
         font-weight: normal;
      } 
      .subheading{
         font-family: 'spfpro';   
         font-size:22px;
         line-height: 24px;
         margin:30px 0; 
         text-align: justify;  
         color: #212529;
         font-weight: 600;   
         }    
         span,strong,td,span{
         font-size: 14px;
          color: #212529;
         }  
      </style>
   </head>
   <pre>
   @php
   if($data->type=="both" && $data->both_same_functionality=='0'){
      $tp_arr = ['mobile', 'web'];
  } else{
      $tp_arr = [$data->tech_type];
  }

   $count = 0;
   if($data->name)
   {
      $count = $count + 1;
   }
   if($data->description)
   {
      $count = $count + 1;
   }
   if($data->type)
   {
      $count = $count + 1;
   }
   if($data->example_projects && count(unserialize($data->example_projects))) {
      foreach(unserialize($data->example_projects) as $item) {
         $count = $count + 1;
      }
   }
   
   if(!empty($project_details)){
      foreach($project_details as $pd){
         $count = $count + $pd['number_of_user_types'];
         $count = $count + $pd['number_of_pages'];
      }
   }
   
   if(!empty($data->web_frameworks)){
      foreach(unserialize($data->web_frameworks) as $item){
         $count = $count + 1;
      }
   }
   if(!empty($data->mobile_frameworks)){
      foreach(unserialize($data->mobile_frameworks) as $item){
         $count = $count + 1;
      }
   }
   foreach($tp_arr as $ia){
      if(isset($project_details[$ia]) && isset($project_details[$ia]['pages_information'])){
         foreach(unserialize($project_details[$ia]['pages_information']) as $key=>$item){
            foreach($item['users'] as $user){
               if(!empty($user['information'])){
                  foreach($user['information'] as $info){
                     if(!empty($info['text'])){
                        $count = $count + 1;
                     }
                  }
               }

               if(!empty($user['actions'])){
                  foreach($user['actions'] as $action){
                     if(!empty($info['text'])){
                        $count = $count + 1;
                     }
                  }
               }
            }
         }
      }
   }
   @endphp
   <body style="
      font-family: 'spfpro';   
      margin: 0 auto;
      padding: 0;
      background-color: #ffffff;      
      color: #212529;
      direction: ltr;
      text-align: left;
      width: 100%;
      ">
      <div style="
         width: 100%;
         margin-right: auto;
         margin-left: auto;
         background-color: #ffffff;
         ">

               <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" style="padding: 0; margin-bottom: 40px;">
                  <tr>
                     <td><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAN8AAAAlCAYAAADC8DxTAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyJpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMC1jMDYxIDY0LjE0MDk0OSwgMjAxMC8xMi8wNy0xMDo1NzowMSAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENTNS4xIFdpbmRvd3MiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6NkJBRjE2NjU2NkU0MTFFQzk5M0JGRDc4NDBCODdBMDEiIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6NkJBRjE2NjY2NkU0MTFFQzk5M0JGRDc4NDBCODdBMDEiPiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmlpZDo2QkFGMTY2MzY2RTQxMUVDOTkzQkZENzg0MEI4N0EwMSIgc3RSZWY6ZG9jdW1lbnRJRD0ieG1wLmRpZDo2QkFGMTY2NDY2RTQxMUVDOTkzQkZENzg0MEI4N0EwMSIvPiA8L3JkZjpEZXNjcmlwdGlvbj4gPC9yZGY6UkRGPiA8L3g6eG1wbWV0YT4gPD94cGFja2V0IGVuZD0iciI/PlifuiQAABJFSURBVHja7F0JnFXl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" style="margin-bottom: 10px;" />
                     
                     <p style="font-size: 16px; font-weight: 400;">Project Requirements PDF</p>
                  </td>
                  <td align="right" style="text-align:  right;">
               <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADoAAAA6CAYAAADhu0ooAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyJpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMC1jMDYxIDY0LjE0MDk0OSwgMjAxMC8xMi8wNy0xMDo1NzowMSAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENTNS4xIFdpbmRvd3MiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6NkMzRjRDMUE2M0RCMTFFQzgzNDVGMDI0NzQ1RDFGNzAiIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6NkMzRjRDMUI2M0RCMTFFQzgzNDVGMDI0NzQ1RDFGNzAiPiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmlpZDo2QzNGNEMxODYzREIxMUVDODM0NUYwMjQ3NDVEMUY3MCIgc3RSZWY6ZG9jdW1lbnRJRD0ieG1wLmRpZDo2QzNGNEMxOTYzREIxMUVDODM0NUYwMjQ3NDVEMUY3MCIvPiA8L3JkZjpEZXNjcmlwdGlvbj4gPC9yZGY6UkRGPiA8L3g6eG1wbWV0YT4gPD94cGFja2V0IGVuZD0iciI/PiPoNXAAAAYDSURBVHja5FtdaBxV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" alt="Print" />
            </td>
                  </tr>
               </table>            
       
         <div class="project-requirements" style="padding: 0px; border-top: 1px solid #dee2e6; background-color: #fff;">
            <div class="header">
               <h1 style="
                  font-family: 'spfpro';   
                  margin: 25px 0 20px;
                  font-size: 48px;
                  font-weight:regular;
                  width: 100%; text-transform: capitalize;
                  ">
                  {{ $data->name }}
               </h1>
               <p style="
                  font-family: 'spfpro';   
                  font-size: 18px;
                  line-height:28px;
                  margin-bottom: 25px; 
                  text-align: justify;
                  margin-top: 0; 
                  color: #212529c4;
                  font-weight: normal;
                  ">
                  {{ $data->description }}
               </p>
               <div style="display: block">


         

                  <table width="100%" border="0" align="left" cellpadding="0" cellspacing="0" style="padding: 0; margin-bottom: 15px;">
                  
                     <tr>
                        <td>
                            <table width="400px" border="0" align="left" cellpadding="0" cellspacing="0" style="padding: 0; margin-bottom: 10px;">
                               <tr>
                                 <td style="font-weight:bold;font-size: 14px;border: 1px dotted #dee2e6; border-right: 0px; padding: 15px;">Client</td>
                                 <td align="right" style="text-align: right; font-size: 14px; border: 1px dotted #dee2e6; border-left: 0px; padding: 15px;">{{ $data->user->name }}</td>
                              </tr>
                            </table>
                        </td>
                     </tr>

                     <tr>
                        <td>
                            <table width="400px" border="0" align="left" cellpadding="0" cellspacing="0" style="padding: 0; margin-bottom: 10px;">
                               <tr>
                                 <td style="font-weight:bold;font-size: 14px;  border: 1px dotted #dee2e6; border-right: 0px;  padding: 15px;">Date</td>
                                 <td align="right" style="text-align: right; font-size: 14px; border: 1px dotted #dee2e6; border-left: 0px; padding: 15px;">{{ $data->created_at->format('Y-m-d') }}</td>
                              </tr>
                            </table>
                        </td>
                     </tr>

                     {{-- <tr>
                        <td>
                            <table width="400px" border="0" align="left" cellpadding="0" cellspacing="0" style="padding: 0; margin-bottom: 10px;">
                               <tr>
                                 <td style="font-weight:bold;font-size: 14px;  border: 1px dotted #dee2e6; border-right: 0px;  padding: 15px;">Requirements</td>
                                 <td align="right" style="text-align: right; font-size: 14px; border: 1px dotted #dee2e6; border-left: 0px; padding: 15px;">{{ $count }} Project Requirements</td>
                              </tr>
                            </table>
                        </td>
                     </tr> --}}
                     <tr>
                        <td>
                            <table width="400px" border="0" align="left" cellpadding="0" cellspacing="0" style="padding: 0; margin-bottom: 10px;">
                               <tr>
                                 <td style="font-weight:bold;font-size: 14px;  border: 1px dotted #dee2e6; border-right: 0px;  padding: 15px;">Project Type</td>
                                 <td align="right" style="text-align: right; font-size: 14px; border: 1px dotted #dee2e6; border-left: 0px;   padding: 15px;">{{ $data->projectType ? $data->projectType->name : '' }}</td>
                              </tr>
                            </table>
                        </td>
                     </tr>
                     <tr>
                        <td>
                            <table width="400px" border="0" align="left" cellpadding="0" cellspacing="0" style="padding: 0; margin-bottom: 10px;">
                               <tr>
                                 <td style="font-weight:bold;font-size: 14px;  border: 1px dotted #dee2e6; border-right: 0px;  padding: 15px;">Skills</td>
                                 <td align="right" style="text-align: right; font-size: 14px; border: 1px dotted #dee2e6; border-left: 0px;   padding: 15px;">{{ $data->skillsName() ? $data->skillsName() : 'N/A' }}</td>
                              </tr>
                            </table>
                        </td>
                     </tr>
                  @if($data->mockup &&  (file_exists( public_path() . '/upload/mockup/'.$data->mockup)))
                     @php
                        $mockupPath =url('/upload/mockup/'.$data->mockup) ;
                     @endphp
                   <tr>
                        <td>
                            <table width="400px" border="0" align="left" cellpadding="0" cellspacing="0" style="padding: 0; margin-bottom: 10px;">
                               <tr>
                                 <td style="font-weight:bold;font-size: 14px;  border: 1px dotted #dee2e6; border-right: 0px;  padding: 15px;">Mockup</td>
                                 <td align="right" style="text-align: right; font-size: 14px; border: 1px dotted #dee2e6; border-left: 0px;   padding: 15px;"><a href="{{$mockupPath}}" target="_blank">{{ $mockupPath }}</a></td>
                              </tr>
                            </table>
                        </td>
                     </tr>
                  @endif
                  @if($data->mockup_url)                     
                   <tr>
                        <td>
                            <table width="400px" border="0" align="left" cellpadding="0" cellspacing="0" style="padding: 0; margin-bottom: 10px;">
                               <tr>
                                 <td style="font-weight:bold;font-size: 14px;  border: 1px dotted #dee2e6; border-right: 0px;  padding: 15px;">Figma URL</td>
                                 <td align="right" style="text-align: right; font-size: 14px; border: 1px dotted #dee2e6; border-left: 0px;   padding: 15px;"><a href="{{$data->mockup_url}}" target="_blank">{{ $data->mockup_url }}</a></td>
                              </tr>
                            </table>
                        </td>
                     </tr>
                  @endif
                  </table>            
               </div>
            </div>
            {{-- <div class="content" style="padding: 0px 0 0px;">
               <div class="table">
                  <h3 class="subheading" style="font-size:20px; margin: 0px 0 7px;">
                     Example Website Links
                  </h3>
                  <ul style="margin: 0; padding: 0; list-style-type: none;">
                     @if($data->example_projects && count(unserialize($data->example_projects)))
                        @foreach (unserialize($data->example_projects) as $item)
                        <li style="margin-bottom: 4px; padding: 0 10px 0 0; width: auto; float: left;">
                           <a href="{{ $item }}" style="
                              padding-bottom: 4px;
                              display: inline-block;
                              border-bottom: 1px dotted #adb5bd;
                              text-decoration: none;
                              font-size: 16px;
                              color: #339af0;
                              font-weight: 400; 
                              ">{{ $item }}</a>
                        </li>
                        @endforeach
                     @endif
                  </ul>
               </div>
            </div> --}}
            <div class="content" style="padding: 0px 0;">
               {{--  <div class="table">
                  <h3 class="subheading" style="font-size:20px; margin: 20px 0 17px;">User Types and Pages</h3>
                  <table style="
                     width: 100%;
                     border: 1px solid #dee2e6;
                     border-collapse: collapse;
                     font-size: 12px;
                     color: #777777;
                     ">
                     <tr>
                        <td colspan="6" style="
                           background-color: #f8f9fa;
                           border: 1px solid #dee2e6;
                           border-collapse: collapse;
                           padding: 18px 8px 15px;
                           overflow: hidden;
                           text-overflow: ellipsis;
                           white-space: nowrap;">
                           @foreach($tp_arr as $ia)
                           @if(isset($project_details[$ia]) && isset($project_details[$ia]['user_types']))
                           @foreach (unserialize($project_details[$ia]['user_types']) as $item)
                           <span @if ($loop->last)
                           style="
                           display: inline-block;
                           padding-right: 1px;
                           overflow: hidden;
                           text-overflow: ellipsis;
                           white-space: nowrap;
                           "
                           @else
                           style="
                           display: inline-block;
                           padding-right: 1px;
                           overflow: hidden;
                           text-overflow: ellipsis;
                           white-space: nowrap;
                           "
                           @endif
                           >{{ ucfirst($item) }}</span>                            
                           @endforeach
                           @endif
                           @endforeach
                        </td>
                     </tr>
                  </table>
               </div> --}}
               {{-- <div class="table" style="margin-top: 0px">
                  <h3 class="subheading" style="font-size:20px; margin: 20px 0 17px;">
                     Web and Mobile Frameworks
                  </h3>
                  <table style="
                     width: 100%;
                     border: 1px solid #dee2e6;
                     border-collapse: collapse;
                     font-size: 12px;
                     color: #777777;
                     ">
                     <tr>
                        <td style="
                           background-color: #f8f9fa;
                           border: 1px solid #dee2e6;
                           border-collapse: collapse;
                           padding: 16px;
                           vertical-align: baseline;
                           text-align: left;
                           width: 14%;
                           ">
                           <strong>Web : </strong>
                           @if (!empty($data->web_frameworks))
                           @forelse (unserialize($data->web_frameworks) as $item)
                           {{ $item }}
                           @empty
                           <span class="horizontal-list">None.</span>
                           @endforelse
                           @else
                           None
                           @endif
                        </td>
                     </tr>
                     <tr>
                        <td style="
                           background-color: #f8f9fa;
                           border: 1px solid #dee2e6;
                           border-collapse: collapse;
                           padding: 16px;
                           vertical-align: baseline;
                           text-align: left;
                           width: 14%;
                           ">
                           <strong>Mobile : </strong>
                           @if (!empty($data->mobile_frameworks))
                           @forelse (unserialize($data->mobile_frameworks) as $item)
                           {{ ucfirst($item) }}
                           @empty
                           <span class="horizontal-list">None.</span>
                           @endforelse
                           @else None @endif
                        </td>
                     </tr>
                  </table>
               </div> --}}
               <div class="table" style="margin-top: 0px">
                  <h1 class="" style="margin: 20px 0 17px;">
                     <strong style="font-size: 20px;">Questions</strong>
                  </h1>
                  <table style="
                     width: 100%;
                     border-collapse: collapse;
                     font-size: 12px;
                     color: #777777;
                     ">
                     @php
                        $index = 1;
                     @endphp
                     @foreach ($data->projectQuestions as $question)
                        @if ($question->value)
                           <tr>
                              <td style="
                                 border-collapse: collapse;
                                 vertical-align: baseline;
                                 text-align: left;
                                 ">
                                 <strong >{{ $index }}. {{ $question->fields['label'] }} </strong>
                              </td>
                           </tr>
                           
                           @if ($question->fields['type'] == 'checkbox-group')
                              @php
                                 $question_values = [];
                                 $selected_values = json_decode($question->value);
                                 foreach ($question->fields['values'] as $value) {
                                    if(is_array($selected_values) && in_array($value['value'], $selected_values)) {
                                       $question_values[] = $value['label'];
                                    }
                                 }
                              @endphp
                              <tr>
                                 <td style="
                                    border-collapse: collapse;
                                    vertical-align: baseline;
                                    text-align: left;
                                    padding-left: 15px;
                                    padding-bottom: 10px;
                                    ">
                                    @foreach ($question_values as $question_value)
                                       <p>{{ $question_value }}</p>
                                    @endforeach
                                 </td>
                              </tr>
                           @else
                              @php
                                 $question_value = $question->value;
                                 if($question->fields['type'] == 'radio-group' || $question->fields['type'] == 'select') {
                                    foreach ($question->fields['values'] as $value) {
                                       if($value['value'] == $question->value) {
                                          $question_value = $value['label'];
                                       }
                                    }
                                 }
                              @endphp
                              <tr>
                                 <td style="
                                    border-collapse: collapse;
                                    vertical-align: baseline;
                                    text-align: left;
                                    padding-left: 15px;
                                    padding-bottom: 10px;
                                    ">
                                    <p>{{ $question_value }}</p>
                                 </td>
                              </tr>
                           @endif
                           
                           @php $index++; @endphp
                        @endif
                     @endforeach
                  </table>
               </div>
            </div>
         </div>
         {{-- <div style="page-break-after: always;"></div>
         <div class="project-requirements">
            <div class="table" style="padding: 0 15px;">
               @foreach($tp_arr as $ia)
               @if(isset($project_details[$ia]) && isset($project_details[$ia]['pages_information']))
               @foreach (unserialize($project_details[$ia]['pages_information']) as $key => $item)
               <h2
                  style="
                  margin-top: 20px;
                  font-size:30px;
                  font-weight: bold;
                  text-transform: capitalize;
                  color: #212529;
                  margin-bottom: 18px;
                  "
                  >
                  {{ $ia }}
               </h2>
               <h2
                  style="
                  margin-top: 20px;
                  font-size:24px;
                  font-weight: normal;
                  text-transform: capitalize;
                  color: #212529;
                  margin-bottom: 18px;
                  "
                  >
                  {{ $key + 1 }} - {{ $item['page'] }} Page
               </h2>
               @php $index = 1; @endphp
               @foreach ($item['users'] as $user)
               @if($index == 1)
               <table
                  style="
                  width: 100%;
                  border: 1px solid #dee2e6;
                  border-collapse: collapse;
                  font-size: 12px;
                  color: #777777;
                  "
                  >
                  <tr>
                     <td style="
                        background-color: #f8f9fa;
                        border: 1px solid #dee2e6;
                        border-collapse: collapse;
                        padding: 16px;
                        vertical-align: baseline;
                        text-align: left;
                        width: 14%;
                        ">
                        @foreach ($item['users'] as $_user)
                        <span style="padding-right:8px;">{{ ucfirst($_user['user']) }}</span>
                        <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAwAAAANCAIAAAASSxgVAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyJpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMC1jMDYxIDY0LjE0MDk0OSwgMjAxMC8xMi8wNy0xMDo1NzowMSAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENTNS4xIFdpbmRvd3MiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6MzY3NkEyOTI2NEE2MTFFQzk0RjY5RjQ0MUFFOTQzQjMiIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6MzY3NkEyOTM2NEE2MTFFQzk0RjY5RjQ0MUFFOTQzQjMiPiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmlpZDozNjc2QTI5MDY0QTYxMUVDOTRGNjlGNDQxQUU5NDNCMyIgc3RSZWY6ZG9jdW1lbnRJRD0ieG1wLmRpZDozNjc2QTI5MTY0QTYxMUVDOTRGNjlGNDQxQUU5NDNCMyIvPiA8L3JkZjpEZXNjcmlwdGlvbj4gPC9yZGY6UkRGPiA8L3g6eG1wbWV0YT4gPD94cGFja2V0IGVuZD0iciI/PvIbaPwAAAC/SURBVHjaYvzx8xcDIcCER+79j/9HHv8FMljwKKo/9IubldFGlhmnSdvu/L3+5m+GEQtO64AWzTz/K0KLVZ6fCaoIKISmaOrZ31wsjMkGrAiHA4VStv6Aq7j46t/2O38qrNlQfJdtzPrw478Jp6BhMfn0r1BNFn0xhEtA7hLkYOxx4cja/sNEkvnm23+vv/2P12NFtp0RHphzL/xece03yOe27EBvIytChBPQmXfe/+dmY0BTgWISmdECBwABBgCoikmeEt38dwAAAABJRU5ErkJggg==" alt="Print" style="display: inline-block; padding-right:8px; margin:0;" />
                        @endforeach
                     </td>
                  </tr>
               </table>
               @endif
               @php $index ++; @endphp
               <h2
                  style="
                  margin-top: 14px;
                  font-size:22px;
                  font-weight: normal;
                  text-transform: capitalize;
                  color: #212529;
                  margin-bottom: 18px;
                  "
                  >{{ $user['user'] }}</h2>
               <table style="
                  width: 100%;
                  border: 1px solid #dee2e6;
                  text-transform: capitalize;
                  border-collapse: collapse;
                  font-size: 12px; font-weight: 400;
                  color: #777777;
                  ">
                  <tr>
                     <th style="
                        border: 1px solid #dee2e6;background-color: #f8f9fa;
                        border-collapse: collapse;
                        padding: 10px 15px;
                        overflow: hidden;
                        text-overflow: ellipsis;
                        text-align:left; 
                        ">
                        <h3 class="thirdheading">Information Displayed</h3>
                     </th>
                  </tr>
                  @foreach ($user['information'] as $info)
                  <tr>
                     <td
                        style="
                        border: 1px solid #dee2e6;
                        border-collapse: collapse;
                        padding: 10px 15px;
                        overflow: hidden;
                        text-overflow: ellipsis;
                        "
                        >
                        <p style="font-size: 14px; color: #212529;line-height:24px;">{{ $info['text'] }}</p>
                     </td>
                  </tr>
                  @endforeach                 
               </table>
               <table style="
                  width: 100%;
                  border: 1px solid #dee2e6;
                  border-collapse: collapse;
                  font-weight: 400;
                  color: #777777;
                  ">
                  <tr>
                     <th style="
                        border: 1px solid #dee2e6;background-color: #f8f9fa;
                        border-collapse: collapse;
                        padding: 10px 15px;
                        overflow: hidden;
                        text-overflow: ellipsis;
                        text-align:left;
                        ">
                        <h3 class="thirdheading">Performable Actions</h3>
                     </th>
                  </tr>
                  @foreach ($user['actions'] as $action)
                  <tr>
                     <td
                        style="
                        padding: 10px 15px;
                        overflow: hidden; 
                        text-overflow: ellipsis;
                        border: 1px solid #dee2e6;
                        border-collapse: collapse;
                        "
                        >
                        <p style="font-size: 14px; color: #212529; line-height: 24px;">{{ $action['text'] }}</p>
                     </td>
                  </tr>
                  @endforeach
                   @if(!empty($user['permockup'][0]['text']))
                      <tr>
                        <th style="
                           border: 1px solid #dee2e6;background-color: #f8f9fa;
                           border-collapse: collapse;
                           padding: 10px 15px;
                           overflow: hidden;
                           text-overflow: ellipsis;
                           text-align:left; 
                           ">
                           <h3 class="thirdheading">Mockup URL</h3>
                        </th>
                     </tr>
                     <tr>
                        <td
                           style="
                           border: 1px solid #dee2e6;
                           border-collapse: collapse;
                           padding: 10px 15px;
                           overflow: hidden;
                           text-overflow: ellipsis;
                           "
                           >
                           <p style="font-size: 14px; color: #212529;line-height:24px;">{{ $user['permockup'][0]['text'] }}</p>
                        </td>
                     </tr>                   
                  @endif
               </table>
               @endforeach               
               @endforeach
               @endif
               @endforeach
            </div>
         </div> --}}
   </body>
</html>