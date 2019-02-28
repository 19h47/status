<?php
/**
 * Insert post
 *
 * @link       https://github.com/19h47/status
 * @since      1.0.0
 *
 * @package    Status
 * @subpackage Status/admin
 */

/**
 * Insert post
 *
 * @package    Status
 * @subpackage Status/admin
 * @author     Jérémy Levron <jeremylevron@19h47.fr>
 */
class Status_Admin_Insert_Post {

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
	 * Tweets
	 *
	 * @since  1.0.0
	 * @access private
	 * @var arr
	 */
	private $tweets;


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
	 * Insert post.
	 *
	 * @param arr $tweets Array of tweets.
	 * @access public
	 */
	public function insert_post( $tweets ) {
		if ( ! $tweets ) {
			return false;
		}

		foreach ( $tweets as $tweet ) {

			$post_exist = get_posts(
				array(
					'post_type'   => 'tweet',
					'post_status' => 'any',
					'meta_key'    => '_tweet_id',
					'meta_value'  => $tweet->id,
				)
			);

			if ( $post_exist ) {
				// Do Nothing.
				continue;
			}

			$tweet_text = $this->text( $tweet->text );
			$tweet_text = $this->follow( $tweet_text );
			$post_title = $this->title( $tweet->text );

			foreach ( $tweet->entities->hashtags as $hashtag ) {
				$find_pattern = '/#' . $hashtag->text . '/';
				$url          = 'https://twitter.com/hashtag/' . $hashtag->text . '?src=hash';
				$replace      = '<a href="' . $url . '" target="_blank">#' . $hashtag->text . '</a>';
				$tweet_text   = preg_replace( $find_pattern, $replace, $tweet_text );
			}

			$date = date_i18n(
				'Y-m-d H:i:s',
				strtotime( $tweet->created_at ) + $tweet->user->utc_offset
			);

			// Post array.
			$postarr = array(
				'post_author'       => 1,
				'post_content'      => $tweet_text,
				'post_date'         => $date,
				'post_date_gmt'     => $date,
				'post_modified'     => $date,
				'post_modified_gmt' => $date,
				'post_title'        => $post_title,
				'post_type'         => 'tweet',
			);
			$post_id = wp_insert_post( $postarr, true );

			// Hashtags.
			foreach ( $this->hashtags( $tweet ) as $hashtag ) {
				wp_set_object_terms( $post_id, $hashtag, 'hashtag', true );
			}

			$this->insert_tweet_media( $tweet, $post_id );

			// Tweet's Original URL.
			$tweet_url = 'https://twitter.com/' . $tweet->user->screen_name . '/status/' . $tweet->id;

			update_post_meta( $post_id, '_tweet_id', $tweet->id );
			update_post_meta( $post_id, '_tweet_url', $tweet_url );
		}
	}


	/**
	 * Text
	 *
	 * @param str $tweet_text The tweet text.
	 * @author Jérémy Levron <jeremylevron@19h47.fr> (http://19h47.fr)
	 * @access public
	 */
	public function text( $tweet_text ) {
		// Convert url to HTML link.
		$link_pattern = '/(http|https)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/[^\s\…\.]*)?/';
		$link_replace = '<a href="${0}" target="_blank">${0}</a>';

		return preg_replace( $link_pattern, $link_replace, $tweet_text );
	}


	/**
	 * Follow
	 *
	 * @param  str $tweet_text The tweet text.
	 * @author  Jérémy Levron <jeremylevron@19h47.fr> (http://19h47.fr)
	 * @access public
	 */
	public function follow( $tweet_text ) {
		// Convert @ to follow.
		$follow_pattern = '/(@([_a-z0-9\-]+))/i';
		$follow_replace = '<a href="https://twitter.com/${0}" target="_blank">${0}</a>';

		return preg_replace( $follow_pattern, $follow_replace, $tweet_text );
	}


	/**
	 * Title
	 *
	 * @param  str $tweet_text The tweet text.
	 * @return str The post title.
	 * @author Jérémy Levron <jeremylevron@19h47.fr> (http://19h47.fr)
	 * @access public
	 */
	public function title( $tweet_text ) {
		$link_pattern = '/(http|https)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/[^\s\…\.]*)?/';
		$post_title   = preg_replace( $link_pattern, '', $tweet_text );

		if ( strlen( $post_title ) >= 60 ) {
			substr( $post_title, 0, 60 ) . '...';
		}

		return $post_title;
	}


	/**
	 * Hashtags
	 *
	 * @param obj $tweet The tweet object.
	 * @return arr $hashtags Array of hastags.
	 * @author Jérémy Levron <jeremylevron@19h47.fr> (http://19h47.fr)
	 * @access public
	 */
	public function hashtags( $tweet ) {

		$hashtags = array();

		if ( ! isset( $tweet->entities->hashtags ) ) {
			return;
		}

		foreach ( $tweet->entities->hashtags as $hashtag ) {
			array_push( $hashtags, $hashtag->text );
		}

		return $hashtags;
	}


	/**
	 * Insert media
	 *
	 * @param obj $tweet The tweet object.
	 * @param int $post_id The post ID.
	 * @author Jérémy Levron <jeremylevron@19h47.fr> (http://19h47.fr)
	 * @access public
	 */
	public function insert_tweet_media( $tweet, $post_id ) {

		if ( ! isset( $tweet->extended_entities->media ) ) {
			return;
		}

		$i = 0;
		foreach ( $tweet->extended_entities->media as $media ) {
			// Don't get tweet with media type.
			if ( 'video' === $media->type ) {
				continue;
			}

			$thumbnail_id = insert_attachment_from_url( $media->media_url, $post_id );

			if ( 0 === $i ) {
				set_post_thumbnail( $post_id, $thumbnail_id );
			}
			$i++;
		}
	}
}
