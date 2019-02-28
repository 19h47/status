<?php
/**
 * The taxonomy of the plugin.
 *
 * @link https://github.com/19h47/status
 * @since 1.0.0
 *
 * @package Status
 * @subpackage status/admin
 */

/**
 * The taxonomy of the plugin.
 *
 * @package Status
 * @subpackage Status/admin
 * @author Jérémy Levron <jeremylevron@19h47.fr> (http://19h47.fr)
 */
class Status_Admin_Taxonomy {

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
	 * Register Custom Taxonomy
	 *
	 * @see https://stackoverflow.com/a/23493737/5091221
	 * @return void
	 * @access public
	 */
	public function register_taxonomy() {
		$labels = array(
			'name'                       => _x( 'Hashtags', 'Taxonomy General Name', 'status' ),
			'singular_name'              => _x( 'Hashtag', 'Taxonomy Singular Name', 'status' ),
			'menu_name'                  => __( 'Hashtags', 'status' ),
			'all_items'                  => __( 'Toutes les hashtags', 'status' ),
			'parent_item'                => __( 'Hashtag parent', 'status' ),
			'parent_item_colon'          => __( 'Hashtag parent :', 'status' ),
			'new_item_name'              => __( 'Nom du nouveau hashtag', 'status' ),
			'add_new_item'               => __( 'Ajouter un nouveau hashtag', 'status' ),
			'edit_item'                  => __( 'Éditer le hashtag', 'status' ),
			'update_item'                => __( 'Mettre à jour le hashtag', 'status' ),
			'view_item'                  => __( 'Voir le hashtag', 'status' ),
			'separate_items_with_commas' => __( 'Séparer les hashtags par des virgules', 'status' ),
			'add_or_remove_items'        => __( 'Ajouter ou supprimer un hashtag', 'status' ),
			'choose_from_most_used'      => __( 'Choisir parmi les hashtags les plus utilisés', 'status' ),
			'popular_items'              => __( 'Hashtag populaire', 'status' ),
			'search_items'               => __( 'Hashtag recherchés', 'status' ),
			'not_found'                  => __( 'Aucun hashtag n\'a été trouvé', 'status' ),
			'no_terms'                   => __( 'Pas de hashtag', 'status' ),
			'items_list'                 => __( 'Liste des hashtags', 'status' ),
			'items_list_navigation'      => __( 'Liste de navigation des hashtags', 'status' ),
		);

		$args = array(
			'labels'                => $labels,
			'hierarchical'          => false,
			'update_count_callback' => '_update_generic_term_count',
			'public'                => true,
			'show_ui'               => true,
			'show_admin_column'     => true,
			'show_in_nav_menus'     => true,
			'show_tagcloud'         => true,
			'show_in_rest'          => true,
		);

		register_taxonomy( 'hashtag', array( 'tweet' ), $args );
	}
}
