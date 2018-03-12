<?php

/**
 * The taxonomy of the plugin.
 *
 * @link       http://19h47.fr
 * @since      1.0.0
 *
 * @package    Status
 * @subpackage Status/admin
 */


/**
 * The taxonomy of the plugin.
 *
 * @package    Status
 * @subpackage Status/admin
 * @author     Jérémy Levron <jeremylevron@19h47.fr>
 */
class Status_Admin_Taxonomy {

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
	 * Initialize the class and set its properties.
	 *
	 * @since    	1.0.0
	 * @param      	string    		$plugin_name       	The name of this plugin.
	 * @param      	string    		$version    		The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}


	/**
	 * Register Custom Taxonomy
	 */
	function register_taxonomy() {
		$labels = array(
			'name'                       	=> _x( 'Hashtags', 'Taxonomy General Name', $this->plugin_name ),
			'singular_name'              	=> _x( 'Catégorie de témoignage', 'Taxonomy Singular Name', $this->plugin_name ),
			'menu_name'                  	=> __( 'Hashtags', $this->plugin_name ),
			'all_items'                  	=> __( 'Toutes les hashtags', $this->plugin_name ),
			'parent_item'                	=> __( 'Hashtag parent', $this->plugin_name ),
			'parent_item_colon'          	=> __( 'Hashtag parent :', $this->plugin_name ),
			'new_item_name'              	=> __( 'Nom du nouveau hashtag', $this->plugin_name ),
			'add_new_item'               	=> __( 'Ajouter un nouveau hashtag', $this->plugin_name ),
			'edit_item'                  	=> __( 'Éditer le hashtag', $this->plugin_name ),
			'update_item'                	=> __( 'Mettre à jour le hashtag', $this->plugin_name ),
			'view_item'                  	=> __( 'Voir le hashtag', $this->plugin_name ),
			'separate_items_with_commas'	=> __( 'Séparer les hashtags par des virgules', $this->plugin_name ),
			'add_or_remove_items'        	=> __( 'Ajouter ou supprimer un hashtag', $this->plugin_name ),
			'choose_from_most_used'      	=> __( 'Choisir parmi les hashtags les plus utilisées', $this->plugin_name ),
			'popular_items'              	=> __( 'Hashtag populaire', $this->plugin_name ),
			'search_items'               	=> __( 'Hahstag recherchés', $this->plugin_name ),
			'not_found'                  	=> __( 'Aucune hashtag n\'a été trouvé', $this->plugin_name ),
			'no_terms'                   	=> __( 'Pas de hashtag', $this->plugin_name ),
			'items_list'                 	=> __( 'Liste des hashtags', $this->plugin_name ),
			'items_list_navigation'      	=> __( 'Liste de navigation des hashtags', $this->plugin_name ),
		);

		$args = array(
			'labels'            	=> $labels,
			'hierarchical'      	=> false,
			// https://stackoverflow.com/a/23493737/5091221
			'update_count_callback'	=> '_update_generic_term_count',
			'public'            	=> true,
			'show_ui'           	=> true,
			'show_admin_column' 	=> true,
			'show_in_nav_menus' 	=> true,
			'show_tagcloud'     	=> true,
		);

		register_taxonomy( 'hashtag', array( 'tweet' ), $args );
	}
}