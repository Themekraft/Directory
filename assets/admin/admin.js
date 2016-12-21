jQuery(document).ready(function (jQuery) {

    // Sortable Accordions for the form elements
    jQuery(".tk-pu-sortable").sortable({
        revert: true
    });
    jQuery("#draggable").draggable({
        connectToSortable: "#sortable",
        helper: "clone",
        revert: "invalid"
    });
    jQuery("ul, li").disableSelection();

    // Remove all fields if the Form Select get changed
    jQuery(document.body).on('change', '#tk-ud-buddyforms', function () {
        jQuery('#tk-pu-loop-sortable').html("");
        jQuery('#tk-pu-single-sortable').html("");

    });

    // Remove fields from the accordion
    jQuery(document.body).on('click', '.tk-ud-delete-meta', function () {
        var slug = jQuery(this).attr('data-slug')
        var type = jQuery(this).attr('data-type')
        jQuery('#tk-pu-' + type + ' #' + slug).remove();
    });

    // Add new field to the Directory Settings Accordion for the Loop and Single
    jQuery(document.body).on('change', '.form-fields-select', function () {

        var slug = jQuery(this).val();
        var type = jQuery(this).attr('data-type');
        var name = jQuery(this).find(':selected').data('name');


        if (jQuery('#tk-pu-' + type + ' #' + slug).length) {
            alert('This Form Field is already in the list!')
        } else {
            jQuery('#tk-pu-' + type).append('<li id="' + slug + '"> ' +
                '<div class="menu-item-bar"> ' +
                '<div class="menu-item-handle ui-sortable-handle"> ' +
                '<input type="hidden" name="tk_ud_meta[' + type + '][' + slug + '][slug]" value="' + slug + '">' +
                '<input type="hidden" name="tk_ud_meta[' + type + '][' + slug + '][name]" value="' + name + '">' +
                '<span class="item-title"><span class="menu-item-title">' + name + '</span><span class="is-submenu" style="display: none;">sub item</span></span> ' +
                '<span class="item-controls">' +
                '<span class="item-type">' +
                '<input type="checkbox" name="tk_ud_meta[' + type + '][' + slug + '][view_label]" value="' + slug + '">View Label </span>' +
                '<a href="#" data-slug="' + slug + '" class="delete_loop_meta">Delete</a></span>' +
                '</div> ' +
                '</div> ' +
                '</li>');
        }

        jQuery(this).val('none');

    });

});

