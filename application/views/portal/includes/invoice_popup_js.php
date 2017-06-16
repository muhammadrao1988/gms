<div id='payment_pop_modal2'></div>
<script>
    $('body').on('click', '.payment_pop', function () {

        var last_invoice = $(this).attr('data-invoice');
        var acc_id = $(this).attr('data-id');
        var month_due = $(this).attr('data-month');
        var register_day = $(this).attr('data-acc-date');
        //var register_day = "<?php echo current_url().( $_SERVER['QUERY_STRING']!="" ? '?msg=Invoice added successfully.' . $_SERVER['QUERY_STRING'] : ""); ?>";
        $("#payment_pop_modal2").modal('show');
        //
        $.ajax({
            type: "POST",
            url: "<?= site_url(ADMIN_DIR . "invoices/payNew"); ?>",
            data: "last_invoice=" + last_invoice +"&acc_id="+acc_id+"&month_due="+month_due+"&register_day="+register_day,
            complete: function (data) {


                $('#payment_pop_modal2').html(data.responseText);
                $('.ajax_form').AJAX_Form();
                $('.datepicker-default-ajax').datepicker({ dateFormat: 'dd-M-yy' });
                $('.select_ajax').uniform();

                // $("#payment_pop_modal2 .validation_html").validationEngine();


            }
        });

    });
</script>