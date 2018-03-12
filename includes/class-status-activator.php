<?php

/**
 * Fired during plugin activation
 *
 * @link       http://19h47.fr
 * @since      1.0.0
 *
 * @package    Status
 * @subpackage Status/includes
 */


/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Status
 * @subpackage Status/includes
 * @author     Jérémy Levron <jeremylevron@19h47.fr>
 */
class Status_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		if ( wp_next_scheduled( 'import_tweets_as_posts' ) ) return;

	    wp_schedule_event( time(), 'hourly', 'import_tweets_as_posts' );
	}
}
