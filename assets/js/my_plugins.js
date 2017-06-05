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

