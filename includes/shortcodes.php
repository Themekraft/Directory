<?php

// Search the directory
function tk_ud_search( $atts ) {
	$tk_ud_search = get_option( 'tk_ud_search', true );
	ob_start();

	$with_map = is_plugin_active( 'directory-plz-search/loader.php' );

	?>

	<div class="panel">
		<h2 class="tk-ud-search-headline"><?php _e( 'Search', 'tk_ud' ); ?></h2>
		<div id="tk-ud-search">
			<form role="search" method="get" id="tk-ud-searchform" action="">

                    <div class="row">

                        <div class="col-md-3">
                            <input type="text" placeholder="<?php _e( 'Companyname', 'tk_ud' ); ?>" name="s" id="tk-ud-s"/>
                        </div>
                        <?php do_action( 'tk_ud_search_form' ); ?>

                        <?php if( isset( $tk_ud_search['display_category_filter'] ) && $tk_ud_search['display_category_filter'] == 'yes'){ ?>
                            <div class="col-md-3">
                                <script>
                                    jQuery(document).ready(function (jQuery) {
                                        jQuery("#tk-ud-s-cat").multipleSelect({
                                        <?php if( isset( $tk_ud_search['category_filter_select_all'] ) && $tk_ud_search['category_filter_select_all'] != 'yes' ){ ?>
                                            selectAll: false,
                                        <?php } ?>
                                            placeholder: "<?php _e('Filter', 'dav'); ?>"
                                        });
                                    });
                                </script>
                                <?php
                                ob_start();
                                wp_dropdown_categories( array( 'taxonomy' => 'directory_categories',
                                    'id' => 'tk-ud-s-cat',
                                    'multiple' => true,
                                    'orderby' => 'name',
                                    'order' => 'ASC',
                                    'hierarchical' => '1'
                                ) );
                                $dropdown = ob_get_clean();
                                $dropdown = str_replace( 'id=', 'multiple="multiple" id=', $dropdown );
                                $dropdown = str_replace( 'id=', 'style="width:93%;" id=', $dropdown );
                                echo $dropdown;
                                ?>
                            </div>
                        <?php } ?>
                        <?php if($with_map) : ?>
                            <div id="tk-ud-mapContainer" class="col-md-4" style="">
                                <?php echo do_shortcode('[directory_search_map_de]'); ?>
                            </div>
                        </div>
                        <div class="row">
                            <?php endif; ?>
                            <div class="col-md-3">
                            <div class="row">
                                <?php if( isset( $tk_ud_search['display_submit'] ) && $tk_ud_search['display_submit'] == 'yes' ){ ?>
                                    <div class="col-lg-6"><input type="submit" id="searchsubmit" value="<?php _e( 'Search', 'tk_ud' ); ?>" class="btn btn-primary btn-small"/></div>
                                <?php } ?>
                                <?php if( isset( $tk_ud_search['display_reset'] ) && $tk_ud_search['display_reset'] == 'yes' ){ ?>
                                    <div class="col-lg-6"><input type="reset" id="reset" value="<?php _e( 'Reset', 'tk_ud' ); ?>"  class="btn btn-primary btn-small"/></div>
                                <?php } ?>
                            </div>
                        </div>

                    </div>

            </form>
        </div>
    <?php if($with_map): ?>
        <div class="row">
            <div class="col-md-8">
        <?php endif; ?>
                <div id="tk-ud-spinner" class="spinner" style="display: block !important;"><span class="tk-search-text">Searching...</span></div>
            <div id="tk-ud-search-result"></div>
                <?php if ( $with_map ): ?>
            </div>

        </div>
    <?php endif; ?>

	</div>
	<?php

	$tmp = ob_get_clean();

	return $tmp;
}
add_shortcode( 'directory_search', 'tk_ud_search' );