<?php

add_action( 'wp_loaded', array ( 'T5_Ajax_Search', 'init' ) );

/**
 * Ajaxify the search form.
 */
class T5_Ajax_Search
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
	protected $action = 't5_ajax_search';

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
	 * @wp-hook wp_ajax_t5_ajax_search
	 * @wp-hook wp_ajax_nopriv_t5_ajax_search
	 * @return void
	 */
	public function search()
	{
		$args  = array ( 's' => $_POST['search_term'] );
		$args  = apply_filters( 't5_ajax_search_args', $args );
		$posts = get_posts( $args );
		if ( $posts )
		{
			$this->render_search_results( $posts );
		}
		else
		{
			print '<b>nothing found</b>';
		}
		exit;
	}

	/**
	 * Create markup from $posts
	 *
	 * @param array $posts Array of post objects
	 * @return void
	 */
	protected function render_search_results( $posts )
	{
		print '<ul class="t5-ajax-search-results">';

		foreach ( $posts as $post )
		{
			printf(
				'<li><a href="%1$s">%2$s</a></li>',
				get_permalink( $post->ID ),
				esc_html( $post->post_title )
			);
		}
		print '</ul>';
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
			't5-ajax',
			plugins_url( '../assets/search.js', __FILE__ ),
			array ( 'jquery' ),
			NULL,
			TRUE
		);

		wp_localize_script(
			't5-ajax',
			'T5Ajax',
			array(
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'action'  => $this->action
			)
		);
	}
}

add_filter( 't5_ajax_search_args', 'restrict_t5_search' );

function restrict_t5_search( $args )
{
	$args['post_type'] = array ( 'ultimate_directory' );
	return $args;
}