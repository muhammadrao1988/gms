var DraggablePortlet = function () {

    return {
        //main function to initiate the module
        init: function () {

            if (!jQuery().sortable) {
                return;
            }

            $("#draggable_portlets_area").sortable({
                connectWith: ".actions-tabe",
                items: ".actions-tabe",
                opacity: 0.8,
                coneHelperSize: true,
                placeholder: 'sortable-box-placeholder round-all',
                forcePlaceholderSize: true,
                tolerance: "pointer",
                update: function( event, ui ) {
                    $('#service_manager_create_form > .actions-tabe').removeClass('first_action_tab');
                    $('#service_manager_create_form > .actions-tabe').eq(0).addClass('first_action_tab');
                    var i_counter_action;
                    var action_tabs_length = $('#service_manager_create_form > .actions-tabe').length;
                    for(i_counter_action = 1;i_counter_action <= action_tabs_length;i_counter_action++ ){
                        $('#service_manager_create_form > .actions-tabe').eq(i_counter_action-1).children('.panel-heading').html(ordinal_suffix_of(i_counter_action)+' Action <span class="tools pull-right"><a class="fa fa-times" href="javascript:void (0);"></a><img src="'+hand_icon_url+'" alt="Sortable" class="hand_icon"/></span>');
                        $('#service_manager_create_form > .actions-tabe').eq(i_counter_action-1).attr('id',i_counter_action+'-action-row');
                        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
						if(i_counter_action > 9){
								var sub_str =2;
							}else{
								var sub_str =1;
							}	
                        $('#service_manager_create_form > .actions-tabe .append-further').eq(i_counter_action-1).find('input[name],select').each(function(){
                            if($(this).attr('name')=='option_message_value'||$(this).attr('name')=='option_message' ||$(this).attr('name')=='option_repeat' ||$(this).attr('name')=='option_timeout' ){

                            }else{
                                $(this).attr('name',(i_counter_action-1)+$(this).attr('name').substr(sub_str));
                            }
                        });
                    }
                    if ($('#service_manager_create_form > .actions-tabe:last .form-group .ivr_actions_dropdown').val() == 'Send to a Voicemail') {
                        $('#service_manager_create_form > .add-another-action').parent('div').hide();
                        $('#save-ivr-action').show();
                    } else {
                        $('#service_manager_create_form > .add-another-action').parent('div').show();
                        $('#save-ivr-action').hide();
                    }
                    $('#service_manager_create_form > .actions-tabe .append-further')


                }
            });
            /*sortable for open/close action box*/
            /*$(".tc-append-box").sortable({
                connectWith: ".tc-open-actions-tabe,.tc-close-actions-tabe",
                items: ".tc-open-actions-tabe,.tc-close-actions-tabe",
                opacity: 0.8,
                coneHelperSize: true,
                placeholder: 'sortable-box-placeholder round-all',
                forcePlaceholderSize: true,
                tolerance: "pointer",
                update: function( event, ui ) {
                    console.log(ui);
                    if(ui.item[0].attributes[0].nodeValue == '1-tc-open-action-row' || ui.item[0].attributes[0].nodeValue == 'panel tc-open-actions-tabe'){
                        var i_counter_action_tc;
                        var action_tabs_length = $(this).find('.tc-open-actions-tabe').length;
                        for(i_counter_action_tc = 1;i_counter_action_tc <= action_tabs_length;i_counter_action_tc++ ){
                            $(this).find('.tc-open-actions-tabe').eq(i_counter_action_tc-1).children('.panel-heading').html(ordinal_suffix_of(i_counter_action_tc+1)+' Action <span class="tools pull-right"><a class="fa fa-times" href="javascript:void (0);"></a></span>');
                            $(this).find('.tc-open-actions-tabe').eq(i_counter_action_tc-1).attr('id',i_counter_action_tc+'-tc-open-action-row');
                        }
                        if ($(this).find('.tc-open-actions-tabe:last .ivr_actions_dropdown').val() == 'Send to a Voicemail') {
                            $(this).find('#tc-tab-open .tc-open-add-another-action').parent('div').hide();
                        } else {
                            $(this).find('#tc-tab-open .tc-open-add-another-action').parent('div').show();
                        }
                    }else{
                        var i_counter_action_tc;
                        var action_tabs_length = $(this).find('.tc-close-actions-tabe').length;
                        for(i_counter_action_tc = 1;i_counter_action_tc <= action_tabs_length;i_counter_action_tc++ ){
                            $(this).find('.tc-close-actions-tabe').eq(i_counter_action_tc-1).children('.panel-heading').html(ordinal_suffix_of(i_counter_action_tc+1)+' Action <span class="tools pull-right"><a class="fa fa-times" href="javascript:void (0);"></a></span>');
                            $(this).find('.tc-close-actions-tabe').eq(i_counter_action_tc-1).attr('id',i_counter_action_tc+'-tc-close-action-row');
                        }
                        if ($(this).find('.tc-close-actions-tabe:last .ivr_actions_dropdown').val() == 'Send to a Voicemail') {
                            $(this).find('#tc-tab-close .tc-close-add-another-action').parent('div').hide();
                        } else {
                            $(this).find('#tc-tab-close .tc-close-add-another-action').parent('div').show();
                        }
                    }
                }
            });
*/
            $(".column").disableSelection();

        }

    };

}();

function tc_dragbale(){
var DraggablePortlet_2 = function () {
            /*sortable for open/close action box*/
            $(".tc-append-box").sortable({
                connectWith: ".tc-open-actions-tabe,.tc-close-actions-tabe",
                items: ".tc-open-actions-tabe,.tc-close-actions-tabe",
                opacity: 0.8,
                coneHelperSize: true,
                placeholder: 'sortable-box-placeholder round-all',
                forcePlaceholderSize: true,
                tolerance: "pointer",
                update: function( event, ui ) {
					
                    if(ui.item[0].attributes[0].nodeValue == '1-tc-open-action-row' || ui.item[0].className == 'panel tc-open-actions-tabe' || ui.item[0].attributes[0].nodeValue == '2-tc-open-action-row'){
                        var action_tabs_length = $(this).find('.tc-open-actions-tabe').length;

                        for(i_counter_action_tc = 1;i_counter_action_tc <= action_tabs_length;i_counter_action_tc++ ){
                            $(this).find('.tc-open-actions-tabe').eq(i_counter_action_tc-1).children('.panel-heading').html(ordinal_suffix_of(i_counter_action_tc+1)+' Action <span class="tools pull-right"><a class="fa fa-times" href="javascript:void (0);"></a><img src="'+hand_icon_url+'" alt="Sortable" class="hand_icon"/></span>');
                            $(this).find('.tc-open-actions-tabe').eq(i_counter_action_tc-1).attr('id',i_counter_action_tc+'-tc-open-action-row');
							////////////////////////////////////////////////////////////////////////////////							
						if(i_counter_action_tc > 9){
								var sub_str =2;
							}else{
								var sub_str =1;
							}						

							  $('#service_manager_create_form .tc-append-box #tc-tab-open .tc-open-actions-tabe .append-further').eq(i_counter_action_tc-1).find('input[name],select').each(function(){
							  $(this).attr('name',(i_counter_action_tc)+$(this).attr('name').substr(sub_str));
						
							  });
                        }
                        if ($(this).find('.tc-open-actions-tabe:last .ivr_actions_dropdown').val() == 'Send to a Voicemail') {
                            $(this).find('#tc-tab-open .tc-open-add-another-action').parent('div').hide();
                        } else {
                            $(this).find('#tc-tab-open .tc-open-add-another-action').parent('div').show();
                        }
                    }else{
                        var i_counter_action_tc;
                        var action_tabs_length = $(this).find('.tc-close-actions-tabe').length;
                        for(i_counter_action_tc = 1;i_counter_action_tc <= action_tabs_length;i_counter_action_tc++ ){
                            $(this).find('.tc-close-actions-tabe').eq(i_counter_action_tc-1).children('.panel-heading').html(ordinal_suffix_of(i_counter_action_tc+1)+' Action <span class="tools pull-right"><a class="fa fa-times" href="javascript:void (0);"></a><img src="'+hand_icon_url+'" alt="Sortable" class="hand_icon"/></span>');
                            $(this).find('.tc-close-actions-tabe').eq(i_counter_action_tc-1).attr('id',i_counter_action_tc+'-tc-close-action-row');
							/////////////////////////////////////////////////////////////////////////////////////////////
							if(i_counter_action_tc > 9){
								var sub_str =2;
							}else{
								var sub_str =1;
							}				
							$('#service_manager_create_form .tc-append-box #tc-tab-close .tc-close-actions-tabe .append-further').eq(i_counter_action_tc-1).find('input[name],select').each(function(){
                            $(this).attr('name',(i_counter_action_tc)+$(this).attr('name').substr(1));
	                        });
                        }
                        if ($(this).find('.tc-close-actions-tabe:last .ivr_actions_dropdown').val() == 'Send to a Voicemail') {
                            $(this).find('#tc-tab-close .tc-close-add-another-action').parent('div').hide();
                        } else {
                            $(this).find('#tc-tab-close .tc-close-add-another-action').parent('div').show();
                        }
                    }
                }
            });

}();
}
function option_menu_dragable(){
    var DraggablePortlet_2 = function () {
        /*sortable for open/close action box*/
        $(".options_menu").sortable({
            connectWith: ".option-menu-actions-tabe",
            items: ".option-menu-actions-tabe",
            opacity: 0.8,
            coneHelperSize: true,
            placeholder: 'sortable-box-placeholder round-all',
            forcePlaceholderSize: true,
            tolerance: "pointer",
            update: function( event, ui ) {
                /*console.log(ui);
                console.log(this);*/
                    var i_counter_action_tc;
                    var action_tabs_length = $(this).find('.panel-body .tab-content > .active .option-menu-actions-tabe').length;
                    for(i_counter_action_tc = 1;i_counter_action_tc <= action_tabs_length;i_counter_action_tc++ ){
                        $(this).find('.panel-body .tab-content > .active .option-menu-actions-tabe').eq(i_counter_action_tc-1).children('.panel-heading').html(ordinal_suffix_of(i_counter_action_tc+1)+' Action <span class="tools pull-right"><a class="fa fa-times" href="javascript:void (0);"></a><img src="'+hand_icon_url+'" alt="Sortable" class="hand_icon"/></span>');
                        $(this).find('.panel-body .tab-content > .active .option-menu-actions-tabe').eq(i_counter_action_tc-1).attr('id',i_counter_action_tc+'-option-menu-action-row');
                        if(i_counter_action_tc > 9){
                            var sub_str =2;
                        }else{
                            var sub_str =1;
                        }
                        $(this).find('.panel-body .tab-content > .active .option-menu-actions-tabe .append-further').eq(i_counter_action_tc-1).find('input[name],select').each(function(){
                            $(this).attr('name',(i_counter_action_tc)+$(this).attr('name').substr(1));
                        });
                    }
                    if ($(this).find('.active .option-menu-actions-tabe:last .ivr_actions_dropdown').val() == 'Send to a Voicemail') {
                        $(this).find('#option-menu-tab .option-menu-add-another-action').parent('div').hide();
                    } else {
                        $(this).find('#option-menu-tab .option-menu-add-another-action').parent('div').show();
                    }
                }
        });

    }();
}