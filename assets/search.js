jQuery(document).ready(function () {

    // Check if search term is entered
    jQuery(document).on('change keyup paste click', '#s', function () {

        var s = jQuery('#s').val();

        if (s.length > 2) {
            tk_ud_ajax_search();
        }

    });

    // Check if plz is entered
    jQuery(document).on('change keyup paste click', '#s-plz', function () {

        var s_plz = jQuery('#s-plz').val();

        if (s_plz.length > 4) {
            tk_ud_ajax_search();
        }

    });

    // Check for the distance
    jQuery(document).on('change keyup paste click', '#s-distance', function () {

        var s_plz = jQuery('#s-plz').val();
        var s_distance = jQuery('#s-distance').val();

        if (s_plz.length > 4 && s_distance.length > 0) {
            tk_ud_ajax_search();
        }

    });

    // ok sure, we also check on submit ;)
    jQuery(document).on('submit', '#tk-ud-searchform', function () {
        tk_ud_ajax_search();
        return false;
    });


});

// Ajax search
function tk_ud_ajax_search() {

    var s = jQuery('#s').val();
    var s_plz = jQuery('#s-plz').val();
    var s_distance = jQuery('#s-distance').val();
    var s_cat = jQuery('#s-cat').val();

    jQuery.post(
        T5Ajax.ajaxurl,
        {
            action: T5Ajax.action,
            search_terms: s,
            search_plz: s_plz,
            search_distance: s_distance,
            search_cat: s_cat
        },
        function (response) {

            jQuery('#result').html(response);

        }
    );

}