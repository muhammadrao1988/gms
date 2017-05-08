<script type="text/javascript">

    var date_frame = '<?=getVar('date_frame');?>';
    (function ($) {
        $(document).ready(function () {
		
        if(date_frame != '' && date_frame!="Custom Dates"){
		
            $('button[data-action*="'+date_frame+'"]').addClass('active');
        }else if(date_frame=="Custom Dates"){
			
			 $('#date_frame').addClass('active');
		}else{
			
            $('.btn-group button').eq(0).addClass('active');
        }
        });
    })(jQuery)
	$(document).ready(function () {
       // custom date functions
         $('.custom-date').hide();
        $('#date_frame').click( function () {
            $('.btn-group button').removeClass('active');
            $('.btn-group button').eq(6).addClass('active');
            if($(this).hasClass('open')){
                $('.custom-date').hide();
                $('.custom-date').removeClass('open');
            }else{
                $('.custom-date').show();
                $('.custom-date').addClass('open');
            }

        });
     	if(date_frame=="Custom Dates"){
			$('.custom-date').show();
		}

       
       
    });
</script>