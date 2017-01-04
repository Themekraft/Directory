jQuery(document).ready(function () {

    // Check if search term is entered
    jQuery(document).on('change keyup paste click', '#tk-ud-s', function () {

        var s = jQuery( this ).val();

        //console.log( s );

        if (s.length > 2) {
            tk_ud_ajax_search();
        } else {
            jQuery('#result').html('');
        }

    });

    // Check if plz is entered
    jQuery(document).on('change keyup paste click', '#tk-ud-s-plz', function () {

        var s_plz = jQuery( this ).val();

        if (s_plz.length > 4) {
            tk_ud_ajax_search();
        }

    });

    // Check for the distance
    jQuery(document).on('change keyup paste click', '#tk-ud-s-distance', function () {

        var s_distance = jQuery( this ).val();
        var s_plz = jQuery('#tk-ud-s-plz').val();

        if (s_plz.length > 4 && s_distance.length > 0) {
            tk_ud_ajax_search();
        }

    });



    // Check for the s-cat
    jQuery(document).on('change', '#tk-ud-s-cat', function () {
        tk_ud_ajax_search();
        return false;
    });



    // ok sure, we also check on submit ;)
    jQuery(document).on('submit', '#tk-ud-searchform', function () {
        tk_ud_ajax_search();
        return false;
    });


});

// Ajax search
function tk_ud_ajax_search() {

    var s           = jQuery('#tk-ud-s').val();
    var s_plz       = jQuery('#tk-ud-s-plz').val();
    var s_distance  = jQuery('#tk-ud-s-distance').val();
    var s_cat       = jQuery('#tk-ud-s-cat').val();

    jQuery.post(
        T5Ajax.ajaxurl,
        {
            action: T5Ajax.action,
            search_term: s,
            search_plz: s_plz,
            search_distance: s_distance,
            search_cat: s_cat
        },
        function (response) {

            jQuery('#result').html(response);

        }
    );

}