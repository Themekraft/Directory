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