jQuery(document).ready(function () {

    jQuery("#tk-ud-spinner").bind("ajaxSend", function() {
        jQuery(this).show();
        }).bind("ajaxStop", function() {
        jQuery(this).hide();
        }).bind("ajaxError", function() {
        jQuery(this).hide();
    });

    // Check if search term is entered
    jQuery(document).on('change keyup paste click delete', '#tk-ud-s', function () {

        var s = jQuery( this ).val();

        if (s.length > 2) {
            jQuery('#tk-ud-paged').val(0);
            tk_ud_ajax_search();
        }

    });

    // Check for the s-cat
    jQuery(document).on('change', '#tk-ud-s-cat', function () {
        jQuery('#tk-ud-paged').val(0);
        tk_ud_ajax_search();
        return false;
    });

    // ok sure, we also check on submit ;)
    jQuery(document).on('submit', '#tk-ud-searchform', function () {
        tk_ud_ajax_search();
        return false;
    });

    jQuery(document).on( 'click', '.nav-links a', function( event ) {
        event.preventDefault();
        var page        = find_page_number( jQuery( this ).clone() );

        if(!isNaN(page)){
            jQuery('#tk-ud-paged').val(page);
            tk_ud_ajax_search();
            return false;
        }

        if (jQuery(this).hasClass('next')){
            page = parseInt( jQuery('#tk-ud-paged').val() );
            page++;
            jQuery('#tk-ud-paged').val(page);
            tk_ud_ajax_search();
            return false;
        }

        if (jQuery(this).hasClass('prev')){
            page = parseInt( jQuery('#tk-ud-paged').val() );
            page--;
            jQuery('#tk-ud-paged').val(page);
            tk_ud_ajax_search();
            return false;
        }

    });

    // Reload the search if reset is triggered
    jQuery(document).on('click', '#reset', function () {
        location.reload();
    });

    tk_ud_ajax_search();

});

// function to get the current page number
function find_page_number( element ) {
    element.find('span').remove();
    return parseInt( element.html() );
}

// Ajax search
function tk_ud_ajax_search() {

    var s           = jQuery('#tk-ud-s').val();
    var s_plz       = jQuery('#tk-ud-s-plz').val();
    var s_plz_multi = jQuery('#tk-ud-s-plz-multi').val();
    var s_distance  = jQuery('#tk-ud-s-distance').val();
    var s_cat       = jQuery('#tk-ud-s-cat').val();
    var paged       = jQuery('#tk-ud-paged').val();


    jQuery.post(
        TK_UD_Ajax.ajaxurl,
        {
            action: TK_UD_Ajax.action,
            search_term: s,
            search_plz: s_plz,
            search_plz_multi: s_plz_multi,
            search_distance: s_distance,
            search_cat: s_cat,
            paged: paged
        },
        function (response) {

            jQuery('#tk-ud-search-result').html(response);
            if(jQuery('#tk-ud-search-result').length > 0) jQuery("html, body").animate({ scrollTop: 0 }, "slow");

        }
    );

}