<?php
/**
 * Globally-accessible functions
 *
 * @link https://github.com/19h47/status
 * @since 1.0.0
 *
 * @package Status
 * @subpackage Status/includes
 */

if ( ! function_exists( 'insert_attachment_from_url' ) ) :

	/**
	 * Insert an attachment from an URL address.
	 *
	 * @param  str $url
	 * @param  int $post_id
	 * @param  arr $meta_data
	 * @return int Attachment ID
	 * @author Miroslav Mitev
	 * @see https://gist.github.com/m1r0/f22d5237ee93bcccb0d9
	 */
	function insert_attachment_from_url( $url, $post_id = null ) {

		if ( ! class_exists( 'WP_Http' ) ) {
			include_once( ABSPATH . WPINC . '/class-http.php' );
		}

		$http     = new WP_Http();
		$response = $http->request( $url );

		if ( 200 !== $response['response']['code'] ) {
			return false;
		}

		$upload = wp_upload_bits( basename( $url ), null, $response['body'] );

		if ( ! empty( $upload['error'] ) ) {
			return false;
		}

		$file_path        = $upload['file'];
		$file_name        = basename( $file_path );
		$file_type        = wp_check_filetype( $file_name, null );
		$attachment_title = sanitize_file_name( pathinfo( $file_name, PATHINFO_FILENAME ) );
		$wp_upload_dir    = wp_upload_dir();

		$post_info = array(
			'guid'           => $wp_upload_dir['url'] . '/' . $file_name,
			'post_mime_type' => $file_type['type'],
			'post_title'     => $attachment_title,
			'post_content'   => '',
			'post_status'    => 'inherit',
		);

		// Create the attachment
		$attach_id = wp_insert_attachment( $post_info, $file_path, $post_id );

		// Include image.php
		require_once( ABSPATH . 'wp-admin/includes/image.php' );

		// Define attachment metadata
		$attach_data = wp_generate_attachment_metadata( $attach_id, $file_path );

		// Assign metadata to attachment
		wp_update_attachment_metadata( $attach_id, $attach_data );

		return $attach_id;
	}

endif;
