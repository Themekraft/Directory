<?php

add_action( 'init', 'tk_ud_register_post_type' );
function tk_ud_register_post_type() {
	$labels = array(
		"name" => __( 'Directories', 'tk_ud' ),
		"singular_name" => __( 'Directory', 'tk_ud' ),
	);

	$args = array(
		"label" => __( 'Directories', 'tk_ud' ),
		"labels" => $labels,
		"description" => "",
		"public" => true,
		"publicly_queryable" => true,
		"show_ui" => true,
		"show_in_rest" => true,
		"rest_base" => "",
		"has_archive" => true,
		"show_in_menu" => true,
		"exclude_from_search" => false,
		"capability_type" => "post",
		"map_meta_cap" => true,
		"hierarchical" => true,
		"rewrite" => array( "slug" => "directory", "with_front" => true ),
		"query_var" => true,

		"supports" => array( "title", "editor", "thumbnail", "comments", "revisions", "author", "page-attributes" ),
		"taxonomies" => array( "directory_categories" ),
	);
	register_post_type( "ultimate_directory", $args );

// End of tk_ud_register_post_type()
}


add_action( 'init', 'tk_ud_register_categories' );
function tk_ud_register_categories() {
	$labels = array(
		"name" => __( 'Categories', 'tk_ud' ),
		"singular_name" => __( 'Category', 'tk_ud' ),
	);

	$args = array(
		"label" => __( 'Categories', 'tk_ud' ),
		"labels" => $labels,
		"public" => true,
		"hierarchical" => true,
		"label" => "Categories",
		"show_ui" => true,
		"show_in_menu" => true,
		"show_in_nav_menus" => true,
		"query_var" => true,
		"rewrite" => array( 'slug' => 'directory-category', 'with_front' => true,  'hierarchical' => true, ),
		"show_admin_column" => true,
		"show_in_rest" => true,
		"rest_base" => "directory-category",
		"show_in_quick_edit" => true,
	);
	register_taxonomy( "directory_categories", array( "ultimate_directory" ), $args );

// End tk_ud_register_categories()
}


add_action( 'init', 'tk_ud_register_tags' );
function tk_ud_register_tags() {
	$labels = array(
		"name" => __( 'Tags', 'tk_ud' ),
		"singular_name" => __( 'tag', 'tk_ud' ),
	);

	$args = array(
		"label" => __( 'Tags', 'tk_ud' ),
		"labels" => $labels,
		"public" => true,
		"hierarchical" => false,
		"label" => "Tags",
		"show_ui" => true,
		"show_in_menu" => true,
		"show_in_nav_menus" => true,
		"query_var" => true,
		"rewrite" => array( 'slug' => 'diretory_tags', 'with_front' => true, ),
		"show_admin_column" => false,
		"show_in_rest" => false,
		"rest_base" => "",
		"show_in_quick_edit" => false,
	);
	register_taxonomy( "diretory_tags", array( "ultimate_directory" ), $args );

// End tk_ud_register_tags()
}







add_filter( 'the_content', 'my_the_content_filter', 20 );
/**
 * Add a icon to the beginning of every post page.
 *
 * @uses is_single()
 */
function my_the_content_filter( $content ) {
	global $post;

	if ( is_single() ){
		if($post->post_type == 'ultimate_directory'){
			// Add image to the beginning of each page
			$content = tk_ud_display_meta();

		}
	}

	// Returns the content.
	return $content;
}

function tk_ud_display_meta() {
	global $buddyforms, $post;

	if ( is_admin() ) {
		return;
	}

	if ( ! isset( $post->ID ) ) {
		return;
	}

	$form_slug = 'directory';//get_post_meta( $post->ID, '_bf_form_slug', true );

	if ( ! isset( $form_slug ) ) {
		return;
	}

	if ( ! isset( $buddyforms[ $form_slug ] ) ) {
		return;
	}

	if ( ! isset( $buddyforms[ $form_slug ]['form_fields'] ) ) {
		return;
	}

	$post_meta_tmp = '';

	foreach ( $buddyforms[ $form_slug ]['form_fields'] as $key => $customfield ) :

		if ( ! empty( $customfield['slug'] ) ) {

			$customfield_value = get_post_meta( $post->ID, $customfield['slug'], true );

			if ( ! empty( $customfield_value ) ) {
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
					case 'link':
						$meta_tmp = "<p><a href='" . $customfield_value . "' " . $customfield['name'] . ">" . $customfield_value . " </a></p>";
						break;
					default:
						//$meta_tmp = $customfield_value;
						break;
				}
				$post_meta_tmp .= '</div>';
				if ( $meta_tmp ) {
					$post_meta_tmp .= $meta_tmp;
				}

			}
		}

	endforeach;

	return $post_meta_tmp;

}
































//add_filter('single_template','booklistTpl_single');
//add_filter('archive_template','booklistTpl_archive');


//route single- template
function booklistTpl_single($single_template){
	global $post;
	$found = locate_template('single-ultimate-directory.php');
	if($post->post_type == 'ultimate_directory' && $found == ''){
		$single_template = TK_UD_TEMPLATES_PATH . 'single-ultimate-directory.php';
	}
	return $single_template;
}

//route archive- template
function booklistTpl_archive($template){
	if(is_post_type_archive('ultimate_directory')){
		$theme_files = array('archive-ultimate-directory.php');
		$exists_in_theme = locate_template($theme_files, false);
		if($exists_in_theme == ''){
			return TK_UD_TEMPLATES_PATH . 'archive-ultimate-directory.php';
		}
	}
	return $template;
}

/**
 * Locate a template
 *
 * @package BuddyForms
 * @since 0.1 beta
 *
 * @param $slug
 */
function aaabuddyforms_locate_template( $slug ) {
	global $buddyforms, $bp, $the_lp_query, $current_user, $form_slug, $post_id;

	// Get the current user so its not needed in the templates
	$current_user  = wp_get_current_user();

	// create the plugin template path
	$template_path = BUDDYFORMS_TEMPLATE_PATH .'buddyforms/'. $slug . '.php';

	// Check if template exist in the child or parent theme and use this path if available
	if ( $template_file = locate_template( "buddyforms/{$slug}.php", false, false)) {
		$template_path = $template_file;
	}

	// Do the include
	include( $template_path );

}