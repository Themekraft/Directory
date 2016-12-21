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
		$args  = array ( 's' => $_POST['search_term'] );
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

add_filter( 'TK_Ajax_Search_args', 'restrict_t5_search' );

function restrict_t5_search( $args )
{
	$args['post_type'] = array ( 'ultimate_directory' );
	return $args;
}