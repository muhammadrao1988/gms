$(function () {

    $.fn.AJAX_Form = function (options) {

        // global
        var form;
        // Establish our default settings
        var settings = $.extend({
            post_url: null,
            method: null,
            form_EL: null,
            async: true,
            dataType: 'JSON',
            validate: true,
            notification_EL: '.notification',
            beforeSubmition: _show_loader,
            afterSubmition: _hide_loader
			
        }, options);

        return this.each(function () {
            // We'll get back to this in a moment
            $(this).on('submit', function (e) {
                e.preventDefault();
                form = $(this);
                settings.beforeSubmition(form);
                if (form.validationEngine('validate') && settings.validate) {
                    _submitForm(form);
                } else {
                    $('.ajax_loading').hide();
                }
            });
        });


        function _submitForm(form) {
			 $('#vm_conf_submit').attr("disabled", true);
			
			 if( form.attr('id')=="time-condition-form"){
				 $('#timecondition-submit').attr("disabled", true);
			 }
            $.ajax({
                type: ((settings.method != null) ? settings.method : form.attr('method')),
                dataType: settings.dataType,
                async: settings.async,
                url: ((settings.post_url != null) ? settings.post_url : form.attr('action')),
                data: ((settings.form_EL != null) ? $(settings.form_EL).serialize() : form.serialize()),
                complete: function (data) {
					 if( form.attr('id')=="time-condition-form"){
				 $('#timecondition-submit').attr("disabled", true);
			 		}
					$('#vm_conf_submit').attr("disabled", false);
                    var JSON = $.parseJSON(data.responseText);
                    settings.afterSubmition(form, JSON);
                }
            });
        }

        function _show_loader(e) {
            $('.ajax_loading').show();
            //console.log('show_loader');
        }

        function _hide_loader(e, JSON) {
			
			
            $('.ajax_loading').hide();
			if(JSON.redirect_url){
				
			location.href = JSON.redirect_url;	
			}
			if(JSON.method=="voicemail" && JSON.result==200 && JSON.field_name!=""){


                var is_edit = $("select[name='"+JSON.field_name+"'] option[value='"+JSON.prompt_id+"']").length;

                if(is_edit > 0){
                    $("select[name='"+JSON.field_name+"'] option[value='"+JSON.prompt_id+"']").remove();
                }
                $('select[name="'+JSON.field_name+'"] option').removeAttr("selected");
                
				$('select[name="'+JSON.field_name+'"]').append('<option value="'+JSON.prompt_id+'" selected="selected">'+JSON.voicemail_name+'</option>');
			 	/*$('#uniform-'+JSON.field_name+' > span').text(JSON.voicemail_name);*/
               // alert("ddd");
                $('select[name="'+JSON.field_name+'"]').parent('.selector').children('span').text(JSON.voicemail_name);

			 	document.getElementById('notification').innerHTML = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">×</button>'+JSON.resultText+'</div>';
				$('#voicemail_message').modal('hide');
			
				
			}else if(JSON.method=="voicemail" && JSON.result==200 && JSON.field_name==""){
				
				 document.getElementById('notification_vm').innerHTML = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">×</button>'+JSON.resultText+'</div>';
				location.href = '?prompt='+JSON.prompt_id;
				
				
			}else if(JSON.method=="voicemail" && JSON.result==401){
				document.getElementById('notification_vm').innerHTML = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">×</button>'+JSON.resultText+'</div>';
			}
			
			if(JSON.method=="welcomemessage" && JSON.result==200 && JSON.field_name==""){
				document.getElementById('notification_wc').innerHTML = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">×</button>'+JSON.resultText+'</div>';
				location.href = '?wc_prompt='+JSON.wc_prompt;
				//document.location.reload(true);
			}else if(JSON.method=="welcomemessage" && JSON.result==200 && JSON.field_name!=""){
				
				document.getElementById('notification').innerHTML = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">×</button>'+JSON.resultText+'</div>';
				
				$('input[name="'+JSON.field_name+'"]').val(JSON.wc_prompt);
				$('input[name="'+JSON.field_name+'_value"]').val(JSON.welcome_name);
                $('#wm-add-file-btn').after('<a href="javascript:void(0);" data-toggle="modal" onclick="divert_number_welcome($(\'#vm_welcome_id\').val(),1,\'divert_welcome\');" class="btn btn-black pull-left"> <i class="fa fa-pencil"></i> </a>');
                $('#wm-add-file-btn').remove();
				$('#welcome_message').modal('hide');
				//location.href = '?wc_prompt='+JSON.wc_prompt;
				//document.location.reload(true);
				
			}else if(JSON.method=="welcomemessage" && JSON.result==401){
				document.getElementById('notification_wc').innerHTML = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">×</button>'+JSON.resultText+'</div>';
			}
			
			if(JSON.method=="tc_add" && JSON.result==200){
				location.href = '?msg='+JSON.resultText;
				document.getElementById('notification').innerHTML = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">×</button>'+JSON.resultText+'</div>';

				
			}
			
			if(JSON.method=="ivr_tc_add" && JSON.result==200){
			 $('#add_time_conditions').modal('hide');
			 $('#ivr_edit_tc').append('<option value="'+JSON.id+'" selected="selected">'+JSON.name+'</option>');
			 $('#uniform-ivr_edit_tc > span').text(JSON.name);

             $('#tc_new_option_value').val(JSON.id);
             $('#tc_new_option_text').val(JSON.name);
			 document.getElementById('notification').innerHTML = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">×</button>'+JSON.resultText+'</div>';
				
				
			}else if(JSON.method=="ivr_tc_edit" && JSON.result==200){
				$('#add_time_conditions').modal('hide');
				$("#ivr_edit_tc option:selected").text(JSON.name);
				$('#uniform-ivr_edit_tc > span').text(JSON.name);

                $('#tc_new_option_value').val(JSON.id);
                $('#tc_new_option_text').val(JSON.name);
				document.getElementById('notification').innerHTML = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">×</button>'+JSON.resultText+'</div>';

				
			}

            if(JSON.method=="divert_sms" && JSON.result==200){

                location.href = '?msg='+JSON.resultText;
            }else if(JSON.method=="divert_sms" && JSON.result==401){
                var msg = JSON.resultText;
                var errors = '';
                $.each(msg, function (key, val) {
                    errors = errors + '<div class="alert alert-danger "><button type="button" class="close" data-dismiss="alert">×</button>' + val + '</div>';

                });

                document.getElementById('notification_ds').innerHTML = errors;
            }

            if(JSON.method=="assign_number_packages" && JSON.result==200){
                $("#assign_number_modal").modal("hide");

                var str1 = self.parent.location;

                $(".loader-dashboard").show();
                var n = str1.toString().indexOf("?");
                // self.parent.location.reload();
                if(n >= 0) {
                    location.href = self.parent.location + '&msg='+JSON.resultText;
                }else{
                    location.href = self.parent.location + '?msg='+JSON.resultText;
                }

            }else if(JSON.method=="assign_number_packages" && JSON.result==401){
                var msg = JSON.resultText;

                    errors =  '<div class="alert alert-danger "><button type="button" class="close" data-dismiss="alert">×</button>' + msg + '</div>';


                document.getElementById('notification_an').innerHTML = errors;
            }
            if(JSON.method=="vip_manage" && JSON.result==200){
                $("#myModal22").modal("hide");

                var str1 = self.parent.location;

                $(".loader-dashboard").show();
                var n = str1.toString().indexOf("?");
                // self.parent.location.reload();
                if(n >= 0) {
                    location.href = self.parent.location + '&cli_id='+JSON.cli_match_id+'&msg='+JSON.resultText;
                }else{
                    location.href = self.parent.location + '?cli_id='+JSON.cli_match_id+'&msg='+JSON.resultText;
                }

            }else if(JSON.method=="vip_manage" && JSON.result==401){
                var msg = JSON.resultText;

                errors =  '<div class="alert alert-danger "><button type="button" class="close" data-dismiss="alert">×</button>' + msg + '</div>';


                document.getElementById('notification_vip').innerHTML = errors;
            }


            if(JSON.method=="assign_block_number" && JSON.result==200){
                //location.href = '?msg='+JSON.resultText;
                var msg = JSON.param;
                var posted_param = '';
                $.each(msg, function (key, val) {
                    posted_param = posted_param + key+'='+val+'&';

                });
                var asset_url = JSON.asset_url;
                posted_param_all = posted_param+"show_form=yes";
                //$("#assign_number_modal").modal("hide");
                $('.ajax_loading').show();
                $("#block_confirmation_number").modal('show');
                var package_info_html = $('.number_package_tab').html();

                $("#assign_number_modal_data").hide();



                $.ajax({
                    type: 'POST',
                    url: JSON.next_screen_url,
                    data: posted_param_all,

                complete: function (data) {
                    //console.log(data);

                    $('.ajax_loading').fadeOut('slow', function () {

                        $('#block_confirmation_number').html(data.responseText);
                        $('.block_package').html(package_info_html);
                        $('.ajax_form').AJAX_Form();

                        $("#assign_block_modal .validation_html").validationEngine();
                        var clientText = new ZeroClipboard($("#text-to-copy"), {
                            moviePath: asset_url + "/ZeroClipboard.swf",
                            debug: false
                        });

                        clientText.on("load", function (clientText) {
                            $('#flash-loaded').fadeIn();

                            clientText.on("complete", function (clientText, args) {
                                clientText.setText(args.text);
                                $('#text-to-copy-text').fadeIn();
                            });
                        });

                    });

                }
            });

            }else if(JSON.method=="assign_block_number" && JSON.result==401){
                var msg = JSON.resultText;

                errors =  '<div class="alert alert-danger "><button type="button" class="close" data-dismiss="alert">×</button>' + msg + '</div>';


                document.getElementById('notification_an').innerHTML = errors;
            }



            if(JSON.method=="assign_block_submitted" && JSON.result==200){
                $("#block_confirmation_number").modal('hide');
                $(".loader-dashboard").show();
                var str1 = self.parent.location;
                var n = str1.toString().indexOf("?");
                // self.parent.location.reload();
                if(n >= 0) {
                    location.href = self.parent.location + '&msg='+JSON.resultText;
                }else{
                    location.href = self.parent.location + '?msg='+JSON.resultText;
                }

            }else if(JSON.method=="assign_block_submitted" && JSON.result==401){

                var msg = JSON.resultText;

                errors =  '<div class="alert alert-danger "><button type="button" class="close" data-dismiss="alert">×</button>' + msg + '</div>';

                $(".notification").html(errors);

            }
            if(JSON.method=="history_add_comment" && JSON.result==200){
                $(".loader-dashboard").show();
                var str1 = self.parent.location;
                var n = str1.toString().indexOf("?");
                // self.parent.location.reload();
                if(n >= 0) {
                    location.href = self.parent.location + '&msg='+JSON.resultText;
                }else{
                    location.href = self.parent.location + '?msg='+JSON.resultText;
                }

            }else if(JSON.method=="history_add_comment" && JSON.result==401){

                var msg = JSON.resultText;

                errors =  '<div class="alert alert-danger "><button type="button" class="close" data-dismiss="alert">×</button>' + msg + '</div>';

                $("#notification_ham").html(errors);

            }

            if(JSON.method=="send_new_password" && JSON.result==200){

                $(".loader-dashboard").show();
                    location.href = self.parent.location + '?msg='+JSON.resultText;
            }else if(JSON.method=="send_new_password" && JSON.result==401){
                var msg = JSON.resultText;
                errors =  '<div class="alert alert-danger "><button type="button" class="close" data-dismiss="alert">×</button>' + msg + '</div>';
                $("#notification_ham").html(errors);

            }

			
            $(settings.notification_EL).html(JSON.notification);
        }

    };

    $.fn.reset_form = function () {
        $(this).each(function () {
            $(this).find(':password,:text,[type=email],[type=number],select,textarea').val('');
            $(this).find('checkbox,radio').attr('checked', false);
        });
    };

    $('.ajax_form').AJAX_Form();
	
});

