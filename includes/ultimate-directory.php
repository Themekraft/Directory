<?php

// Create the post type
add_action( 'init', 'tk_ud_register_post_type' );
function tk_ud_register_post_type() {
	$labels = array(
		"name"          => __( 'Directories', 'tk_ud' ),
		"singular_name" => __( 'Directory', 'tk_ud' ),
	);

	$args = array(
		"label"               => __( 'Directories', 'tk_ud' ),
		"labels"              => $labels,
		"description"         => "",
		"public"              => true,
		"publicly_queryable"  => true,
		"show_ui"             => true,
		"show_in_rest"        => true,
		"rest_base"           => "",
		"has_archive"         => true,
		"show_in_menu"        => true,
		"exclude_from_search" => false,
		"capability_type"     => "post",
		"map_meta_cap"        => true,
		"hierarchical"        => true,
		"rewrite"             => array( "slug" => "directory", "with_front" => true ),
		"query_var"           => true,

		"supports"   => array( "title", "editor", "thumbnail", "comments", "revisions", "author", "page-attributes" ),
		"taxonomies" => array( "directory_categories" ),
	);
	register_post_type( "ultimate_directory", $args );

// End of tk_ud_register_post_type()
}

// Create the taxonomy category
add_action( 'init', 'tk_ud_register_categories' );
function tk_ud_register_categories() {
	$labels = array(
		"name"          => __( 'Categories', 'tk_ud' ),
		"singular_name" => __( 'Category', 'tk_ud' ),
	);

	$args = array(
		"label"              => __( 'Categories', 'tk_ud' ),
		"labels"             => $labels,
		"public"             => true,
		"hierarchical"       => true,
		"label"              => "Categories",
		"show_ui"            => true,
		"show_in_menu"       => true,
		"show_in_nav_menus"  => true,
		"query_var"          => true,
		"rewrite"            => array( 'slug' => 'directory-category', 'with_front' => true, 'hierarchical' => true, ),
		"show_admin_column"  => true,
		"show_in_rest"       => true,
		"rest_base"          => "directory-category",
		"show_in_quick_edit" => true,
	);
	register_taxonomy( "directory_categories", array( "ultimate_directory" ), $args );

// End tk_ud_register_categories()
}

// Create the taxonomy tags
add_action( 'init', 'tk_ud_register_tags' );
function tk_ud_register_tags() {
	$labels = array(
		"name"          => __( 'Tags', 'tk_ud' ),
		"singular_name" => __( 'tag', 'tk_ud' ),
	);

	$args = array(
		"label"              => __( 'Tags', 'tk_ud' ),
		"labels"             => $labels,
		"public"             => true,
		"hierarchical"       => false,
		"label"              => "Tags",
		"show_ui"            => true,
		"show_in_menu"       => true,
		"show_in_nav_menus"  => true,
		"query_var"          => true,
		"rewrite"            => array( 'slug' => 'diretory_tags', 'with_front' => true, ),
		"show_admin_column"  => false,
		"show_in_rest"       => false,
		"rest_base"          => "",
		"show_in_quick_edit" => false,
	);
	register_taxonomy( "diretory_tags", array( "ultimate_directory" ), $args );

// End tk_ud_register_tags()
}

// Add Fields to the content and or excerpt
add_filter( 'the_content', 'tk_ud_content_filter', 20 );
add_filter( 'the_excerpt', 'tk_ud_content_filter', 20 );
/**
 * Add a icon to the beginning of every post page.
 *
 * @uses is_single()
 */
function tk_ud_content_filter( $content ) {
	global $post;

	if ( is_single() ) {
		if ( $post->post_type == 'ultimate_directory' ) {
			$content .= tk_ud_display_meta( 'single' );
		}
	}

	if ( is_archive() ) {
		if ( $post->post_type == 'ultimate_directory' ) {
			$content = tk_ud_display_meta( 'loop' );
		}
	}

	// Returns the content.
	return $content;
}


// Display post meta
function tk_ud_display_meta( $type = 'single' ) {
	global $buddyforms, $post;

	if ( !( is_admin() && defined( 'DOING_AJAX' ) && DOING_AJAX ) ){
		return;
	}

	if ( ! isset( $post->ID ) ) {
		return;
	}

	$form_slug = get_option( 'tk_ud_form_slug' );

	if ( ! isset( $form_slug ) ) {
		return;
	}

	if ( ! isset( $buddyforms[ $form_slug ] ) ) {
		return;
	}

	if ( ! isset( $buddyforms[ $form_slug ]['form_fields'] ) ) {
		return;
	}

	$tk_ud_meta = get_option( 'tk_ud_meta' );

	$post_meta_tmp = '';

	if ( isset( $tk_ud_meta[ $type ] ) ) {
		foreach ( $tk_ud_meta[ $type ] as $key => $meta ) {

			$customfield = buddyforms_get_form_field_by_slug( $form_slug, $meta['slug'] );

			if ( ! isset( $customfield ) ) {
				continue;
			}

			if ( ! empty( $customfield['slug'] ) ) {

				$customfield_value = get_post_meta( $post->ID, $customfield['slug'], true );

				// if ( ! empty( $customfield_value ) ) {
					$post_meta_tmp .= '<div class="post_meta ' . $customfield['slug'] . '">';

					$post_meta_tmp .= '<label>' . $customfield['name'] . '</label>';

					if ( is_array( $customfield_value ) ) {
						$meta_tmp = "<p>" . implode( ',', $customfield_value ) . "</p>";
					} else {
						$meta_tmp = "<p>" . $customfield_value . "</p>";
					}

					switch ( $customfield['type'] ) {
						case 'taxonomy':
							$meta_tmp = get_the_term_list( $post->ID, $customfield['taxonomy'], "<p>", ' - ', "</p>" );
							break;
						case 'user_website':
							$meta_tmp = "<p><a href='" . $customfield_value . "' " . $customfield['name'] . ">" . $customfield_value . " </a></p>";
							break;
						default:
							$meta_tmp = $customfield_value;
							break;
					}
					$post_meta_tmp .= '</div>';
					if ( $meta_tmp ) {
						$post_meta_tmp .= $meta_tmp;
					}

				// }
			}

		}
	}

	return $post_meta_tmp;

}

// Locate a template
function tk_ud_locate_template( $slug ) {
	global $tk_ud_search_query, $paged;

	// create the plugin template path
	$template_path = TK_UD_TEMPLATES_PATH . $slug . '.php';

	// Check if template exist in the child or parent theme and use this path if available
	if ( $template_file = locate_template( "tk_ud/{$slug}.php", false, false ) ) {
		$template_path = $template_file;
	}

	// Do the include
	include( $template_path );

}

// Get field by slug
function buddyforms_get_form_field_by_slug( $form_slug, $slug ) {
	global $buddyforms;

	if ( isset( $buddyforms[ $form_slug ]['form_fields'] ) ) : foreach ( $buddyforms[ $form_slug ]['form_fields'] as $field_key => $field ) {
		if ( $field['slug'] == $slug ) {
			return $field;
		}
	} endif;

	return false;
}
