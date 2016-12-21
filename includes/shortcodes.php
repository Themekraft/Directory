<?php

function tk_ud_search( $atts ) {

	ob_start();
	?>

	<div class="panel">
		<h2><?php _e( 'Search', 'tk_ud' ); ?></h2>
		<div id="tk-ud-search">
			<form role="search" method="get" id="tk-ud-searchform" action="" >
				<input type="text" value="" name="s" id="s" />
				<?php wp_dropdown_categories(array('taxonomy' => 'directory_categories', 'id' => 's-cat')); ?>
				<input type="text" value="" name="s_plz" minlength=5 id="s-plz" />
				<input type="number" value="" name="s_distance" id="s-distance" />
				<input type="submit" id="searchsubmit" value="Search" />
				<input type="reset" id="reset" value="Reset" />
			</form>
		</div>
		<div id="result"></div>
	</div>
	<?php

	$tmp = ob_get_clean();


//	$was = ogdbPLZnearby('40227','3', true, true);
//
//
//	echo '<pre>';
//	print_r( $was );
//	echo '</pre>';

	return $tmp;
}

add_shortcode( 'directory_search', 'tk_ud_search' );