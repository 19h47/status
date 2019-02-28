<?php
/**
 * The metabox-specific functionality of the plugin.
 *
 * @link https://github.com/19h47/status
 * @since 1.0.0
 *
 * @package Status
 * @subpackage Status/admin
 */

/**
 * The metabox-specific functionality of the plugin.
 *
 * @package Status
 * @subpackage Status/admin
 * @author Jérémy Levron <jeremylevron@19h47.fr> (http://19h47.fr)
 */
class Status_Admin_Metaboxes {

	/**
	 * The ID of this plugin.
	 *
	 * @since 1.0.0
	 * @access private
	 * @var str $plugin_name The ID of this plugin.
	 */
	private $plugin_name;


	/**
	 * The version of this plugin.
	 *
	 * @since 1.0.0
	 * @access private
	 * @var str $version The current version of this plugin.
	 */
	private $version;


	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 1.0.0
	 * @param str $plugin_name The name of this plugin.
	 * @param str $version The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;
	}


	/**
	 * Registers metaboxes with WordPress
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function add_metaboxes() {

		add_meta_box(
			'status_tweet_additional_information',
			apply_filters(
				$this->plugin_name . '_metabox_tweet_additional_information',
				esc_html__( 'Additional Information', 'status' )
			),
			array( $this, 'metabox' ),
			'tweet',
			'side',
			'default',
			array(
				'file' => 'tweet-additional-information',
			)
		);
	}


	/**
	 * Calls a metabox file specified in the add_meta_box args.
	 *
	 * @since 1.0.0
	 * @param obj $post The post object.
	 * @param arr $params Array of parameters.
	 * @access public
	 * @return void
	 */
	public function metabox( $post, $params ) {

		if ( ! is_admin() ) {
			return;
		}

		if ( 'tweet' !== $post->post_type ) {
			return;
		}

		include plugin_dir_path( __FILE__ ) . 'partials/status-admin-metabox-' . $params['args']['file'] . '.php';
	}
}
