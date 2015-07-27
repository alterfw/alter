<?php
/**
 * Created by PhpStorm.
 * User: sergiovilar
 * Date: 02/05/14
 * Time: 10:48 PM
 */

abstract class AppModel {

    private $post_type;
    private $default_post_type = array('post', 'page');
    protected $fields;

    function __construct(){

        // Define the post_type by convention (Ex: PostModel);
        $this->post_type = strtolower(str_replace('Model', '', get_class($this)));

        // Register the meta-boxes and post-type
        if(!in_array($this->post_type, $this->default_post_type)){
            add_action( 'init', array($this, 'registerPostType'), 0 );
        }

    }

    /**
     * @return mixed
     */
    public function getFields()
    {

        if(!empty($this->fields)){
            return $this->fields;
        }else{
            return array();
        }

    }

    public function getTaxonomies(){

        if(!empty($this->taxonomies)){
            return $this->taxonomies;
        }else{
            return array();
        }

    }

    /**
     * @return string
     */
    public function getPostType()
    {
        return $this->post_type;
    }

    /**
     * Register the post type
     */
    public function registerPostType(){

        if(!isset($this->singular)){
            $this->singular = ucfirst($this->post_type);
        }

        if(!isset($this->plural)){
            $this->plural = ucfirst($this->post_type) . 's';
        }

        if(!isset($this->description)){
            $this->description = '';
        }

        if(!isset($this->icon)){
            $icon = 'dashicons-admin-post';
        }else{

            if(strpos($this->icon, '.') > 0){
                $icon = ALTER_IMG . $this->icon;
            }else{
                $icon = $this->icon;
            }

        }

        $tax = array();

        if(!empty($this->taxonomies))
            $tax = $this->taxonomies;

        $supports = array();
        $wp_fields = array(
        	'title', 
        	'editor', 
        	'thumbnail', 
        	'excerpt', 
        	'comments', 
        	'revisions', 
        	'trackbacks', 
        	'page-attributes'
        );

        if(!empty($this->fields))

            foreach($this->fields as $key => $value){

                if(in_array($key, $wp_fields) && $value){
                    array_push($supports, $key);
                }

            }

        if(count($supports) == 0) $supports = false;

        if(!empty($this->capability_type)){
            $capability_type = $this->capability_type;
        }else{
            $capability_type = 'page';
        }

        if(!empty($this->capabilities)){
            $capabilities = $this->capabilities;
        }else{
            $capabilities = array();
        }

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
            'label'               => __( $this->post_type , 'text_domain' ),
            'description'         => __( $this->description, 'text_domain' ),
            'labels'              => $labels,
            'supports'            => $supports,
            'taxonomies'          => $tax,
            'hierarchical'        => false,
            'public'              => true,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'show_in_nav_menus'   => true,
            'show_in_admin_bar'   => true,
            'menu_position'       => 5,
            'menu_icon'           => $icon,
            'can_export'          => true,
            'has_archive'         => true,
            'exclude_from_search' => false,
            'publicly_queryable'  => true,
            'capability_type'     => $capability_type,
            'capabilities'        => $capabilities,
        );

        if(!empty($this->route)){
			$args['rewrite'] = array('slug' => $this->route, 'with_front' => true);
		}

        if($this->post_type != 'page'){
			register_post_type( $this->post_type , $args );
		}

    }

} 
