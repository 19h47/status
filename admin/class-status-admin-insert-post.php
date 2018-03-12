<?php

/**
 * Insert post
 *
 * @link       http://19h47.fr
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
	 * @since    	1.0.0
	 * @access   	private
	 * @var      	string    		$plugin_name    	The ID of this plugin.
	 */
	private $plugin_name;


	/**
	 * The version of this plugin.
	 *
	 * @since    	1.0.0
	 * @access   	private
	 * @var      	string    		$version    		The current version of this plugin.
	 */
	private $version;


	/**
	 * Tweets
	 *
	 * @since  1.0.0
	 * @access private
	 */
	private $tweets;


	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    	1.0.0
	 * @param      	string    		$plugin_name       	The name of this plugin.
	 * @param      	string    		$version    		The version of this plugin.
	 */
	public function __construct( $plugin_name, $version, $tweets ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->tweets = $tweets;
	}


	/**
	 * Insert post
	 *
	 * @param $tweets
	 */
	function insert_post() {

		foreach ( $this->tweets as $tweet ) {

			$tweet_id = abs( (int) $tweet->id );
          	$post_exist = get_posts(
          		array(
		            'post_type' 	=> 'tweet',
		            'post_status' 	=> 'any',
		            'meta_key' 		=> '_tweet_id',
		            'meta_value' 	=> $tweet_id,
	          	)
          	);
          	if ( $post_exist ) continue; // Do Nothing


          	$tweet_text = $this->text( $tweet->text );
          	$tweet_text = $this->follow( $tweet_text );
          	$post_title = $this->title( $tweet->text );

          	foreach ( $tweet->entities->hashtags as $hashtag ) {
          		$hashFindPattern = "/#" . $hashtag->text . "/";
          		$hashUrl = 'https://twitter.com/hashtag/' . $hashtag->text . '?src=hash';
          		$hashReplace = '<a href="' . $hashUrl . '" target="_blank">#' . $hashtag->text .'</a>';
          		$tweet_text = preg_replace( $hashFindPattern, $hashReplace, $tweet_text );
          	}

          	$date = date_i18n(
          		'Y-m-d H:i:s',
          		strtotime( $tweet->created_at ) + $tweet->user->utc_offset
          	);


            // postarr
			$postarr = array(
				'post_author'		=> 1,
				'post_content'		=> $tweet_text,
				'post_date'			=> $date,
				'post_date_gmt'		=> $date,
				'post_modified'		=> $date,
				'post_modified_gmt'	=> $date,
				'post_title'		=> $post_title,
				'post_type'			=> 'tweet',
			);
			$post_id = wp_insert_post( $postarr, true );


			// Hashtags
			foreach ( $this->hashtags( $tweet ) as $hashtag ) {
				wp_set_object_terms( $post_id, $hashtag, 'hashtag', true );
			}

			$this->insert_tweet_media( $tweet, $post_id );

			//Tweet's Original URL
			$tweet_url = 'https://twitter.com/' . $tweet->user->screen_name . '/status/' . $tweet->id;

			update_post_meta( $post_id, '_tweet_id', $tweet->id );
			update_post_meta( $post_id, '_tweet_url', $tweet_url );
		}
	}


	/**
	 * Text
	 *
	 * @param str $tweet_text
	 * @author  Jérémy Levron <jeremylevron@19h47.fr>
	 */
	function text( $tweet_text ) {

		// Convert url to HTML link
		$link_pattern = "/(http|https)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/[^\s\…\.]*)?/";
      	$link_replace = '<a href="${0}" target="_blank">${0}</a>';

      	return preg_replace( $link_pattern, $link_replace, $tweet_text );
	}


	/**
	 * Follow
	 *
	 * @param  str $tweet_text
	 * @author  Jérémy Levron <jeremylevron@19h47.fr>
	 */
	function follow( $tweet_text ) {

		// Convert @ to follow
		$follow_pattern = '/(@([_a-z0-9\-]+))/i';
      	$follow_replace = '<a href="https://twitter.com/${0}" target="_blank">${0}</a>';

      	return preg_replace( $follow_pattern, $follow_replace, $tweet_text );
	}


	/**
	 * Title
	 *
	 * @param  str $tweet_text
	 * @author  Jérémy Levron <jeremylevron@19h47.fr>
	 */
	function title( $tweet_text ) {

		$link_pattern = "/(http|https)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/[^\s\…\.]*)?/";
		$post_title = preg_replace( $link_pattern, '', $tweet_text );

      	if ( strlen( $post_title ) >= 60 ) {
          	substr( $post_title, 0, 60 ) . '...';
        }

        return $post_title;
	}


	/**
	 * Hashtags
	 *
	 * @param  	obj $tweet
	 * @return  arr $hashtags
	 * @author  Jérémy Levron <jeremylevron@19h47.fr>
	 */
	function hashtags( $tweet ) {

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
	 * @param 	object 		$tweet Tweet object
	 * @param 	int  		$post_id
	 * @author Jérémy Levron <jeremylevron@19h47.fr>
	 */
	function insert_tweet_media( $tweet, $post_id ) {

		if ( ! isset( $tweet->extended_entities->media ) ) {
			return;
		}

		$i = 0;
		foreach ( $tweet->extended_entities->media as $media ) {

			if ( $media->type === 'video' ) continue;

			$thumbnail_id = insert_attachment_from_url( $media->media_url, $post_id );

			if ( $i === 0 ) {
				set_post_thumbnail( $post_id, $thumbnail_id );
			}
			$i++;
		}
	}
}
