<?php
/**
 * Class Status Admin Connection
 *
 * @link       https://github.com/19h47/status
 * @since      1.0.0
 *
 * @package    Status
 * @subpackage Status/admin
 */

/**
 * Twitter OAuth REST API
 *
 * @see  https://github.com/abraham/twitteroauth
 */
use Abraham\TwitterOAuth\TwitterOAuth;


/**
 * Status Admin Connection class
 *
 * @package Status
 * @subpackage Status/admin
 * @author Jérémy Levron <jeremylevron@19h47.fr> (http://19h47.fr)
 */
class Status_Admin_Connection {

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
	 * @param str $version     The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version     = $version;
	}


	/**
	 * Connection
	 *
	 * @access public
	 * @param arr $config Array of config.
	 * @return arr $content
	 */
	public static function connection( $config ) {
		$post_tweet_id = null;

		$connection = new TwitterOAuth(
			$config['consumer']['key'],
			$config['consumer']['secret'],
			$config['access']['token'],
			$config['access']['token_secret']
		);

		$posts = get_posts(
			array(
				'meta_key'       => '_tweet_id',
				'order'          => 'DESC',
				'post_status'    => 'any',
				'post_type'      => 'tweet',
				'posts_per_page' => 1,
			)
		);

		if ( $posts ) {
			foreach ( $posts as $post ) {
				$post_tweet_id = get_post_meta( $post->ID, '_tweet_id', true );
			}
		}

		$user = $connection->get( 'users/show', [ 'screen_name' => $config['screen_name'] ] );

		$args_content = array(
			'screen_name' => $config['screen_name'],
			'count'       => $user->statuses_count,
			'include_rts' => false,
		);

		if ( null !== $post_tweet_id ) {
			array_push( $args_content, array( 'since_id' => $post_tweet_id ) );
		}

		$content = $connection->get( 'statuses/user_timeline', $args_content );

		return $content;
	}
}
