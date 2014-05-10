<?php
/**
 * Created by PhpStorm.
 * User: sergiovilar
 * Date: 03/05/14
 * Time: 02:40 PM
 */

class AppTaxonomy{

    private $key;
    private $singular;
    private $plural;

    function __construct($config, $post_type){

        $this->key = $config['key'];
        $this->singular = $config['singular'];
        $this->plural = $config['plural'];
        $this->hierarchical = $config['hierarchical'];

        $this->post_type = $post_type;

        add_action( 'init', array($this, 'register'), 0 );

    }

    public function register() {

        $labels = array(
            'name'                => __($this->plural),
            'singular_name'       => __($this->singular),
            'menu_name'           => __($this->plural),
            'parent_item_colon'   => __( 'Parent Item:'),
            'all_items'           => __($this->plural),
            'view_item'           => __( 'View') . ' '. __($this->plural),
            'add_new_item'        => __( 'Add' ) . ' '. __($this->singular),
            'add_new'             => __( 'Add') .' '. __($this->singular),
            'edit_item'           => __( 'Edit') . ' '. __($this->singular),
            'update_item'         => __( 'Update'). ' '. __($this->singular),
            'search_items'        => __( 'Search'). ' '. __($this->singular),
            'not_found'           => __( 'Not found'),
            'not_found_in_trash'  => __( 'Not found in Trash'),
        );

        $args = array(
            'labels'                     => $labels,
            'hierarchical'               => $this->hierarchical,
            'public'                     => true,
            'show_ui'                    => true,
            'show_admin_column'          => true,
            'show_in_nav_menus'          => true,
            'show_tagcloud'              => true,
        );

        register_taxonomy( $this->key, $this->post_type, $args );

    }

}
