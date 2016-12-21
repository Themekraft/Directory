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
                    action: T5Ajax.action,
                    search_term: jQuery(this).val()
                },
                function( response ) {

                    jQuery('#result').html( response );
                }
            );
        }


    });

    jQuery(document).on('submit', '#tk-ud-searchform', function () {
            alert('search');

        var s           = jQuery('#s').val();
        var s_plz       = jQuery('#s-plz').val();
        var s_distance  = jQuery('#s-distance').val();
        var s_cat       = jQuery('#s-cat').val();

        console.log(s);
        console.log(s_plz);
        console.log(s_distance);

        jQuery.post(
            T5Ajax.ajaxurl,
            {
                action: T5Ajax.action,
                search_terms:    s,
                search_plz:      s_plz,
                search_distance: s_distance,
                search_cat: s_cat
            },
            function( response ) {

                jQuery('#result').html( response );

            }
        );
        return false;
    });


});
