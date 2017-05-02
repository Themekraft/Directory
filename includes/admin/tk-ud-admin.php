<?php

/**
 * Add the Settings Page to the Directory Post Type
 *
 * @package Ultimate Directory
 * @since 0.1
 */
function tk_ud_settings_menu() {
	add_submenu_page( 'edit.php?post_type=ultimate_directory', __( 'Settings', 'tk_ud' ), __( 'Settings', 'tk_pm' ), 'manage_options', 'tk_ud_settings', 'tk_ud_settings_page' );
}

add_action( 'admin_menu', 'tk_ud_settings_menu' );

/**
 * Settings Page Content
 *
 * @package Ultimate Directory
 * @since 0.1
 */
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
 *
 * @package Ultimate Directory
 * @since 0.1
 */
function tk_ud_admin_tabs( $current = 'homepage' ) {
	$tabs = array( 'general' => 'General Settings', 'loop' => 'The Loop', 'single' => 'Post Single View', 'search' => 'Search' );

	$tabs = apply_filters( 'tk_ud_admin_tabs', $tabs );

	echo '<h2 class="nav-tab-wrapper" style="padding-bottom: 0;">';
	foreach ( $tabs as $tab => $name ) {
		$class = ( $tab == $current ) ? ' nav-tab-active' : '';
		echo "<a class='nav-tab$class' href='?post_type=ultimate_directory&page=tk_ud_settings&tab=$tab'>$name</a>";
	}
	echo '</h2>';
}

/**
 * Register Settings Options
 *
 * @param string $current
 */
function tk_ud_register_option() {
	register_setting( 'tk_ud_options', 'tk_ud_options', 'tk_ud_form_slug_sanitize' );
	register_setting( 'tk_ud_meta', 'tk_ud_meta', 'tk_ud_form_slug_sanitize' );
	register_setting( 'tk_ud_search', 'tk_ud_search', 'tk_ud_form_slug_sanitize' );
}

add_action( 'admin_init', 'tk_ud_register_option' );

/**
 * @param $new
 *
 * @return mixed
 */
function tk_ud_form_slug_sanitize( $new ) {
	// todo: Sanitize all please!!!
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
					global $buddyforms; ?>
					<div class="metabox-holder">
						<div class="postbox">
							<h2><?php _e( 'Select the Form you like to use for the Front end Post management', 'tk-pm' ); ?></h2>
							<div class="inside">
								<form method="post" action="options.php">
									<?php settings_fields( 'tk_ud_options' ); ?>
									<?php $tk_ud_options = get_option( 'tk_ud_options' );

									?>

									<select id="tk_ud_form_slug" name="tk_ud_options[form_slug]">
										<?php if ( isset( $buddyforms ) ) {
											foreach ( $buddyforms as $buddyform ) {
												echo '<option ' . selected( $tk_ud_options['form_slug'], $buddyform['slug'] ) . '  value="' . $buddyform['slug'] . '">' . $buddyform['name'] . '</option>';
											}
										}
										?>
									</select>


									<label for="tk_ud_directory_slug"><p><b>Directory Slug</b></p></label>
									<input id="tk_ud_directory_slug" name="tk_ud_options[directory_slug]" type="text" value="<?php echo empty( $tk_ud_options['directory_slug'] ) || !empty( $tk_ud_options['directory_slug'] ) && $tk_ud_options['directory_slug'] == '1' ? "directory" : $tk_ud_options['directory_slug'] ?>">

									<?php submit_button(); ?>
								</form>
							</div><!-- .inside -->
						</div><!-- .postbox -->
					</div><!-- .metabox-holder -->
					<?php
					break;
				case 'loop' :
					?>
					<div class="metabox-holder">
						<div class="postbox">
							<h2><?php _e( 'Select the Form you like to use for the Front end Post management', 'tk-pm' ); ?></span></h2>
							<div class="inside">

								<form method="post" action="options.php">

									<?php settings_fields( 'tk_ud_meta' ); ?>

									<div style="display:none;">
										<?php tk_ud_get_field_list( 'single' ); ?>
									</div>

									<label for="form-fields-select"><p><b>Add form element's to the Loop</b></p></label>
									<?php tk_ud_get_field_list( 'loop' ); ?>

									<?php submit_button(); ?>
								</form>

							</div><!-- .inside -->
						</div><!-- .postbox -->
					</div><!-- .metabox-holder -->
					<?php
					break;
				case 'single' :
					?>
					<div class="metabox-holder">
						<div class="postbox">
							<h2><?php _e( 'Select the Form you like to use for the Front end Post management', 'tk-pm' ); ?></h2>
							<div class="inside">
								<form method="post" action="options.php">

									<?php settings_fields( 'tk_ud_meta' ); ?>

									<div style="display:none;">
										<?php tk_ud_get_field_list( 'loop' ); ?>
									</div>

									<label for="form-fields-select"><p><b>Add form element's to the Single</b></p>
									</label>
									<?php tk_ud_get_field_list( 'single' ); ?>

									<?php submit_button(); ?>
								</form>

							</div><!-- .inside -->
						</div><!-- .postbox -->
					</div><!-- .metabox-holder -->
					<?php
					break;
				case 'search' :
					?>
					<div class="metabox-holder">
						<div class="postbox">
							<h2><?php _e( 'Search Setup', 'tk-pm' ); ?></h2>
							<div class="inside">
								<form method="post" action="options.php">

									<?php settings_fields( 'tk_ud_search' ); ?>
									<?php $tk_ud_search = get_option( 'tk_ud_search', true ); ?>

									<label for="display_category_filter"><p><b>Display the category filter</b></p></label>
									<select id="display_category_filter" name="tk_ud_search[display_category_filter]">
										<option <?php selected($tk_ud_search['display_category_filter'], 'yes') ?> value="yes">Yes</option>
										<option <?php selected($tk_ud_search['display_category_filter'], 'no') ?> value="no">No</option>
									</select>

									<label for="category_filter_select_all"><p><b>Enable select all in the category filter</b></p></label>
									<select id="category_filter_select_all" name="tk_ud_search[category_filter_select_all]">
										<option <?php selected($tk_ud_search['category_filter_select_all'], 'yes') ?> value="yes">Yes</option>
										<option <?php selected($tk_ud_search['category_filter_select_all'], 'no') ?> value="no">No</option>
									</select>
									<hr>
									<label for="display_submit"><p><b>Display the Submit Button</b></p></label>
									<select id="display_submit" name="tk_ud_search[display_submit]">
										<option <?php selected($tk_ud_search['display_submit'], 'yes') ?> value="yes">Yes</option>
										<option <?php selected($tk_ud_search['display_submit'], 'no') ?> value="no">No</option>
									</select>

									<label for="display_reset"><p><b>Display the Reset Button</b></p></label>
									<select id="display_reset" name="tk_ud_search[display_reset]">
										<option <?php selected($tk_ud_search['display_reset'], 'yes') ?> value="yes">Yes</option>
										<option <?php selected($tk_ud_search['display_reset'], 'no') ?> value="no">No</option>
									</select>
									<?php do_action('tk_ud_search_settings', $tk_ud_search); ?>
									<?php submit_button(); ?>
								</form>

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

function tk_ud_get_field_list( $type = 'single' ) {
	global $buddyforms;




	$tk_ud_options   = get_option( 'tk_ud_options' );
	$tk_ud_meta      = get_option( 'tk_ud_meta' );

	$tk_ud_form_slug = $tk_ud_options['form_slug'];

	echo $tk_ud_form_slug;

	?>

	<ul id="tk-pu-<?php echo $type ?>" class="tk-pu-sortable">
		<?php if ( isset( $tk_ud_meta[ $type ] ) && is_array( $tk_ud_meta[ $type ] ) ) {
			foreach ( $tk_ud_meta[ $type ] as $field ) {

				$customfield = buddyforms_get_form_field_by_slug( $tk_ud_form_slug, $field['slug'] );

				if ( ! $customfield )
					continue
				?>

				<li id="<?php echo $customfield['slug'] ?>">
					<div class="menu-item-bar">
						<div class="menu-item-handle ui-sortable-handle">
							<span class="item-title"><span
									class="menu-item-title"><?php echo $customfield['name'] ?></span> <span
									class="is-submenu" style="display: none;">sub item</span></span>
							<span class="item-controls">
								<span class="item-type">
									<input type="hidden"
									       name="tk_ud_meta[<?php echo $type ?>][<?php echo $customfield['slug'] ?>][slug]"
									       value="<?php echo $customfield['slug'] ?>">
									<input type="hidden"
									       name="tk_ud_meta[<?php echo $type ?>][<?php echo $customfield['name'] ?>][slug]"
									       value="<?php echo $customfield['name'] ?>">
									<input type="checkbox"
									       name="tk_ud_meta[<?php echo $type ?>][<?php echo $customfield['slug'] ?>][view_label]"
									       value="view"> View Label
								</span>
								<a href="#" data-type="<?php echo $type ?>"
								   data-slug="<?php echo $customfield['slug'] ?>" class="tk-ud-delete-meta">Delete</a>
							</span>
						</div>
					</div>
				</li>
				<?php
			}
		} else {
			echo 'No Fields to display so far. Add some now! ';
		}
		?>
	</ul>
	<select data-type="<?php echo $type ?>" class="form-fields-select">
		<option value="none">Select a form field to add it to the <?php echo $type ?> list</option>
		<?php
		if ( isset( $buddyforms[ $tk_ud_form_slug ]['form_fields'] ) && is_array( $buddyforms[ $tk_ud_form_slug ]['form_fields'] ) ) {
			foreach ( $buddyforms[ $tk_ud_form_slug ]['form_fields'] as $field ) {
				echo '<option data-name="' . $field["name"] . '" value="' . $field["slug"] . '">' . $field["name"] . '</option>';
			}
		}
		?>
	</select>

	<?php
}