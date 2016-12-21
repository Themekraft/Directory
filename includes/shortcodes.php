<?php

function tk_ud_search( $atts ) {

	ob_start();
	?>

	<div class="panel">
		<h2>Search Videos</h2>
		<div id="my-search">
			<form role="search" method="get" id="searchform" action="" >
				<input type="text" value="" name="s[]" id="s" />
				<?php wp_dropdown_categories(array('taxonomy' => 'directory_categories')); ?>
				<input type="number" value="" name="s[]" id="plz" />
				<input type="number" value="" name="s[]" id="umkreis" />
				<input type="submit" id="searchsubmit" value="Search" />
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