//jQuery( function( $ ) {
//    // search filed
//    var $s = $( '#s' );
//    // the search form
//    var $sForm = $s.closest( 'form' );
//    console.log( $sForm );
//    $sForm.on( 'submit', function( event) {
//        event.preventDefault();
//        $.post(
//            T5Ajax.ajaxurl,
//            {
//                action:     T5Ajax.action,
//                search_term: $s.val()
//            },
//            function( response ) {
//                // just append the result to the search form.
//                $sForm.append( response );
//            }
//        );
//    });
//});


jQuery(document).ready(function () {

    jQuery(document).on('change keyup paste click', '#s', function () {



        var str = jQuery(this).val();
        if(str.length > 2 ) {
            jQuery.post(
                T5Ajax.ajaxurl,
                {
                    action:     T5Ajax.action,
                    search_term: jQuery(this).val()
                },
                function( response ) {

                    jQuery('#result').html( response );
                }
            );
        }


    });

});
