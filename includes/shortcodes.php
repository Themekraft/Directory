<?php

// Search the directory
function tk_ud_search( $atts ) {

	ob_start();
	?>

	<div class="panel">
		<h2><?php _e( 'Search', 'tk_ud' ); ?></h2>
		<div id="tk-ud-search">
			<form role="search" method="get" id="tk-ud-searchform" action="">
				<input type="text" placeholder="Search" name="s" id="tk-ud-s"/>

				<?php wp_dropdown_categories( array( 'taxonomy' => 'directory_categories', 'id' => 'tk-ud-s-cat', 'show_option_none' => 'Category' ) ); ?>
				<input type="text" value="" placeholder="PLZ" name="s_plz" minlength=5 maxlength=5 id="tk-ud-s-plz"/>
				<input type="number" value="" placeholder="Distance" name="s_distance" id="tk-ud-s-distance"/>
				<input type="submit" id="searchsubmit" value="Search"/>
				<input type="reset" id="reset" value="Reset"/>
			</form>
		</div>
		<div id="result"></div>
	</div>
	<?php

	$tmp = ob_get_clean();

	return $tmp;
}
add_shortcode( 'directory_search', 'tk_ud_search' );