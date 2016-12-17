<?php


//
// Add the Settings Page to the Settings Menu
//
function tk_ud_settings_menu() {

	add_submenu_page( 'edit.php?post_type=ultimate_directory', __( 'Settings', 'tk_ud' ), __( 'Settings', 'tk_pm' ), 'manage_options', 'tk_ud_settings', 'tk_ud_settings_page' );

}

add_action( 'admin_menu', 'tk_ud_settings_menu' );

//
// Settings Page Content
//
function tk_ud_settings_page() { ?>

	<div class="wrap">
		<div id="poststuff">
			<div id="post-body" class="metabox-holder columns-2">

				<div id="postbox-container-1" class="postbox-container">
					<?php tk_ud_settings_page_sidebar(); ?>
				</div>
				<div id="postbox-container-2" class="postbox-container">
					<?php tk_ud_settings_page_tabs_content(); ?>
				</div>
			</div> <!-- #post-body -->
		</div> <!-- #poststuff -->
	</div> <!-- .wrap -->
	<?php
}

/**
 * Settings Tabs Navigation
 *
 * @param string $current
 */
function tk_ud_admin_tabs( $current = 'homepage' ) {
	$tabs = array( 'general' => 'General Settings' );

	$tabs = apply_filters( 'tk_ud_admin_tabs', $tabs );

	echo '<h2 class="nav-tab-wrapper" style="padding-bottom: 0;">';
	foreach ( $tabs as $tab => $name ) {
		$class = ( $tab == $current ) ? ' nav-tab-active' : '';
		echo "<a class='nav-tab$class' href='?page=tk_ud_settings&tab=$tab'>$name</a>";
	}
	echo '</h2>';
}

/**
 * Register Settings Options
 *
 * @param string $current
 */
function tk_ud_register_option() {
	register_setting( 'tk_ud_buddyforms', 'tk_ud_buddyforms', 'tk_ud_buddyforms_sanitize' );
	register_setting( 'tk_ud_meta', 'tk_ud_meta', 'tk_ud_buddyforms_sanitize' );
}

add_action( 'admin_init', 'tk_ud_register_option' );

/**
 * @param $new
 *
 * @return mixed
 */
function tk_ud_buddyforms_sanitize( $new ) {
	// todo: Sanitize
	return $new;
}

function tk_ud_settings_page_tabs_content() { ?>

	<div id="poststuff">

		<?php

		// Display the Update Message
		if ( isset( $_GET['updated'] ) && 'true' == esc_attr( $_GET['updated'] ) ) {
			echo '<div class="updated" ><p>' . __( 'Settings Saved', 'tk-pm' ) . '</p></div>';
		}

		if ( isset ( $_GET['tab'] ) ) {
			tk_ud_admin_tabs( $_GET['tab'] );
		} else {
			tk_ud_admin_tabs( 'general' );
		}

		if ( $_GET['page'] == 'tk_ud_settings' ) {

			if ( isset ( $_GET['tab'] ) ) {
				$tab = $_GET['tab'];
			} else {
				$tab = 'general';
			}

			switch ( $tab ) {
				case 'general' :
					global $buddyforms;
					$tk_ud_buddyforms = get_option( 'tk_ud_buddyforms' ); ?>
					<div class="metabox-holder">
						<div class="postbox">
							<h3>
								<span><?php _e( 'Select the Form you like to use for the Front end Post management', 'tk-pm' ); ?></span>
							</h3>
							<div class="inside">
								<form method="post" action="options.php">

									<?php settings_fields( 'tk_ud_buddyforms' ); ?>
									<?php $tk_ud_buddyforms = get_option( 'tk_ud_buddyforms', true ); ?>
									<select name="tk_ud_buddyforms" >
									<?php if( isset( $buddyforms ) ){
										foreach ( $buddyforms as $buddyform ) {

											echo '<option ' . selected($tk_ud_buddyforms, $buddyform['slug']) . '  value="' . $buddyform['slug'] . '">' . $buddyform['name'] . '</option>';
										}
									}
									?>
									</select>
									<?php submit_button(); ?>
								</form>

								<script>

									jQuery(document).ready(function () {

										// User Accordion
										jQuery("#tk-pu-sortable").sortable({
											revert: true
										});
										jQuery("#draggable").draggable({
											connectToSortable: "#sortable",
											helper: "clone",
											revert: "invalid"
										});
										jQuery("ul, li").disableSelection();
									});
								</script>
								<form method="post" action="options.php">
									<?php
									$tk_ud_meta = get_option( 'tk_ud_meta' );
									settings_fields( 'tk_ud_meta' ); ?>
									<ul id="tk-pu-sortable">
									<?php
									if ( isset( $buddyforms[ $tk_ud_buddyforms ][ 'form_fields' ] ) && is_array( $buddyforms[ $tk_ud_buddyforms ][ 'form_fields' ] ) ) { ?>
										<?php foreach ( $buddyforms[ $tk_ud_buddyforms ][ 'form_fields' ] as $field ) { ?>

											<li id="<?php echo $field['slug'] ?>">

												<div class="menu-item-bar">
													<div class="menu-item-handle ui-sortable-handle">
														<span class="item-title"><span class="menu-item-title"><?php echo $field['name'] ?></span> <span class="is-submenu" style="display: none;">sub item</span></span>
														<span class="item-controls">
															<span class="item-type">
																<input type="checkbox" name="tk_ud_meta[<?php echo $field['slug'] ?>][loop]" value="<?php echo $field['slug'] ?>"> Add to loop
																<input type="checkbox" name="tk_ud_meta[<?php echo $field['slug'] ?>][single]" value="<?php echo $field['slug'] ?>"> Add to Single
															</span>
														</span>
													</div>
												</div>
											</li>
											<?php
										}
									}
									?><?php submit_button(); ?>
										</form>
								</ul>

							</div><!-- .inside -->
						</div><!-- .postbox -->
					</div><!-- .metabox-holder -->
					<?php
					break;
				default:
					do_action( 'tk_ud_settings_page_tab', $tab );
					break;
			}
		}
		?>
	</div> <!-- #poststuff -->
	<?php
}

function tk_ud_settings_page_sidebar() {
}