<?php

/*
 Plugin Name: Ultimate Directory
 Plugin URI: https://github.com/Themekraft/directory
 Description: Ultimate Directory
 Version: 0.1
 Author: Sven Lehnert
 Author URI: https://profiles.wordpress.org/svenl77
 License: GPLv2 or later
 Network: false

 *****************************************************************************
 *
 * This script is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 *
 ****************************************************************************
 */


class TK_Ultimate_Directory {

	public function __construct() {

		// Add an init hook to allow other plugins to hook into the init
		$this->init_hook();

		// Some constance needed to get files and templates and make them overwrite in the theme possible. tk_ud_INSTALL_PATH and tk_ud_INCLUDES_PATH
		$this->load_constants();

		// Load all needed files
		add_action( 'init', array( $this, 'includes' ), 1 );

		// Load the plugin translation files
		add_action( 'init', array( $this, 'load_plugin_textdomain' ), 10, 1 );

		// Admin js
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_js' ), 1, 1 );

		// Front css
		add_action( 'wp_enqueue_scripts', array( $this, 'front_js' ), 1002, 1 );

	}


	/**
	 * Defines TK_Ultimate_Directory action
	 *
	 * @package Ultimate Directory
	 * @since 0.1
	 */

	public function init_hook() {

		do_action( 'tk_ud_init' );

	}

	/**
	 * Defines constants needed throughout the plugin.
	 *
	 * @package Ultimate Directory
	 * @since 0.1
	 */

	public function load_constants() {

		if ( ! defined( 'TK_UD_INSTALL_PATH' ) ) {
			define( 'TK_UD_INSTALL_PATH', dirname( __FILE__ ) . '/' );
		}

		if ( ! defined( 'TK_UD_INCLUDES_PATH' ) ) {
			define( 'TK_UD_INCLUDES_PATH', TK_UD_INSTALL_PATH . 'includes/' );
		}
		if ( ! defined( 'TK_UD_TEMPLATES_PATH' ) ) {
			define( 'TK_UD_TEMPLATES_PATH', TK_UD_INSTALL_PATH . 'templates/' );
		}

	}

	/**
	 * Includes files needed by Ultimate Directory
	 *
	 * @package Ultimate Directory
	 * @since 0.1
	 */

	public function includes() {

		require_once( TK_UD_INCLUDES_PATH . '/admin/tk-ud-metabox.php' );
		require_once( TK_UD_INCLUDES_PATH . '/admin/tk-ud-admin.php' );
		require_once( TK_UD_INCLUDES_PATH . '/ultimate-directory.php' );
		require_once( TK_UD_INCLUDES_PATH . '/shortcodes.php' );
		require_once( TK_UD_INCLUDES_PATH . '/search.php' );

	}

	/**
	 * Loads the textdomain for the plugin
	 *
	 * @package Ultimate Directory
	 * @since 0.1
	 */

	public function load_plugin_textdomain() {

		load_plugin_textdomain( 'tk-um', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

	}

	/**
	 * Enqueue the needed JS for the admin screen
	 *
	 * @package Ultimate Directory
	 * @since 0.1
	 *
	 * @param $hook_suffix
	 */
	function admin_js( $hook_suffix ) {
		global $post;

//		if (
//			( isset( $post ) && $post->post_type == 'buddyforms' && isset( $_GET['action'] ) && $_GET['action'] == 'edit'
//			  || isset( $post ) && $post->post_type == 'buddyforms' && $hook_suffix == 'post-new.php' )
//			//|| isset($_GET['post_type']) && $_GET['post_type'] == 'buddyforms'
//			|| $hook_suffix == 'buddyforms-page-bf-add_ons'
//			|| $hook_suffix == 'buddyforms-page-bf-settings'
//			|| $hook_suffix == 'buddyforms-page-bf-submissions'
//			|| $hook_suffix == 'buddyforms_page_buddyforms-pricing'
//		) {
		wp_enqueue_script( 'admin', plugins_url( 'assets/admin/admin.js', __FILE__ ), array( 'jquery' ), '4.0.3' );
//		}

	}

	/**
	 * Enqueue the needed JS for the front
	 *
	 * @package Ultimate Directory
	 * @since 0.1
	 *
	 */
	function front_js() {
		wp_enqueue_script( 'multiple-select', plugins_url( 'assets/resources/multiple-select/multiple-select.js', __FILE__ ), array( 'jquery' ), '1.2.1' );
		wp_enqueue_style( 'multiple-select', plugins_url( 'assets/resources/multiple-select/multiple-select.css', __FILE__ ) );

	}


}

new TK_Ultimate_Directory;