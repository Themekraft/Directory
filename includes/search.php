<?php

add_action( 'wp_loaded', array( 'TK_Ajax_Search', 'init' ) );

/**
 * Ajaxify the search form.
 */
class TK_Ajax_Search {
	/**
	 * The main instance. You can create further instances for unit tests.
	 * @type object
	 */
	protected static $instance = null;

	/**
	 * Action name used by AJAX callback handlers
	 * @type string
	 */
	protected $action = 'TK_Ajax_Search';

	/**
	 * Constructor. Registers the actions.
	 *
	 * @wp-hook wp_loaded
	 */
	public function __construct() {
		$callback = array( $this, 'search' );
		add_action( 'wp_ajax_' . $this->action, $callback );
		add_action( 'wp_ajax_nopriv_' . $this->action, $callback );
		add_action( 'wp_enqueue_scripts', array( $this, 'register_script' ) );
	}

	/**
	 * Handler for initial load.
	 *
	 * @wp-hook wp_loaded
	 * @return void
	 */
	public static function init() {
		null === self::$instance and self::$instance = new self;

		return self::$instance;
	}

	/**
	 * Callback for AJAX search.
	 *
	 * @wp-hook wp_ajax_TK_Ajax_Search
	 * @wp-hook wp_ajax_nopriv_TK_Ajax_Search
	 * @return void
	 */
	public function search() {
		global $tk_ud_search_query, $tk_ud_posts, $paged;

		$paged = isset( $_POST['paged'] ) ? $_POST['paged'] : 1;

		$search_term = isset( $_POST['search_term'] ) ? $_POST['search_term'] : '';
		$search_cat  = isset( $_POST['search_cat'] ) && $_POST['search_cat'] != -1  ? $_POST['search_cat'] : false;

		// Add the search string to the query
		$args = array(
			's'         => $search_term,
			'post_type' => 'ultimate_directory',
			'posts_per_page' => 10,
			'orderby' => 'title',
			'order' => 'ASC',
			'paged' => $paged,
		);

		// Add the category string to the query
		if ( $search_cat ) {
			$args['tax_query'][]['relation'] = 'AND';
			$args['tax_query'][] = array(
					'taxonomy' => 'directory_categories',
					'field'    => 'term_id',
					'terms'    => $search_cat,
					'operator' => 'AND'
			);
		}

		$args = apply_filters( 'tk_ud_ajax_search_args', $args );

 		$tk_ud_search_query = new WP_Query( $args );

		$GLOBALS['wp_query'] = $tk_ud_search_query;

		if ( $tk_ud_search_query ) {
			tk_ud_locate_template( 'search-loop' );
		} else {
			print __( 'Nothing found', 'tk_ud' );
		}
		exit;
	}

	/**
	 * Register script and local variables.
	 *
	 * @wp-hook wp_enqueue_scripts
	 * @return void
	 */
	public function register_script() {
		wp_enqueue_script(
			'tk-ud-ajax',
			plugins_url( '../assets/search.js', __FILE__ ),
			array( 'jquery' ),
			null,
			true
		);

		wp_localize_script(
			'tk-ud-ajax',
			'TK_UD_Ajax',
			array(
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'action'  => $this->action
			)
		);
	}
}