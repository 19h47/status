<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link https://github.com/19h47/status
 * @since 1.0.0
 *
 * @package Status
 * @subpackage status/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package Status
 * @subpackage status/admin
 * @author Jérémy Levron <jeremylevron@19h47.fr> (http://19h47.fr)
 */
class Status_Admin {

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
	 * Register the stylesheets for the admin area.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function enqueue_styles() {
		global $typenow;

		if ( 'tweet' !== $typenow ) {
			return false;
		}

		wp_enqueue_style(
			$this->plugin_name,
			plugin_dir_url( __FILE__ ) . 'css/status-admin.css',
			array(),
			$this->version,
			'all'
		);

	}


	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function enqueue_scripts() {
		global $typenow;

		if ( 'tweet' !== $typenow ) {
			return false;
		}

		wp_enqueue_script(
			$this->plugin_name,
			plugin_dir_url( __FILE__ ) . 'js/status-admin.js',
			array( 'jquery' ),
			$this->version,
			false
		);
	}
}
