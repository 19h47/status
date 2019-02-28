<?php
/**
 * The post type of the plugin.
 *
 * @link https://github.com/19h47/status
 * @since 1.0.0
 *
 * @package Status
 * @subpackage status/admin
 */

/**
 * The post type of the plugin.
 *
 * @package Status
 * @subpackage Status/admin
 * @author Jérémy Levron <jeremylevron@19h47.fr> (http://19h47.fr)
 */
class Status_Admin_Post_Type {

	/**
	 * The ID of this plugin.
	 *
	 * @since 1.0.0
	 * @access private
	 * @var string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;


	/**
	 * The version of this plugin.
	 *
	 * @since 1.0.0
	 * @access private
	 * @var string $version The current version of this plugin.
	 */
	private $version;


	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 1.0.0
	 * @param string $plugin_name The name of this plugin.
	 * @param string $version The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version     = $version;

		add_theme_support( 'post-thumbnails' );
	}


	/**
	 * Register Custom Post Type
	 *
	 * @since 1.0.0
	 * @access public
	 * @uses register_post_type()
	 */
	public static function register_post_type() {
		$labels = array(
			'name'                  => _x( 'Tweets', 'Tweet pluriel', 'status' ),
			'singular_name'         => _x( 'Tweet', 'Tweet singulier', 'status' ),
			'menu_name'             => __( 'Tweets', 'status' ),
			'name_admin_bar'        => __( 'Tweet', 'status' ),
			'all_items'             => __( 'Tous les tweets', 'status' ),
			'add_new_item'          => __( 'Ajouter un tweet', 'status' ),
			'add_new'               => __( 'Ajouter', 'status' ),
			'new_item'              => __( 'Nouveau tweet', 'status' ),
			'edit_item'             => __( 'Modifier le tweet', 'status' ),
			'update_item'           => __( 'Mettre à jour le tweet', 'status' ),
			'view_item'             => __( 'Voir le tweet', 'status' ),
			'view_items'            => __( 'Voir les tweets', 'status' ),
			'search_items'          => __( 'Chercher parmi les tweets', 'status' ),
			'not_found'             => __( 'Aucun tweet trouvé.', 'status' ),
			'not_found_in_trash'    => __( 'Aucun tweet trouvé dans la corbeille.', 'status' ),
			'featured_image'        => __( 'Image à la une', 'status' ),
			'set_featured_image'    => __( 'Mettre une image à la une', 'status' ),
			'remove_featured_image' => __( 'Retirer l\'image mise à la une', 'status' ),
			'use_featured_image'    => __( 'Mettre une image à la une', 'status' ),
			'insert_into_item'      => __( 'Insérer dans le tweet', 'status' ),
			'uploaded_to_this_item' => __( 'Ajouter à ce tweet', 'status' ),
			'items_list'            => __( 'Liste des tweets', 'status' ),
			'items_list_navigation' => __( 'Navigation de liste des tweets', 'status' ),
			'filter_items_list'     => __( 'Filtrer la liste des tweets', 'status' ),
		);

		$rewrite = array(
			'slug'       => 'tweets',
			'with_front' => true,
			'pages'      => true,
			'feeds'      => false,
		);

		$args = array(
			'label'               => 'tweet',
			'description'         => __( 'Les tweets', 'status' ),
			'labels'              => $labels,
			'supports'            => array( 'title', 'editor', 'author', 'thumbnail' ),
			'taxonomies'          => array( 'hashtag' ),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_nav_menus'   => true,
			'show_in_menu'        => true,
			'show_in_admin_bar'   => true,
			'show_in_rest'        => true,
			'rest_base'           => 'tweets',
			'menu_position'       => 5,
			'menu_icon'           => 'dashicons-twitter',
			'can_export'          => true,
			'has_archive'         => true,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'rewrite'             => $rewrite,
			'capability_type'     => 'post',
		);
		register_post_type( 'tweet', $args );
	}


	/**
	 * CSS
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function css() {
		?>
		<style>
			#dashboard_right_now .tweet-count:before { content: "\f301"; }
			.fixed .column-thumbnail {
				width: 62px;
			}
			.column-thumbnail a {
				display: inline-block;
				vertical-align: middle;

				width: 60px;
				height: 60px;
				max-width: 60px;

				border: 1px solid #e5e5e5;
				border: 1px solid rgba( 0, 0, 0, .07 );
			}
			.column-thumbnail a img {
				display: inline-block;
				vertical-align: middle;

				object-fit: cover;
				object-position: center;
			}
		</style>
		<?php
	}


	/**
	 * Add custom columns
	 *
	 * @since 1.0.0
	 * @param arr $columns An array of column name ⇒ label. The name is passed to functions to identify the column. The label is shown as the column header.
	 */
	public function add_custom_columns( $columns ) {
		global $typenow;

		if ( 'tweet' !== $typenow ) {
			return;
		}

		$new_columns = array();
		$keys        = array_keys( $columns );

		foreach ( $columns as $key => $value ) {
			if ( 'title' === $key ) {
				$new_columns['thumbnail'] = __( 'Image' );
			}

			$new_columns[ $key ] = $value;
		}
		return $new_columns;
	}


	/**
	 * Render custom columns
	 *
	 * @since 1.0.0
	 * @param arr $column_name The name of the column to display.
	 * @param int $post_id The ID of the current post. Can also be taken from the global $post->ID.
	 * @return void
	 */
	public function render_custom_columns( $column_name, $post_id ) {
		global $typenow;

		if ( 'tweet' !== $typenow ) {
			return;
		}

		switch ( $column_name ) {
			case 'thumbnail':
				$post_thumbnail = get_the_post_thumbnail(
					$post_id,
					array( 60, 60 ),
					array( 'class' => '' )
				);

				include plugin_dir_path( __FILE__ ) . 'partials/status-admin-post-type-thumbnail-column.php';

				break;
		}
	}


	/**
	 * "At a glance" items (dashboard widget): add the tweet.
	 *
	 * @since 1.0.0
	 * @param arr $items Array of items.
	 * @return $items
	 */
	public function at_a_glance( $items ) {
		$post_type   = 'tweet';
		$post_status = 'publish';
		$object      = get_post_type_object( $post_type );

		$num_posts = wp_count_posts( $post_type );
		if ( ! $num_posts || ! isset( $num_posts->{$post_status} ) || 0 === (int) $num_posts->{$post_status} ) {
			return $items;
		}

		$text = sprintf(
			_n( '%1$s %4$s%2$s', '%1$s %4$s%3$s', $num_posts->{$post_status} ),
			number_format_i18n( $num_posts->{$post_status} ),
			strtolower( $object->labels->singular_name ),
			strtolower( $object->labels->name ),
			'pending' === $post_status ? 'Pending ' : ''
		);

		if ( current_user_can( $object->cap->edit_posts ) ) {
			$items[] = sprintf(
				'<a class="%1$s-count" href="edit.php?post_status=%2$s&post_type=%1$s">%3$s</a>',
				$post_type,
				$post_status,
				$text
			);
		} else {
			$items[] = sprintf( '<span class="%1$s-count">%s</span>', $text );
		}

		return $items;
	}


	/**
	 * Register REST route
	 *
	 * @access public
	 */
	public function register_rest_route_status() {
		register_rest_route(
			'wp/v2',
			'/tweets/',
			array(
				'methods' => 'GET',
			)
		);
	}

	/**
	 * Register REST field meta
	 *
	 * Doc: register_rest_field ( 'name-of-post-type', 'name-of-field-to-return', array-of-callbacks-and-schema() )
	 *
	 * @access public
	 */
	public function register_rest_field_meta() {
		register_rest_field(
			'tweet',
			'meta',
			array(
				'get_callback' => array( $this, 'get_post_meta_status' ),
				'schema'       => null,
			)
		);
	}


	/**
	 * Get post meta
	 *
	 * @param arr $array Array.
	 * @access public
	 */
	public function get_post_meta_status( $array ) {
		// Get the id of the post object array.
		$post_id = $array['id'];

		// Return the post meta.
		return get_post_meta( $post_id );
	}


	/**
	 * Register REST route for unix timestamp date
	 *
	 * Doc: register_rest_field ( 'name-of-post-type', 'name-of-field-to-return', array-of-callbacks-and-schema() )
	 *
	 * @access public
	 */
	public function register_rest_field_date_unix_timestamp() {
		register_rest_field(
			'tweet',
			'date_unix_timestamp',
			array(
				'get_callback' => array( $this, 'get_date_as_unix_timestamp' ),
				'schema'       => null,
			)
		);
	}


	/**
	 * Get date as Unix Timestamp
	 *
	 * @param arr $array Array.
	 * @access public
	 * @return $date_unix_time_stamp
	 */
	public function get_date_as_unix_timestamp( $array ) {
		$post_id = $array['id'];

		$date_unix_time_stamp = get_the_date( 'U', $post_id );

		return $date_unix_time_stamp;
	}
}
