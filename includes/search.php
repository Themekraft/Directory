<?php

add_action( 'wp_loaded', array ( 'TK_Ajax_Search', 'init' ) );

/**
 * Ajaxify the search form.
 */
class TK_Ajax_Search
{
	/**
	 * The main instance. You can create further instances for unit tests.
	 * @type object
	 */
	protected static $instance = NULL;

	/**
	 * Action name used by AJAX callback handlers
	 * @type string
	 */
	protected $action = 'TK_Ajax_Search';

	/**
	 * Handler for initial load.
	 *
	 * @wp-hook wp_loaded
	 * @return void
	 */
	public static function init()
	{
		NULL === self::$instance and self::$instance = new self;
		return self::$instance;
	}

	/**
	 * Constructor. Registers the actions.
	 *
	 *  @wp-hook wp_loaded
	 *  @return object
	 */
	public function __construct()
	{
		$callback = array ( $this, 'search' );
		add_action( 'wp_ajax_'        . $this->action,        $callback );
		add_action( 'wp_ajax_nopriv_' . $this->action, $callback );
		add_action( 'wp_enqueue_scripts', array ( $this, 'register_script' ) );
	}

	/**
	 * Callback for AJAX search.
	 *
	 * @wp-hook wp_ajax_TK_Ajax_Search
	 * @wp-hook wp_ajax_nopriv_TK_Ajax_Search
	 * @return void
	 */
	public function search()
	{
		global $posts;


		$search_term = isset($_POST['search_term']) ? $_POST['search_term'] : '';

		$plzs = false;
		if( isset($_POST['search_plz']) ){
			$search_plz = $_POST['search_plz'];
			$search_distance = isset($_POST['search_distance']) ? $_POST['search_distance'] : 0;
			$plzs = ogdbPLZnearby($search_plz, $search_distance);
		}

		$search_cat = isset($_POST['search_cat']) ? $_POST['search_cat'] : false;




		// Add the search string to the query
		$args  = array (
			's' => $search_term,
			'post_type' => 'ultimate_directory',
		);

		// Add the plzs string to the query
		if($plzs){
			$plz_str = implode(',',$plzs);
			$args['directory_plz'] = $plz_str;
		}

		// Add the category string to the query
		if($search_cat){
			$search_cat_str = implode(',',$search_cat);
			$args['directory_categories'] = $search_cat_str;
		}

		$args  = apply_filters( 'TK_Ajax_Search_args', $args );

		$posts = get_posts( $args );
		if ( $posts )
		{
			tk_ud_locate_template('search-loop');
		}
		else
		{
			print __('Nothing found' , 'tk_ud');
		}
		exit;
	}


	/**
	 * Register script and local variables.
	 *
	 * @wp-hook wp_enqueue_scripts
	 * @return void
	 */
	public function register_script()
	{
		wp_enqueue_script(
			'tk-ud-ajax',
			plugins_url( '../assets/search.js', __FILE__ ),
			array ( 'jquery' ),
			NULL,
			TRUE
		);

		wp_localize_script(
			'tk-ud-ajax',
			'T5Ajax',
			array(
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'action'  => $this->action
			)
		);
	}
}

//add_filter( 'TK_Ajax_Search_args', 'restrict_t5_search' );
//
//function restrict_t5_search( $args )
//{
//	$args['post_type'] = array ( 'ultimate_directory' );
//	return $args;
//}

add_action( 'init', 'tk_ud_register_search_plz' );
function tk_ud_register_search_plz() {
	$labels = array(
		"name" => __( 'PLZ', 'tk_ud' ),
	);

	$args = array(
		"label" => __( 'PLZ', 'tk_ud' ),
		"labels" => $labels,
		"public" => true,
		"hierarchical" => false,
		"label" => "PLZ",
		"show_ui" => true,
		"show_in_menu" => true,
		"show_in_nav_menus" => true,
		"query_var" => true,
		"rewrite" => array( 'slug' => 'plz', 'with_front' => true,  'hierarchical' => false, ),
		"show_admin_column" => true,
		"show_in_rest" => true,
		"rest_base" => "directory-plz",
		"show_in_quick_edit" => true,
	);
	register_taxonomy( "directory_plz", array( "ultimate_directory" ), $args );

// End tk_ud_register_categories()
}
