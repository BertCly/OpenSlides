var Search = function () {
    return {
        //main function to initiate the module
        init: function () {


            // handle the search submit(for sidebar search and responsive mode of the header search)
            $('.search-form .submit').on('click', function (e) {
                e.preventDefault();
                $('.search-form').submit();

            });


//            if (jQuery().datepicker) {
//                $('.date-picker').datepicker();
//            }
//            Metronic.initFancybox();
        }

    };

}();