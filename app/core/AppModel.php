<?php
/**
 * Created by PhpStorm.
 * User: sergiovilar
 * Date: 02/05/14
 * Time: 10:48 PM
 */

abstract class AppModel {

    private $post_type;
    private $taxonomies;

    private $singular;
    private $plural;
    private $description = '';

    function __construct(){

        // Define the post_type by convention (Ex: PostModel);
        $this->post_type = strtolower(str_replace('Model', '', get_class($this)));

        // Register the meta-boxes and post-type
        add_action( 'init', array($this, 'registerPostType'), 0 );

    }

    /**
     * @return mixed
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * @return string
     */
    public function getPostType()
    {
        return $this->post_type;
    }

    /**
     * Find posts in the Wordpress database using WP_Query
     *
     * @param $options
     * @return bool|WP_Query
     */
    public function find($options = null){

        try{

            $attrs = $this->getDefaultQuery();

            if(!empty($options)){

                // Posts limit
                if(!empty($options['limit'])){
                    $attrs['posts_per_page'] = $options['limit'];
                }

                // Check if has a manual query
                if(!empty($options['query'])){

                    $arr = explode('&', $options['query']);

                    foreach($arr as $item){

                        $arr_item = explode('=', $item);
                        $attrs[$arr_item[0]] = $arr_item[1];

                    }

                }

            }

            $qr = new WP_Query($attrs);

            if(!$qr->have_posts()){
                throw new NoPostFoundException();
            }else{

                $posts = [];

                while($qr->have_posts()){

                    $qr->the_post();

                    $obj = new PostObject(get_post(get_the_ID()), $this->fields);
                    array_push($posts, $obj);

                }

                return $posts;

            }

        }catch(NoPostFoundException $e){
            return false;
        }

    }

    public function findById($id){
        return new PostObject(get_post($id), $this->fields);
    }

    /**
     * Create a default query
     *
     * @return array
     */
    private function getDefaultQuery(){

        return array(
            'post_type'         => $this->post_type,
            'posts_per_page'    => -1
        );

    }

    /**
     * Register the post type
     */
    public function registerPostType(){

        if(!empty($this->singular)){
            $this->singular = ucfirst($this->post_type);
        }

        if(!empty($this->plural)){
            $this->plural = ucfirst($this->post_type) . 's';
        }

        $labels = array(
            'name'                => _x( $this->plural, 'Post Type General Name', 'text_domain' ),
            'singular_name'       => _x( $this->singular, 'Post Type Singular Name', 'text_domain' ),
            'menu_name'           => __( $this->singular, 'text_domain' ),
            'parent_item_colon'   => __( 'Parent Item:', 'text_domain' ),
            'all_items'           => __( 'All', 'text_domain' ) . ' '. $this->plural ,
            'view_item'           => __( 'View', 'text_domain' ) . ' '. $this->singular,
            'add_new_item'        => __( 'Add New', 'text_domain' ) . ' '. $this->singular,
            'add_new'             => __( 'Add New', 'text_domain' ),
            'edit_item'           => __( 'Edit', 'text_domain' ) . ' '. $this->singular,
            'update_item'         => __( 'Update', 'text_domain' ). ' '. $this->singular,
            'search_items'        => __( 'Search', 'text_domain' ). ' '. $this->singular,
            'not_found'           => __( 'Not found', 'text_domain' ),
            'not_found_in_trash'  => __( 'Not found in Trash', 'text_domain' ),
        );

        $args = array(
            'label'               => __( $this->post_type , 'text_domain' ),
            'description'         => __( $this->description, 'text_domain' ),
            'labels'              => $labels,
            'supports'            => array( ),
            'taxonomies'          => array( $this->taxonomies ),
            'hierarchical'        => false,
            'public'              => true,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'show_in_nav_menus'   => true,
            'show_in_admin_bar'   => true,
            'menu_position'       => 5,
            'menu_icon'           => '',
            'can_export'          => true,
            'has_archive'         => true,
            'exclude_from_search' => false,
            'publicly_queryable'  => true,
            'capability_type'     => 'page',
        );

        register_post_type( $this->post_type , $args );

    }

} 