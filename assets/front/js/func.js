(function ($) {
    $(document).ready(function () {
        $('#mycarousel').jcarousel({
            scroll: 1,
            visible: 1
        });

        $('form').validationEngine();
        $(".styled").uniform({ radioClass: 'choice' });

        /*$('.tip').tooltip();*/

        $("#slider-range-max").slider({
            range: "max",
            min: 1,
            max: 50,
            slide: function (event, ui) {
                $("#quantity").val(ui.value);
            }
        });
        $("#quantity").val($("#slider-range-max").slider("value"));

        $('.radio-chk').on('change', function () {
            $('.radio-chk').attr('checked', false).parent('span').removeClass('checked');
            $(this).attr('checked', true).parent('span').addClass('checked');
        });
    });
})(jQuery)