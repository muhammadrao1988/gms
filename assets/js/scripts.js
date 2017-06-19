//left side accordion
;$(function($) {
	
	//======Status confirmation close ===//
	  $('body').on('click','.status_close',function(){
		
           $('#myModal3').modal('hide');
        });
    $('body').on('click','.pwd_reset_close',function(){

        $('#pasword_reset_modal').modal('hide');
    });
     $('body').on('click','#reset-user-password',function(){
		
          $("#resetpassword-form").submit();
        });
    $('body').on('click','#refresh_btn_page',function(){
        var refresh_value = '';
        var redirect_url = $(this).attr('data-url');
        var current_host = $(location).attr('host');
        var root_dir = $("#report_updated_checkbox").attr('data-location');
        $("#error_report_update").hide();
        if($('#report_updated_checkbox').attr('checked')) {


            var refresh_value = $("#report_updated_value").val();
            var intRegex = /^\d+$/;
            if(!intRegex.test(refresh_value)) {

                $("#error_report_update").html("Value should be in integer.");
                $("#error_report_update").show();
            }else if(refresh_value < 30){
                $("#error_report_update").html("Minimum value is 30 seconds.");
                $("#error_report_update").show();
            }else {

                var url_host = $(location).attr('protocol')+"//"+current_host + "/" + root_dir + "/portal/my_numbers_reports/update_record_refresh";


                $.ajax({
                    type: 'GET',
                    url: url_host,
                    data: {report_refresh: refresh_value, rt: "json"},

                    success: function (jsonData) {
                        //location.reload();
                    }
                });
            }
        } else {
            var url_host = $(location).attr('protocol')+"//"+current_host + "/" + root_dir + "/portal/my_numbers_reports/unset_record_refresh";
            $.ajax({
                type: 'GET',
                url: url_host,
                data: { report_refresh: refresh_value, rt: "json" },

                success: function (jsonData) {
                    // location.reload();
                }
            });
        }
        $(document).ajaxStop(function () {

            location.href =redirect_url;
        });

    });
	 //===== Form Validation =====//
	$("#validate").validationEngine({promptPosition : "topRight:-122,-5"});
    $(".validate").validationEngine({promptPosition : "topRight:-122,-5"});
	 //===== Select box with search =====//
    $(".styled_select select").uniform();
    $(".styled_select input[type='file']").uniform();
	$(".select").select2();
    $('.select-default').select2({
        minimumResultsForSearch: -1
    });
	 //===== Tags =====//

    $('.tags').tagsInput({width: '100%'});
    $('.tags-autocomplete').tagsInput({
        width: '100%',
        autocomplete_url: 'tags_autocomplete.html'
    });
	//===============Delete Confirmation========//
	$('.del-data').click(function(){
		var id =$(this).attr("id");
		
		$('#del-id').attr('value', id);
		});
		
	//===============Delete Confirmation========//
	$('.download-report').click(function(){
		var id =$(this).attr("data-url");
		
		$('#download-url').attr('value', id);
		});	
	//===============Status========//	
	$('body').on('click','.status-data',function(){
	
		var id =$(this).attr("id")
		$('#status-id').attr('value', id);
		});	
	
		
    $('#nav-accordion').dcAccordion({
        eventType: 'click',
        autoClose: true,
        saveState: true,
        disableLink: true,
        speed: 'slow',
        showCount: false,
        autoExpand: true,
        classExpand: 'dcjq-current-parent'
    });
	//alert();
	var str = window.location.pathname;
	
	var advice_line = str.split("/");
	
	
	
    // left side bar nav expand
    if($('.sub li').hasClass('active')){
        $('.sub li.active').parents('.sub-menu').find('a').addClass('active');
    }
    if($('.sub-menu a').hasClass('active')){
        $('.sub-menu a.active').parents('.sub-menu').find('ul').attr('style','display:block;');
    }
	if($('.sub li a:contains(User Details)')){
		 $('.sub li a:contains(User Details)').css("display","none");
		 $('.sub li a:contains(company)').css("display","none");
		 $('.sub li a:contains(Billing)').css("display","none");
        $('.sub li a:contains(SMS)').css("display","none");
        $('.sub li a:contains(Auto Logout)').css("display","none");
        $('.sub li a:contains(Statements)').css("display","none");
		 $('.sub li a:contains(Billing Admin)').css("display","block");
		 $('.sub li a[href*="billing_customer"]').css("display","block");
		// $("p:contains(is)").css("background-color","yellow");
       // $('.sub-menu a.active').parents('.sub-menu').find('ul').attr('style','display:block;');
	   // $('.sub-menu a.active').parents('.sub-menu').find('ul').attr('style','display:none;');
	   
    }
	
	if($('.sub li a:contains(Inbound Numbers)')){
		$('.sub li a:contains(Inbound Numbers)').css("display","none");
		$('.sub li a:contains(Message Configuration)').css("display","none");
		$('.sub li a:contains(Voicemail)').css("display","none");
		$('.sub li a:contains(Recordings)').css("display","none");
	}
	
	if(advice_line[3]=="inbound_numbers" || advice_line[3]=="message_configuration" || advice_line[3]=="recordings"|| advice_line[3]=="advice_voicemail"|| advice_line[3]=="condition" ){
		 $('.sub-menu a:contains(Advice Lines)').addClass('active');
		 $('.sub li a:contains(Advice Line Manager)').parents('.sub').show();
		 $('.sub li a:contains(Advice Line Manager)').addClass('active');
		 $('.sub li a:contains(Advice Line Manager)').parent('li').addClass('active');
		 
		 
		
	}
	if(advice_line[2]=="inbound_numbers" || advice_line[2]=="message_configuration" || advice_line[2]=="recordings"|| advice_line[2]=="advice_voicemail"|| advice_line[2]=="condition" ){
		 $('.sub-menu a:contains(Advice Lines)').addClass('active');
		 $('.sub li a:contains(Advice Line Manager)').parents('.sub').show();
		 $('.sub li a:contains(Advice Line Manager)').addClass('active');
		 $('.sub li a:contains(Advice Line Manager)').parent('li').addClass('active');
		 
		 
		
	}
	if(advice_line[3]=="customer_order_manager" || advice_line[3]=="customer_ticket_manager"  || advice_line[3]=="customer_service_manager"  ||  advice_line[2]=="customer_service_manager"  || advice_line[2]=="customer_order_manager" || advice_line[2]=="customer_ticket_manager"){
		
		 $('.sub-menu a:contains(Customers)').addClass('active');
		 $('.sub li a:contains(Customer Manager)').parents('.sub').show();
		 $('.sub li a:contains(Customer Manager)').addClass('active');
		 $('.sub li a:contains(Customer Manager)').parent('li').addClass('active');
		 
		 
		
	}
	$('.sub-menu a:contains(Account)').click(function(){
		var aa = $(this).attr('href');
		location.href = aa;
		})
		
	 if($('.sidebar-menu > li').hasClass('active') && $('.sidebar-menu li a:contains(Help)') ){
      		$('.sidebar-menu > li.active a').addClass('active');
    	}
	//}
    // page top searching buttons
    $('.btn-group button').click(function(){
        var href = $(this).attr('data-action');
        if(href != ''){
        $(location).attr('href',href);
        }
    });
    // back button
    $('a.back,button.back').click(function(){
        parent.history.back();
        return false;
    });
    //date picker
	 
	 $("#date_range").datepicker({
       dateFormat: 'yy/mm/dd',
        onSelect: function(selected) {
          $("#date_range2").datepicker("option","minDate", selected)
        }
    });
    $("#date_range2").datepicker({ 
        dateFormat: 'yy/mm/dd',
        onSelect: function(selected) {
           $("#date_range").datepicker("option","maxDate", selected)
        }
    });  

	 
	 $('.datepicker').datepicker({ dateFormat: 'yy/mm/dd' }).val();
	 $('.datepicker-sql').datepicker({ dateFormat: 'yy-mm-dd' }).val();
	$( ".datepicker" ).datepicker( "option", "maxDate", '+0m +0w' );//calendar end date restrict to today
	
	 
  
	 $('.datepicker2').datepicker();
	 $('.datepicker-default').datepicker({ dateFormat: 'dd/mm/yy' });
	 $('.datepicker-format').datepicker({ dateFormat: 'dd-M-yy',maxDate: "0" });
	 $('.datepicker-dob').datepicker({ dateFormat: 'dd-M-yy',maxDate: "0",defaultDate: "-18y", }).prop('readonly',true);

 $('#checkRow').click(function(event) {  //on click 
        if(this.checked) { // check select status
            $('.chk_box').each(function() { //loop through each checkbox
                this.checked = true;  //select all checkboxes with class "checkbox1"               
            });
        }else{
            $('.chk_box').each(function() { //loop through each checkbox
                this.checked = false; //deselect all checkboxes with class "checkbox1"                       
            });         
        }
    });
	$('.help_info_model').click(function () {
		$('.modal-backdrop').addClass("style");		
	});


});
function grid_form_submit(){
	
	var top_select  	= document.getElementById('limit_top').value;
	var redirect_url  	= ($("#current_url").length > 0 ? $('#current_url').val():'');
	var current_limit  	= ($("#current_limit").length > 0 ? $('#current_limit').val():'');
	
	
	if(redirect_url!=""){
	var redirect_url  	= redirect_url.replace('&limit='+current_limit, '');
	document.getElementById('rec_limit').value = top_select;
	location.href 		= redirect_url+'&limit='+top_select;
	}else{
	document.getElementById('rec_limit').value = top_select;	
	$('.grid_form').submit();
	}
}
function validate_calender(){
var date_range  = document.getElementById('date_range').value;
var date_range2  = document.getElementById('date_range2').value;	
if(date_range=="" || date_range2==""){
	document.getElementById('error_calender').style.display = 'block';
return false;	
}else{
	document.getElementById('error_calender').style.display = 'none';
return true;	
}
}
function redirect_url(url){
	location.href=url;

}

function clear_calender_form(){
$('input[type=text]').removeAttr('value') ;	
	
}
/*Reset form fields*/
$('body').on('click','button[action-type="reset"]',function(){
    $(this).closest('form').find('input,select,textarea').each(function(){
        if($(this).is('input')){
            $(this).val('');
            $(this).prop("checked", false);
        }else if($(this).is('textarea')){
            $(this).val('');
        }else{
            if($(this).attr('id')!='limit_top'){
                $(this).parent('.selector').children('span').text($(this).children('option[value=""]').text());
                $(this).children('option[value=""]').prop("selected", true);
            }
        }
    });
});

var Script = function () {

    //  menu auto scrolling

    jQuery('#sidebar .sub-menu > a').click(function () {
        var o = ($(this).offset());
        diff = 80 - o.top;
        if(diff>0)
            $("#sidebar").scrollTo("-="+Math.abs(diff),500);
        else
            $("#sidebar").scrollTo("+="+Math.abs(diff),500);
    });

    // toggle bar

    $(function() {
        var wd;
        wd = $(window).width();
        function responsiveView() {
            var newd = $(window).width();
            if(newd==wd){
                return true;
            }else{
                wd = newd;
            }
            var wSize = $(window).width();
            if (wSize <= 768) {
                $('#sidebar').addClass('hide-left-bar');

            }
        else{
                $('#sidebar').removeClass('hide-left-bar');
            }

        }
        $(window).on('load', responsiveView);
        $(window).on('resize', responsiveView);




    });

    $('.sidebar-toggle-box .fa-bars').click(function (e) {
        $('#sidebar').toggleClass('hide-left-bar');
        $('#main-content').toggleClass('merge-left');
        e.stopPropagation();
        if( $('#container').hasClass('open-right-panel')){
            $('#container').removeClass('open-right-panel')
        }
        if( $('.right-sidebar').hasClass('open-right-bar')){
            $('.right-sidebar').removeClass('open-right-bar')
        }

        if( $('.header').hasClass('merge-header')){
            $('.header').removeClass('merge-header')
        }



    });
    $('.toggle-right-box .fa-bars').click(function (e) {
        $('#container').toggleClass('open-right-panel');
        $('.right-sidebar').toggleClass('open-right-bar');
       // $('.header').toggleClass('merge-header');
		if($('#op-tog').hasClass('act')){
		$('#op-tog').hide();
		$('#op-tog').removeClass('act');
		$('#cl-tog').show();
		}else{
		$('#op-tog').show();
		$('#op-tog').addClass('act');
		$('#cl-tog').hide();	
		}
		
	
		

        e.stopPropagation();
    });
	$('.help_closed').click(function () {
   //when click to open
   $(".help_closed").hide('slide', {direction: 'right'}, 300);
   $(".help_opened").show('slide', {direction: 'right'}, 1000 , function(){
     $(".help_section_content").slideDown('slow');
    }
   );
  });
  $('.help_opened').click(function () {
   //when click to closed
   $(".help_section_content").slideUp('slow', function() {
    // Animation complete.
    $(".help_opened").hide('slide', {direction: 'right'}, 1000);
    $(".help_closed").show('slide', {direction: 'right'}, 1300);
   });
  });
  
  

    $('.header,#main-content,#sidebar').click(function () {
       if( $('#container').hasClass('open-right-panel')){
           $('#container').removeClass('open-right-panel')
       }
        if( $('.right-sidebar').hasClass('open-right-bar')){
            $('.right-sidebar').removeClass('open-right-bar')
        }

        if( $('.header').hasClass('merge-header')){
            $('.header').removeClass('merge-header')
        }


    });


   // custom scroll bar
    $("#sidebar").niceScroll({styler:"fb",cursorcolor:"#1FB5AD", cursorwidth: '3', cursorborderradius: '10px', background: '#404040', spacebarenabled:false, cursorborder: ''});
    $(".right-sidebar").niceScroll({styler:"fb",cursorcolor:"#1FB5AD", cursorwidth: '3', cursorborderradius: '10px', background: '#404040', spacebarenabled:false, cursorborder: ''});


   // widget tools

    jQuery('.panel .tools .fa-chevron-down').click(function () {
        var el = jQuery(this).parents(".panel").children(".panel-body");
        if (jQuery(this).hasClass("fa-chevron-down")) {
            jQuery(this).removeClass("fa-chevron-down").addClass("fa-chevron-up");
            el.slideUp(200);
        } else {
            jQuery(this).removeClass("fa-chevron-up").addClass("fa-chevron-down");
            el.slideDown(200);
        }
    });

   /* jQuery('.panel .tools .fa-times').click(function () {
        jQuery(this).parents(".panel").parent().remove();
    });*/

   // tool tips

    $('.tooltips').tooltip();

    // popovers

    $('.popovers').popover();

    // notification pie chart
    $(function() {
        $('.notification-pie-chart').easyPieChart({
            onStep: function(from, to, percent) {
                $(this.el).find('.percent').text(Math.round(percent));
            },
            barColor: "#39b6ac",
            lineWidth: 3,
            size:50,
            trackColor: "#efefef",
            scaleColor:"#cccccc"

        });

    });


/*    $(function() {

        var datatPie = [30,50];*/
// DONUT
/*
        $.plot($(".target-sell"), datatPie,
            {
                series: {
                    pie: {
                        innerRadius: 0.6,
                        show: true,
                        label: {
                            show: false

                        },
                        stroke: {
                            width:.01,
                            color: '#fff'

                        }
                    }




                },

                legend: {
                    show: true
                },
                grid: {
                    hoverable: true,
                    clickable: true
                },

                colors: ["#ff6d60", "#cbcdd9"]
            });
    });
*/

    $(function() {
        $('.pc-epie-chart').easyPieChart({
            onStep: function(from, to, percent) {
                $(this.el).find('.percent').text(Math.round(percent));
            },
            barColor: "#5bc6f0",
            lineWidth: 3,
            size:50,
            trackColor: "#32323a",
            scaleColor:"#cccccc"

        });

    });



    $(function() {
        $(".d-pending").sparkline([3,1], {
            type: 'pie',
            width: '40',
            height: '40',
            sliceColors: ['#e1e1e1','#8175c9']
        });
    });



// SPARKLINE
    $(function () {
        var sparkLine = function () {
            $(".sparkline").each(function(){
                var $data = $(this).data();
                ($data.type == 'pie') && $data.sliceColors && ($data.sliceColors = eval($data.sliceColors));
                ($data.type == 'bar') && $data.stackedBarColor && ($data.stackedBarColor = eval($data.stackedBarColor));

                $data.valueSpots = {'0:': $data.spotColor};
                $(this).sparkline( $data.data || "html", $data);


                if($(this).data("compositeData")){
                    $spdata = $(this).data("compositeConfig");
                    $spdata.composite = true;
                    $spdata.minSpotColor = false;
                    $spdata.maxSpotColor = false;
                    $spdata.valueSpots = {'0:': $spdata.spotColor};
                    $(this).sparkline($(this).data("compositeData"), $spdata);
                };
            });
        };

        var sparkResize;
        $(window).resize(function (e) {
            clearTimeout(sparkResize);
            sparkResize = setTimeout(function () {
                sparkLine(true)
            }, 500);
        });
        sparkLine(false);
    });

    /*==Collapsible==*/
    $(function() {
        $('.widget-head').click(function(e)
        {
            var widgetElem = $(this).children('.widget-collapse').children('i');

            $(this)
                .next('.widget-container')
                .slideToggle('slow');
            if ($(widgetElem).hasClass('ico-minus')) {
                $(widgetElem).removeClass('ico-minus');
                $(widgetElem).addClass('ico-plus');
            }
            else
            {
                $(widgetElem).removeClass('ico-plus');
                $(widgetElem).addClass('ico-minus');
            }
            e.preventDefault();
        });
	

    });
// grid delete	
$("#delete-all").click(function(event){
    event.preventDefault();
    var searchIDs = $(".chk_box:checkbox:checked").map(function(){
      return $(this).val();
    }).get(); // <----
	if(searchIDs==""){
	$("#myModal2").hide();
	$('.notification').html('<div class="alert alert-danger" style="margin-top:10px;"><button type="button" class="close" data-dismiss="alert">×</button>Please select at least one checkbox.</div>');
	return false;
	}else{
	$('#del-all').attr('value', searchIDs);	
	$('#action').attr('value', '1');	
	}
	
});

//    $(function() {
//
//    $('.todo-check label').click(function(){
//        var n = $(this).parents('li input[type="checkbox"]');
//        if ($(n).is(':checked')){
//alert('tets');
//        }
//        else{
//            alert('none');
//        }
//
//    });
//
//    });
}();
$(document).ready(function () {
    if($(window).width() <= 740){
        /*alert($(window).width());*/
        $('#sidebar').addClass('hide-left-bar');
    }
});

