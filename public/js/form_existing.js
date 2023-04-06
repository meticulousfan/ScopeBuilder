function iterate_delete_button($parent_selector, $type){
    var $el_length = $parent_selector.find(".values-list").length;
    if($type=='remove'){
        if($el_length<'3'){
            $parent_selector.find(".values-list").each(function(){
                $(this).find('.rminfofield').addClass('d-none');
            });
        } else{
            $parent_selector.find(".values-list").each(function(){
                $(this).find('.rminfofield').removeClass('d-none');
            });
        }
    } else if($type=='add'){
         if($el_length<'2'){
            $parent_selector.find(".values-list").each(function(){
                $(this).find('.rminfofield').addClass('d-none');
            });
        } else{
            $parent_selector.find(".values-list").each(function(){
                $(this).find('.rminfofield').removeClass('d-none');
            });
        }
    }
}

function iterate_delete_button2($parent_selector, $type){
    var $el_length = $parent_selector.find(".form-group").length;  
    if($type=='remove'){
        if($el_length<'3'){
            $parent_selector.find(".form-group").each(function(){
                $(this).find('.action-btn').addClass('d-none');
            });
        } else{
            $parent_selector.find(".form-group").each(function(){
                $(this).find('.action-btn').removeClass('d-none');
            });
        }
    } else if($type=='add'){
        if($el_length<'2'){
            $parent_selector.find(".form-group").each(function(){
                $(this).find('.action-btn').addClass('d-none');
            });
        } else{
            $parent_selector.find(".form-group").each(function(){
                $(this).find('.action-btn').removeClass('d-none');
            });
        }
    }
}

function hide_single_fields_delete(){
    $(".values-wrap").each(function(){
        $value_length = $(this).find('.values-list').length;
        $(this).find('.values-list').each(function(){
            if($value_length>1){
                $(this).find('.rminfofield').removeClass('d-none');
            } else{
                $(this).find('.rminfofield').addClass('d-none');
            }
        });
    });
}

function active_element($nav='next'){
    $current_el = $(".footer-progress ul li.active");
    $current_el.removeClass('active');
    if($nav=='next'){
        $current_el.next().addClass('active');
    } else if($nav=='prev'){
        $current_el.prev().addClass('active');
    }

    var $tech_type_val = $("#tech_type").val();
    if($(".footer-progress ul li.active").hasClass('step_6')){
        $('.'+$tech_type_val+'-container [data-formid="step_no_6"] .project_number').val($(".footer-progress ul li.active").attr('data-divide'));
    } else{
        $('.'+$tech_type_val+'-container [data-formid="step_no_6"] .project_number').val('1');
    }
}

function step_text(){
    var $total_length = $(".footer-progress ul li").length;
    if($(".footer-progress ul li.active").index()=='-1'){
        $(".footer-progress ul li:eq(0)").addClass('active');
    }
    var $active_el = $(".footer-progress ul li.active").index();
    /*if($total_length==7){
        var $active_el = $active_el + 1;
    }*/
    var $remaining_el = $total_length - $active_el;

    $("#steptext").text($remaining_el+" of "+$total_length+" Steps Remaining");
}

function form_display(){
    var $current_el = $(".footer-progress ul li.active");
    var $crstep = $current_el.attr('class');
    var $spl1 = $crstep.split("step_");
    var $spl2 = $spl1[1].split(" ");
    var $current_step = parseInt($spl2[0]);
    if($current_step>=3 && $current_step<=6){ 
        var $current_project_type = $current_el.attr('data-project-type');
        $(".projectforms").hide();
        $("."+$current_project_type+"-container [data-formid='step_no_"+$current_step+"']").show();
    } else{
        $(".projectforms").hide();
        $("[data-formid='step_no_"+$current_step+"']").show();
    }
}

function card_display(){
    var $tech_type_val = $("#tech_type").val();
    $('.'+$tech_type_val+'-container [data-formid="step_no_6"] #pageinfo .card-project').hide();
    var $current_step_eq = parseInt($(".footer-progress ul li.step_6.active").attr('data-divide')) - 1;
    $('.'+$tech_type_val+'-container [data-formid="step_no_6"] #pageinfo .card-project:eq('+$current_step_eq+')').show();
}

function step_btn_display(){
    //$('.step-btn').hide();
    var $tech_type_val = $("#tech_type").val();
    var $active_selector = $(".footer-progress ul li.active");
    var $highest_step_number = parseInt($(".footer-progress ul li.step_6[data-project-type='"+$tech_type_val+"']:last").attr('data-divide'));
    if($active_selector.hasClass('step_6') && parseInt($active_selector.attr('data-divide'))<$highest_step_number){
        $('#submitbtn_projects').show();
        $('#submitbtn').hide();
    } else{
        $('#submitbtn_projects').hide();
        $('#submitbtn').show();
    }

    if($active_selector.hasClass('step_6') && parseInt($active_selector.attr('data-divide'))>1){
        $('#backbutton_projects').show();
        $('#backbutton').hide();
    } else{
        $('#backbutton_projects').hide();
        $('#backbutton').show();
    }
}

function resize_textarea_height($el){
    $($el).height('auto');
    $crheight = Math.min($($el).prop('scrollHeight'), 300);
    $crheight = Math.max($crheight, 20);
    $($el).height($crheight+"px");
}

function resize_all_textareas(){
    $('.expanding-textarea').each(function(){
        $el = this;
        resize_textarea_height($el);
    });
}

function toggle_both_functionality(){
    var $tech_type_val = $("#tech_type").val();
    var $type_val = $('.'+$tech_type_val+'-container [name="type"]:checked').val();
    var $smf_val = $('.'+$tech_type_val+'-container [name="both_same_functionality"]:checked').val();

    if($type_val=='both' && $smf_val=="1"){
        $("#tech_type").val('both');
        $("#clientproject").addClass('both-project').removeClass('mobile-project').removeClass('web-project');
        $("#top").addClass('both-project').removeClass('mobile-project').removeClass('web-project');
    } else if($type_val=='web'){
        $("#tech_type").val('web');
        $("#clientproject").addClass('web-project').removeClass('mobile-project').removeClass('both-project');
        $("#top").addClass('web-project').removeClass('mobile-project').removeClass('both-project');
    } else{
        if($type_val=='both' && $smf_val=="0" && $tech_type_val=="web"){
            $("#clientproject").addClass('web-project').removeClass('mobile-project').removeClass('both-project');
            $("#top").addClass('web-project').removeClass('mobile-project').removeClass('both-project');
        } else{
            $("#tech_type").val('mobile');
            $("#clientproject").addClass('mobile-project').removeClass('web-project').removeClass('both-project');
            $("#top").addClass('mobile-project').removeClass('web-project').removeClass('both-project');
        }
    }

    if($type_val=='both'){
        $('.same-functionality-wrap').removeClass('d-none').addClass('d-block');
    } else{
        $('.same-functionality-wrap').addClass('d-none').removeClass('d-block');
        $('.same-functionality-wrap [name="both_same_functionality"][value="1"]').prop('checked', true);
    }
}

function inArray(needle, haystack) {
    var length = haystack.length;
    for(var i = 0; i < length; i++) {
        if(haystack[i] == needle) return true;
    }
    return false;
}

function save_for_later_btn(){
    var step = parseInt($("#backbutton").attr('data-step'));
    if(step>='2'){
        $("#savelater").removeClass('d-none');
    } else{
        $("#savelater").addClass('d-none');
    }
}

function data_changed($parent_selector, $type=1){
    if($parent_selector.find('.duplicate').length>0){
        var $duplicate_checked = $parent_selector.find('.duplicate').prop('checked');
        if($duplicate_checked && $type==1){
            $parent_selector.find('.duplicate-data-changed').val('1');
        } else{
            $parent_selector.find('.duplicate-data-changed').val('0');
        }
    }
}

$(function(){
    step_text();
    //form_display();
    card_display();
    step_btn_display();

    hide_single_fields_delete();
    resize_all_textareas();
    save_for_later_btn();

    toggle_both_functionality();

    $('body').on('input', '.expanding-textarea', function(){
        $el = this;
        data_changed($(this).closest('.card-project'), 1);
        resize_textarea_height($el);
    });

    $('body').on('change', '[name="type"]', function(){
        var $this_val = $(this).val();
        $("#clientproject .project-type-container").each(function(){
            $(this).find('[data-formid="step_no_2"] [name="type"][value="'+$this_val+'"]').prop('checked', true);
        });
        toggle_both_functionality();
    });

    $('body').on('change', '[name="both_same_functionality"]', function(){
        var $this_val = $(this).val();
        $("#clientproject .project-type-container").each(function(){
            $(this).find('[data-formid="step_no_2"] [name="both_same_functionality"][value="'+$this_val+'"]').prop('checked', true);
        });
        toggle_both_functionality();
    });
    
    /* Duplicate information */
    $('body').on('click','.duplicate',function(){
        var $tech_type_val = $("#tech_type").val();
        data_changed($(this).closest('.card-project'), 0);
        if($(this).is(':checked'))
        {
            var $copyfrom_cardid = parseInt($(this).closest('.card-project').attr('data-number'));
            var $copyto_cardid = parseInt($(this).closest('.card-project').attr('data-number')) + 1;

            $("."+$tech_type_val+"-container [data-formid='step_no_6'] .card-project[data-number='"+$copyto_cardid+"'] .pinfowrap .values-wrap").html('');
            $number_count = 1;
            $("."+$tech_type_val+"-container [data-formid='step_no_6'] .card-project[data-number='"+$copyfrom_cardid+"'] .pinfowrap .values-wrap .values-list").each(function(index, el){
                $("."+$tech_type_val+"-container [data-formid='step_no_6'] .card-project[data-number='"+$copyto_cardid+"'] .pinfowrap .values-wrap").append($(this).prop('outerHTML'));

                $copyfrom_user = $(this).closest('.card').find('.card-header .page-user').text();
                $("."+$tech_type_val+"-container [data-formid='step_no_6'] .card-project[data-number='"+$copyto_cardid+"'] .pinfowrap .values-wrap textarea").each(function(index2, el2){
                    $prev_name = $(this).attr('name');
                    $copyto_user = $(this).closest('.card').find('.card-header .page-user').text();

                    $new_name = $prev_name.replace("["+$copyfrom_user+"]", "["+$copyto_user+"]");
                    $(this).attr('name', $new_name);

                    var $copy_from_val = $("."+$tech_type_val+"-container [data-formid='step_no_6'] .card-project[data-number='"+$copyfrom_cardid+"'] .pinfowrap .values-wrap .values-list:eq("+index2+") textarea").val();
                    $(this).val($copy_from_val).addClass("duplicated");
                });
            });

            $("."+$tech_type_val+"-container [data-formid='step_no_6'] .card-project[data-number='"+$copyto_cardid+"'] .actwrap .values-wrap").html('');
            $("."+$tech_type_val+"-container [data-formid='step_no_6'] .card-project[data-number='"+$copyfrom_cardid+"'] .actwrap .values-wrap .values-list").each(function(index, el){
                $("."+$tech_type_val+"-container [data-formid='step_no_6'] .card-project[data-number='"+$copyto_cardid+"'] .actwrap .values-wrap").append($(this).prop('outerHTML'));

                $copyfrom_user = $(this).closest('.card').find('.card-header .page-user').text();
                $("."+$tech_type_val+"-container [data-formid='step_no_6'] .card-project[data-number='"+$copyto_cardid+"'] .actwrap .values-wrap textarea").each(function(index2, el2){
                    $prev_name = $(this).attr('name');
                    $copyto_user = $(this).closest('.card').find('.card-header .page-user').text();

                    $new_name = $prev_name.replace("["+$copyfrom_user+"]", "["+$copyto_user+"]");
                    $(this).attr('name', $new_name);

                    var $copy_from_val = $("."+$tech_type_val+"-container [data-formid='step_no_6'] .card-project[data-number='"+$copyfrom_cardid+"'] .actwrap .values-wrap .values-list:eq("+index2+") textarea").val();
                    $(this).val($copy_from_val).addClass("duplicated");
                });
            });

            /*let id = $(this).attr('data-copy');

            $(".pinfowrap.copyto[data-copy='"+id+"'] .values-component .values-wrap").html('');
            $(".pinfowrap.copyfrom[data-copy='"+id+"'] .values-wrap .values-list").each(function(index, el){
                $(".pinfowrap.copyto[data-copy='"+id+"'] .values-component .values-wrap").append($(this).prop('outerHTML'));

                $copyfrom_user = $(this).closest('.card').find('.card-header .page-user').text();
                $(".pinfowrap.copyto[data-copy='"+id+"'] .values-component .values-wrap input[type='text']").each(function(index2, el2){
                    $prev_name = $(this).attr('name');
                    $copyto_user = $(this).closest('.card').find('.card-header .page-user').text();

                    $new_name = $prev_name.replace("["+$copyfrom_user+"]", "["+$copyto_user+"]");
                    $(this).attr('name', $new_name);
                });

                let child = (index+1);
                inputval = $(this).find('input[type="text"]').val();
                $(".pinfowrap.copyto[data-copy='" + id +"']").each(function(index2,element){
                    $(element).find("input:eq("+index+")").val(inputval).addClass("duplicated");
                });
            });
            
            $(".actwrap.copyto[data-copy='"+id+"'] .values-component .values-wrap").html('');
            $(".actwrap.copyfrom[data-copy='"+id+"'] .values-wrap .values-list").each(function(index, el){
                $(".actwrap.copyto[data-copy='"+id+"'] .values-component .values-wrap").append($(this).prop('outerHTML'));

                $copyfrom_user = $(this).closest('.card').find('.card-header .page-user').text();
                $(".actwrap.copyto[data-copy='"+id+"'] .values-component .values-wrap input[type='text']").each(function(index2, el2){
                    $prev_name = $(this).attr('name');
                    $copyto_user = $(this).closest('.card').find('.card-header .page-user').text();

                    $new_name = $prev_name.replace("["+$copyfrom_user+"]", "["+$copyto_user+"]");
                    $(this).attr('name', $new_name);
                });

                let child = (index+1);
                inputval = $(this).find('input[type="text"]').val();
                $(".actwrap.copyto[data-copy='" + id +"']").each(function(index2,element){
                    $(element).find("input:eq("+index+")").val(inputval).addClass("duplicated");
                });
            });*/
        }else{
            var $copyfrom_cardid = parseInt($(this).closest('.card-project').attr('data-number'));
            var $copyto_cardid = parseInt($(this).closest('.card-project').attr('data-number')) + 1;

            $("."+$tech_type_val+"-container [data-formid='step_no_6'] .card-project[data-number='"+$copyto_cardid+"'] .pinfowrap .values-wrap").html('');
            $("."+$tech_type_val+"-container [data-formid='step_no_6'] .card-project[data-number='"+$copyfrom_cardid+"'] .pinfowrap .values-wrap .values-list:eq(0)").each(function(index, el){
                $("."+$tech_type_val+"-container [data-formid='step_no_6'] .card-project[data-number='"+$copyto_cardid+"'] .pinfowrap .values-wrap").append($(this).prop('outerHTML'));

                $copyfrom_user = $(this).closest('.card').find('.card-header .page-user').text();
                $("."+$tech_type_val+"-container [data-formid='step_no_6'] .card-project[data-number='"+$copyto_cardid+"'] .pinfowrap .values-wrap textarea").each(function(index2, el2){
                    $prev_name = $(this).attr('name');
                    $copyto_user = $(this).closest('.card').find('.card-header .page-user').text();

                    $new_name = $prev_name.replace("["+$copyfrom_user+"]", "["+$copyto_user+"]");
                    $(this).attr('name', $new_name).val('').removeClass("duplicated");
                });
            });

            $("."+$tech_type_val+"-container [data-formid='step_no_6'] .card-project[data-number='"+$copyto_cardid+"'] .actwrap .values-wrap").html('');
            $("."+$tech_type_val+"-container [data-formid='step_no_6'] .card-project[data-number='"+$copyfrom_cardid+"'] .actwrap .values-wrap .values-list:eq(0)").each(function(index, el){
                $("."+$tech_type_val+"-container [data-formid='step_no_6'] .card-project[data-number='"+$copyto_cardid+"'] .actwrap .values-wrap").append($(this).prop('outerHTML'));

                $copyfrom_user = $(this).closest('.card').find('.card-header .page-user').text();
                $("."+$tech_type_val+"-container [data-formid='step_no_6'] .card-project[data-number='"+$copyto_cardid+"'] .actwrap .values-wrap textarea").each(function(index2, el2){
                    $prev_name = $(this).attr('name');
                    $copyto_user = $(this).closest('.card').find('.card-header .page-user').text();

                    $new_name = $prev_name.replace("["+$copyfrom_user+"]", "["+$copyto_user+"]");
                    $(this).attr('name', $new_name).val('').removeClass("duplicated");
                });
            });

            /*$('.duplicated').closest('.values-list').remove();

            let id = $(this).attr('data-copy');
            //$(".pinfowrap.copyto[data-copy='" + id +"'] .values-component .values-wrap").html($(".pinfowrap.copyfrom[data-copy='"+id+"'] .values-component .values-wrap .values-list:eq(0)").prop('outerHTML'));
            //$(".actwrap.copyto[data-copy='" + id +"'] .values-component .values-wrap").html($(".actwrap.copyfrom[data-copy='"+id+"'] .values-component .values-wrap .values-list:eq(0)").prop('outerHTML'));

            $(".pinfowrap.copyto[data-copy='"+id+"'] .values-component .values-wrap").html('');
            $(".pinfowrap.copyfrom[data-copy='"+id+"'] .values-wrap .values-list:eq(0)").each(function(index, el){
                $(".pinfowrap.copyto[data-copy='"+id+"'] .values-component .values-wrap").append($(this).prop('outerHTML'));

                $copyfrom_user = $(this).closest('.card').find('.card-header .page-user').text();
                $(".pinfowrap.copyto[data-copy='"+id+"'] .values-component .values-wrap input[type='text']").each(function(index2, el2){
                    $prev_name = $(this).attr('name');
                    $copyto_user = $(this).closest('.card').find('.card-header .page-user').text();

                    $new_name = $prev_name.replace("["+$copyfrom_user+"]", "["+$copyto_user+"]");
                    $(this).attr('name', $new_name);
                });
            });
            
            $(".actwrap.copyto[data-copy='"+id+"'] .values-component .values-wrap").html('');
            $(".actwrap.copyfrom[data-copy='"+id+"'] .values-wrap .values-list:eq(0)").each(function(index, el){
                $(".actwrap.copyto[data-copy='"+id+"'] .values-component .values-wrap").append($(this).prop('outerHTML'));

                $copyfrom_user = $(this).closest('.card').find('.card-header .page-user').text();
                $(".actwrap.copyto[data-copy='"+id+"'] .values-component .values-wrap input[type='text']").each(function(index2, el2){
                    $prev_name = $(this).attr('name');
                    $copyto_user = $(this).closest('.card').find('.card-header .page-user').text();

                    $new_name = $prev_name.replace("["+$copyfrom_user+"]", "["+$copyto_user+"]");
                    $(this).attr('name', $new_name);
                });
            });*/
        }
    });

    /*$('body').on('click','.duplicate',function(){
        if($(this).is(':checked'))
        {
            let id = $(this).attr('data-copy');

            $(".pinfowrap.copyto[data-copy='"+id+"'] .values-component .values-wrap").html('');
            $(".pinfowrap.copyfrom[data-copy='"+id+"'] .values-wrap .values-list").each(function(index, el){
                $(".pinfowrap.copyto[data-copy='"+id+"'] .values-component .values-wrap").append($(this).prop('outerHTML'));

                $copyfrom_user = $(this).closest('.card').find('.card-header .page-user').text();
                $(".pinfowrap.copyto[data-copy='"+id+"'] .values-component .values-wrap input[type='text']").each(function(index2, el2){
                    $prev_name = $(this).attr('name');
                    $copyto_user = $(this).closest('.card').find('.card-header .page-user').text();

                    $new_name = $prev_name.replace("["+$copyfrom_user+"]", "["+$copyto_user+"]");
                    $(this).attr('name', $new_name);
                });

                let child = (index+1);
                inputval = $(this).find('input[type="text"]').val();
                $(".pinfowrap.copyto[data-copy='" + id +"']").each(function(index2,element){
                    $(element).find("input:eq("+index+")").val(inputval).addClass("duplicated");
                });
            });
            
            $(".actwrap.copyto[data-copy='"+id+"'] .values-component .values-wrap").html('');
            $(".actwrap.copyfrom[data-copy='"+id+"'] .values-wrap .values-list").each(function(index, el){
                $(".actwrap.copyto[data-copy='"+id+"'] .values-component .values-wrap").append($(this).prop('outerHTML'));

                $copyfrom_user = $(this).closest('.card').find('.card-header .page-user').text();
                $(".actwrap.copyto[data-copy='"+id+"'] .values-component .values-wrap input[type='text']").each(function(index2, el2){
                    $prev_name = $(this).attr('name');
                    $copyto_user = $(this).closest('.card').find('.card-header .page-user').text();

                    $new_name = $prev_name.replace("["+$copyfrom_user+"]", "["+$copyto_user+"]");
                    $(this).attr('name', $new_name);
                });

                let child = (index+1);
                inputval = $(this).find('input[type="text"]').val();
                $(".actwrap.copyto[data-copy='" + id +"']").each(function(index2,element){
                    $(element).find("input:eq("+index+")").val(inputval).addClass("duplicated");
                });
            });
        }else{
            $('.duplicated').closest('.values-list').remove();

            let id = $(this).attr('data-copy');
            //$(".pinfowrap.copyto[data-copy='" + id +"'] .values-component .values-wrap").html($(".pinfowrap.copyfrom[data-copy='"+id+"'] .values-component .values-wrap .values-list:eq(0)").prop('outerHTML'));
            //$(".actwrap.copyto[data-copy='" + id +"'] .values-component .values-wrap").html($(".actwrap.copyfrom[data-copy='"+id+"'] .values-component .values-wrap .values-list:eq(0)").prop('outerHTML'));

            $(".pinfowrap.copyto[data-copy='"+id+"'] .values-component .values-wrap").html('');
            $(".pinfowrap.copyfrom[data-copy='"+id+"'] .values-wrap .values-list:eq(0)").each(function(index, el){
                $(".pinfowrap.copyto[data-copy='"+id+"'] .values-component .values-wrap").append($(this).prop('outerHTML'));

                $copyfrom_user = $(this).closest('.card').find('.card-header .page-user').text();
                $(".pinfowrap.copyto[data-copy='"+id+"'] .values-component .values-wrap input[type='text']").each(function(index2, el2){
                    $prev_name = $(this).attr('name');
                    $copyto_user = $(this).closest('.card').find('.card-header .page-user').text();

                    $new_name = $prev_name.replace("["+$copyfrom_user+"]", "["+$copyto_user+"]");
                    $(this).attr('name', $new_name);
                });
            });
            
            $(".actwrap.copyto[data-copy='"+id+"'] .values-component .values-wrap").html('');
            $(".actwrap.copyfrom[data-copy='"+id+"'] .values-wrap .values-list:eq(0)").each(function(index, el){
                $(".actwrap.copyto[data-copy='"+id+"'] .values-component .values-wrap").append($(this).prop('outerHTML'));

                $copyfrom_user = $(this).closest('.card').find('.card-header .page-user').text();
                $(".actwrap.copyto[data-copy='"+id+"'] .values-component .values-wrap input[type='text']").each(function(index2, el2){
                    $prev_name = $(this).attr('name');
                    $copyto_user = $(this).closest('.card').find('.card-header .page-user').text();

                    $new_name = $prev_name.replace("["+$copyfrom_user+"]", "["+$copyto_user+"]");
                    $(this).attr('name', $new_name);
                });
            });
        }
    });*/

    /* Do you have any example projects you would like to share */
    $('#addmoreexample').click(function(){
      let el = parseInt($("#sh_card .form-group").length);
      let newel = (el+1);
      //let clone = $("#shmain_1").clone();
      let clone = $("#sh_card>.form-group:eq(0)").clone();
      clone.attr('id','shmain_'+newel);
      clone.find(".action-btn").removeClass('d-none').attr('data-id','shmain_'+newel);
      clone.find("input").val('');
      clone.find("#example_projectsInput").attr('id','example_projects_'+newel+'_Input');
      clone.find("#example_projectsError").attr('id','example_projects_'+newel+'_Error');      
      $("#example_projectsError").before(clone);

        //var $parent_selector = $(this).closest('.multi-value-list');
        var $parent_selector = $("#sh_card");
        iterate_delete_button2($parent_selector, 'add');
    });
    $('body').on('click','#sh_card .action-btn',function(){
        //var $parent_selector = $(this).closest('.multi-value-list');
        var $parent_selector = $("#sh_card");
        iterate_delete_button2($parent_selector, 'remove');

        $('#'+$(this).attr('data-id')).remove();
    });  
    /* Do you have mock up designs for the project */
    $('input[name="mockup"]').change(function(){
        if($(this).val()==1){
            $('#mockupfile').removeClass('d-none');
        }else{
            $('#mockupfile').addClass('d-none');
        }
    });
  /* Back button Click */
  $('#backbutton').click(function(){
    var step = parseInt($(this).attr('data-step'));
    if(step!=0){
        let backStep = step-1;
        if(backStep==0){
            window.location = $(this).attr('data-url');
        }

        var $tgt_selector = $(".mobile-container [data-formid='step_no_2']");
        var $tgt_selector2 = $(".footer-progress li.active");
        if($tgt_selector.find("[name='type']:checked").attr('value')=="both" && $tgt_selector.find("[name='both_same_functionality']:checked").attr('value')=="0" && $tgt_selector2.hasClass('step_3') && $tgt_selector2.attr('data-project-type')=="web"){
            $("#tech_type").val('mobile');
            $("#clientproject").addClass('mobile-project').removeClass('web-project').removeClass('both-project');
            $("#top").addClass('mobile-project').removeClass('web-project').removeClass('both-project');

            $('.projectforms').hide();
            $('.mobile-container [data-formid="step_no_6"]').show();
            $('#backbutton').attr('data-step', '6');
            $('#submitbtn').attr('data-step', '6');

            active_element('prev');
            step_text();
            setTimeout(function(){ 
                card_display();
                resize_all_textareas();
            }, 300);

            step_btn_display();
        } else{
            $('.projectforms').hide();   
            //$('#step_no_'+backStep).show();
            var $tech_type_val = $("#tech_type").val();
            $('.'+$tech_type_val+'-container [data-formid="step_no_'+backStep+'"]').show();
            let backText = "Back";
            if(backStep==1){
                backText = "Cancel";
                $('#savelater').addClass('d-none');
            }
            $('#backbutton').text(backText).attr('data-step',backStep);
            $('#submitbtn').attr('data-step',backStep);

            //$('.footer-progress ul li').removeClass('active');
            //$('#pc_'+backStep).addClass('active');
            active_element('prev');
            
            //$('#steptext').text((8-backStep)+' of 7 Steps Remaining'); 
            step_text();
            setTimeout(function(){ 
                card_display();
                resize_all_textareas();
            }, 300);
            
            step_btn_display();
            if(backStep==5){
                $("#clientproject").removeClass('project-wrap');
            }

            if(step==3){
                $("#clientproject .project-type-container").each(function(){
                    $(this).find('[data-formid="step_no_2"]').show();
                });
            }

            save_for_later_btn();
        }
    }else{
        window.location = $(this).attr('data-url');
    }   
  });
    /* next button click*/
    $('#submitbtn').click(function(){
        var formID = parseInt($(this).attr('data-step'));

        var $tech_type_val = $("#tech_type").val();
        $('#backbutton').attr('data-step',formID-1);

        $("."+$tech_type_val+'-container [data-formid="step_no_'+formID+'"]').submit();

        /*if(inArray(formID, [3, 4, 5, 6])){
            var $tech_type_val = $("#tech_type").val();
            $("."+$tech_type_val+'-container #step_no_'+formID).submit();
        } else{
            $('#step_no_'+formID).submit();
        }*/

        if(formID=="2"){
            var $type_val = $('[name="type"]:checked').val();
            var $smf_val = $('[name="both_same_functionality"]:checked').val();

            $(".footer-progress li[data-project-type='mobile']").remove();
            $(".footer-progress li[data-project-type='web']").remove();
            $(".footer-progress li[data-project-type='both']").remove();
            
            if($type_val=="both" && $smf_val=="0"){
                $append_str = '';
                $tp_arr = ['mobile', 'web'];
                $tp_arr.forEach(myFunction);
                function myFunction($type_val, index, array) {
                  $append_str = $append_str + '<li class="step_3" data-project-type="'+$type_val+'"><span class="percentage">42%</span> <span class="dots"></span></li>';
                    $append_str = $append_str + '<li class="step_4" data-project-type="'+$type_val+'"><span class="percentage">56%</span> <span class="dots"></span></li>';
                    $append_str = $append_str + '<li class="step_5" data-project-type="'+$type_val+'"><span class="percentage">70%</span> <span class="dots"></span></li>';
                    $append_str = $append_str + '<li class="step_6" data-project-type="'+$type_val+'" data-divide="1"><span class="percentage">84%</span> <span class="dots"></span></li>';
                }
                
                $(".footer-progress li.step_2").after($append_str);
            } else{
                $append_str = '';
                $append_str = $append_str + '<li class="step_3" data-project-type="'+$type_val+'"><span class="percentage">42%</span> <span class="dots"></span></li>';
                $append_str = $append_str + '<li class="step_4" data-project-type="'+$type_val+'"><span class="percentage">56%</span> <span class="dots"></span></li>';
                $append_str = $append_str + '<li class="step_5" data-project-type="'+$type_val+'"><span class="percentage">70%</span> <span class="dots"></span></li>';
                $append_str = $append_str + '<li class="step_6" data-project-type="'+$type_val+'" data-divide="1"><span class="percentage">84%</span> <span class="dots"></span></li>';
                $(".footer-progress li.step_2").after($append_str);
            }
        }

        if(formID=="5"){
            $("#clientproject").addClass('project-wrap');

            var $divide_steps = $("#clientproject ."+$tech_type_val+"-container [class*='checkme_']:checked").length;
            
            $('li.step_6[data-project-type="'+$tech_type_val+'"]').remove();
            var $ap_str = '';
            for($i=1; $i<=$divide_steps; $i++){
                $ap_str = $ap_str + '<li class="step_6" data-project-type="'+$tech_type_val+'" data-divide="'+$i+'"><span class="percentage">84%</span> <span class="dots"></span></li>';
            }
            $('li.step_5[data-project-type="'+$tech_type_val+'"]').after($ap_str);

            //$('.footer-progress li').removeClass('active');
            //$('.step_6[data-divide="1"]').addClass('active');
            active_element('next');
            card_display();

            step_text();
            step_btn_display();
        } else{
            $("#clientproject").removeClass('project-wrap');
        }

        setTimeout(function(){ 
            hide_single_fields_delete();
            resize_all_textareas();
        }, 2000);
    });

    $('#submitbtn_projects').click(function(){
        var $active_tab = $(".footer-progress ul li.active").attr('data-divide');
        var $tech_type_val = $("#tech_type").val();
        var $parent_selector = $('.'+$tech_type_val+'-container [data-formid="step_no_6"] .card-project[data-number="'+$active_tab+'"]');

        $has_errors = false;
        $parent_selector.find(".invalid-feedback").text('');

        if($parent_selector.find('.duplicate-data-changed').val()=='1'){
            //alert("New text has been added. Please uncheck and check Duplicate Information.");
            //return false;
            $parent_selector.find('.duplicate').trigger('click');
            $parent_selector.find('.duplicate').trigger('click');
        }
        $parent_selector.find('.duplicate').prop('checked', false);

        var $info_val = $parent_selector.find(".values-wrap .informationfield:eq(0) textarea").val();
        var $action_val = $parent_selector.find(".values-wrap .perfomableinput:eq(0) textarea").val();
        //var $mockup_val = $parent_selector.find(".mockupinput input[type='text']").val();

        if($info_val==''){
           $parent_selector.find(".values-wrap .informationfield:eq(0)").closest('.values-wrap').next(".invalid-feedback").text('This field is required');
           $has_errors = true;
        }

        if($action_val==''){
           $parent_selector.find(".values-wrap .perfomableinput:eq(0)").closest('.values-wrap').next(".invalid-feedback").text('This field is required');
           $has_errors = true;
        }

        /*if($mockup_val==''){
           $parent_selector.find(".mockupinput").next(".invalid-feedback").text('This field is required');
           $has_errors = true;
        }*/

        if(!$has_errors){
            //$parent_selector.find("input[type='checkbox'].duplicate").trigger('click');
            //$parent_selector.find("input[type='checkbox'].duplicate").trigger('click');

            $parent_selector.find(".invalid-feedback").text('');

            active_element('next');
            card_display();

            step_text();
            step_btn_display();

            setTimeout(function(){ 
                hide_single_fields_delete();
                resize_all_textareas();
            }, 2000);
        }

        $('#savelater').removeClass('d-none');
    });

    $('#backbutton_projects').click(function(){
        active_element('prev');
        card_display();

        step_text();
        step_btn_display();
        resize_all_textareas();
    });

    
    
    /* User type select */
    $('[name="number_of_user_types"]').change(function(){  
        var $target_selector = $(this).closest('form'); 
        let el = parseInt($target_selector.find(".user_type_field").length);
        let looprange  = parseInt($(this).val());    
        if(looprange>el){        
            for(let i = el; i <= looprange-1; i++){
                let clone = $target_selector.find("#user_type_field_1").clone();
                clone.find("input").val('');
                clone.attr('id','user_type_field_'+(i+1));           
                clone.find("label").text('User Type '+(i+1));           
                $target_selector.find("#user_typesError").before(clone);
            }
        }else{       
            for(let i = el; i > looprange; i--) {
                $target_selector.find("#user_type_field_"+i).remove();
            } 
        }
    });

  /* pages select */
  $('[name="number_of_pages"]').change(function(){
    var $target_selector = $(this).closest('form'); 
    let el = parseInt($target_selector.find(".page_no_field").length); 
    let looprange  = parseInt($(this).val());    
    if(looprange>el){        
       for(let i = el; i <= looprange-1; i++) {
            let clone = $target_selector.find("#page_no_field_1").clone();
            clone.find("input").val('');
            clone.attr('id','page_no_field_'+(i+1));           
            clone.find("label").text('Page '+(i+1)+' Name');           
            $target_selector.find("#pagesError").before(clone);            
        }
    }else{       
       for(let i = el; i > looprange; i--){
           $target_selector.find("#page_no_field_"+i).remove();
        } 
    }
 
  }); 
  /* Check All*/
    $('body').on('click', '.checkall', function(){
        let myid = $(this).attr('id');
      if(this.checked){
        $('.checkme_'+myid).prop('checked', true);
       }else{
        $('.checkme_'+myid).prop('checked', false);
       }
    });
  /* Perfomable add more*/
  $('body').on('click','.perfomableadd',function(){
    /*let myid = $(this).attr('data-id');
    let el = parseInt($(".perfomableinput").length); 
    let clone = $("#"+myid).clone();
    clone.find("input").val('');
    clone.attr('id',el+1); 
    clone.addClass('mt-3'); 
    clone.find(".action-btn").removeClass('d-none').attr('data-id',el+1);
    $(".invalid-feedback."+myid).before(clone);*/
    $(this).closest('.values-component').find('.values-wrap').append(
        $(this).closest('.values-component').find('.values-wrap .values-list:eq(0)').prop('outerHTML')
    );

    $(this).closest('.values-component').find('.values-wrap .values-list:last textarea').val('');

    var $parent_selector = $(this).closest('.values-component');
    iterate_delete_button($parent_selector, 'add');
    data_changed($(this).closest('.card-project'), 1);
  });
   /* Information add more*/

  $('body').on('click','.infoadd',function(){
    /*let myid = $(this).attr('data-id');
    let el = parseInt($(".informationfield").length); 
    let clone = $("#"+myid).clone();
    clone.find("input").val('');
    clone.attr('id',el+1); 
    clone.addClass('mt-3'); 
    clone.find(".action-btn").removeClass('d-none').attr('data-id',el+1);
    $(".invalid-feedback."+myid).before(clone);*/

    $(this).closest('.values-component').find('.values-wrap').append(
        $(this).closest('.values-component').find('.values-wrap .values-list:eq(0)').prop('outerHTML')
    );
    $(this).closest('.values-component').find('.values-wrap .values-list:last textarea').val('');

    var $parent_selector = $(this).closest('.values-component');
    iterate_delete_button($parent_selector, 'add');

    data_changed($(this).closest('.card-project'), 1);
  });
  $('body').on('click','.rminfofield',function(evt){
    var $parent_selector = $(this).closest('.values-component');
    data_changed($(this).closest('.card-project'), 1);
    iterate_delete_button($parent_selector, 'remove');

    //$('#'+$(this).attr('data-id')).remove();
    $(this).closest('.values-list').remove();
  });
  /* save Later*/
  $('#savelater').click(function(){
    var step = parseInt($('#submitbtn').attr('data-step'));
    var $tech_type_val = $("#tech_type").val();
    $('[data-formid="step_no_'+step+'"] input[name="is_draft"]').attr('value', '1');
    $('.'+$tech_type_val+'-container [data-formid="step_no_'+step+'"]').submit();
  });
  /* Form submit action*/
  $('.projectforms').submit(function(e){
        e.preventDefault();
        //$('#loader').show();
        $('.loader-con').addClass('d-flex').removeClass('d-none');
        $('.loader-con').closest('.card').addClass('loading-active');
        var $tech_type_val = $("#tech_type").val();
        var form = $('.'+$tech_type_val+'-container [data-formid="'+e.target.getAttribute("data-formid")+'"]');
        var formData = new FormData(form[0]);
        formData.append('tech_type', $tech_type_val);
        formData.append('project_type', 'existing');
        $(".invalid-feedback").text("");
        $('.'+$tech_type_val+'-container [data-formid="'+e.target.getAttribute("data-formid")+'"] input').removeClass("is-invalid");
        $.ajax({
            method: "POST",
            processData: false,
            contentType: false,
            url:form.attr('action'),
            data: formData,
            success: (response) =>{
                //$('#loader').hide();
                $('.loader-con').removeClass('d-flex').addClass('d-none');
                $('.loader-con').closest('.card').removeClass('loading-active');
              if(response.status==200){
                if(response.url){
                    if(response.step!='draft'){
                        $('#questionnaire-completed-modal').addClass('visible').show();
                    }else{
                       window.location = response.url;
                    }
                }else{
                    let step = response.step;               
                    //$('#steptext').text((8-step)+' of 7 Steps Remaining'); 
                    step_text();
                    if(step!=6){
                        //$('.footer-progress li').removeClass('active');
                        //$('#pc_'+step).addClass('active');
                        active_element('next');
                        step_text();
                    }
                    $('#backbutton').text("Back").attr('data-step',step);
                    $('#submitbtn').attr('data-step',step);
                    $('#savelater').removeClass('d-none');
                    if(response.permissionHtml){
                        $('.'+$tech_type_val+'-container #permission').html(response.permissionHtml);
                    }
                    if(response.infoHtml){
                        $('.'+$tech_type_val+'-container [data-formid="step_no_6"] #pageinfo').html(response.infoHtml);
                    }
                    if(response.project_type){
                        $('.'+$tech_type_val+'-container [data-formid="step_no_7"] #project_type_7').val(response.project_type);
                        if(response.project_type=='mobile'){
                            $('.'+$tech_type_val+'-container [data-formid="step_no_7"] .techlist').hide();
                            $('.'+$tech_type_val+'-container [data-formid="step_no_7"] #mobilelist').show();
                        }else if(response.project_type=='web'){
                            $('.'+$tech_type_val+'-container [data-formid="step_no_7"] .techlist').hide();
                            $('.'+$tech_type_val+'-container #weblist').show();
                        }else{
                            $('.'+$tech_type_val+'-container [data-formid="step_no_7"] .techlist').show();
                        }
                    }
                    $('.projectforms').hide();
                    //$('#step_no_'+step).show().focus();
                    $("."+$tech_type_val+'-container [data-formid="step_no_'+step+'"]').show().focus();

                    if(step==6){
                        card_display();
                    }
                    step_btn_display();

                    if(e.target.getAttribute("data-formid")=="step_no_1"){
                        $("#clientproject .project-type-container").each(function(){
                            $(this).find('[data-formid="step_no_2"]').show();
                        });
                    }
                }
                
                if(response.goto_back){
                    $("#tech_type").val('web');
                    $("#clientproject").addClass('web-project').removeClass('mobile-project').removeClass('both-project');
                    $("#top").addClass('web-project').removeClass('mobile-project').removeClass('both-project');
                    $(".web-container [data-formid='step_no_3']").show();
                }
              }
            },
            error: (response) => {
                //$('#loader').hide();
                $('.loader-con').removeClass('d-flex').addClass('d-none');
                $('.loader-con').closest('.card').removeClass('loading-active');
                if(response.status === 422) {
                    let errors = response.responseJSON.errors;
                    Object.keys(errors).forEach(function (key) {
                       let id = key.split('.').shift();
                       $("#" + id + "Input").addClass("is-invalid");
                       $("#" + id + "Error").text(errors[key][0]);
                       let multiDimensionId = key.replace(/\./g,'_');
                       $("#" + multiDimensionId + "_Error").text(errors[key][0]);
                    });
                } else {
                  //  window.location.reload();
                }
            }
        })
    });
}); 
//  file uplaod
 function readURL(input) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();
    reader.onload = function(e) {
      $('.image-upload-wrap').hide();
      $('.file-upload-content').show();
      $('.image-title').html(input.files[0].name);
    };
    reader.readAsDataURL(input.files[0]);
  } else {
    removeUpload();
  }
}
function removeUpload() {
  $('.file-upload-input').replaceWith($('.file-upload-input').clone());
  $('.file-upload-content').hide();
  $('.image-upload-wrap').show();
}
