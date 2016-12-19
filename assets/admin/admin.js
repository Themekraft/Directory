jQuery(document).ready(function (jQuery) {





    // User Accordion
    jQuery("#tk-pu-loop-sortable").sortable({
        revert: true
    });
    jQuery("#draggable").draggable({
        connectToSortable: "#sortable",
        helper: "clone",
        revert: "invalid"
    });
    jQuery("ul, li").disableSelection();





    jQuery(document.body).on('click', '.delete_loop_meta', function () {
        //alert(jQuery(this).attr('data-slug'));

        jQuery('#' + jQuery(this).attr('data-slug')).remove();
    });



    jQuery(document.body).on('change', '#form_fields_select', function () {
        var field_slug = jQuery(this).val();

        var type = jQuery(this).attr('data-type');

        if(jQuery('#tk-pu-' + type + '-sortable #' + field_slug).length) {
            alert('This Form Field is already in the list!')
        } else {
            jQuery('#tk-pu-' + type + '-sortable').append('<li id="' + field_slug + '"> ' +
                '<div class="menu-item-bar"> ' +
                '<div class="menu-item-handle ui-sortable-handle"> ' +
                '<input type="hidden" name="tk_ud_meta[' + type + '][' + field_slug + '][slug]" value="' + field_slug + '">' +
                '<input type="hidden" name="tk_ud_meta[' + type + '][' + field_slug + '][label]" value="' + field_slug + '">' +
                '<span class="item-title"><span class="menu-item-title">' + field_slug + '</span><span class="is-submenu" style="display: none;">sub item</span></span> ' +
                '<span class="item-controls">' +
                '<span class="item-type">' +
                '<input type="checkbox" name="tk_ud_meta[' + type + '][' + field_slug + '][view_label]" value="' + field_slug + '">View Label </span>' +
                '<a href="#" data-slug="' + field_slug + '" class="delete_loop_meta">Delete</a>' +
                ' </span> ' +

                '</div> ' +
                '</div> ' +
                '</li>');
        }

        jQuery(this).val('none');

    });


});

