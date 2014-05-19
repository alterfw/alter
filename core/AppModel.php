<?php
/**
 * Created by PhpStorm.
 * User: sergiovilar
 * Date: 02/05/14
 * Time: 10:48 PM
 */

abstract class AppModel {

    private $post_type;

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

        if(!empty($this->fields)){
            return $this->fields;
        }else{
            return false;
        }

    }

    public function getTaxonomies(){

        if(!empty($this->taxonomies)){
            return $this->taxonomies;
        }else{
            return false;
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
     * Find posts in the Wordpress database using WP_Query
     *
     * @param $options
     * @return bool|WP_Query
     */
    public function find($options = null){

        try{            

            $attrs = $this->buildQuery($options);

            if(empty($attrs['limit'])){
                $attrs['limit'] = -1;
            }

            $qr = new WP_Query($attrs);

            if(!$qr->have_posts()){
                throw new NoPostFoundException();
            }else{

                $posts = array();

                while($qr->have_posts()){

                    $qr->the_post();

                    $obj = new PostObject(get_post(get_the_ID()), $this);
                    array_push($posts, $obj);

                }

                return $posts;

            }

        }catch(NoPostFoundException $e){
            return false;
        }

    }

    public function findById($id){
        return new PostObject(get_post($id), $this);
    }

    public function findBySlug($slug){

        $args = array(
          'name' => $slug,
          'post_type' => $this->post_type,
          'post_status' => 'publish',
          'numberposts' => 1
        );

        $my_posts = get_posts($args);
        
        if( $my_posts ) {
            return $this->findById($my_posts[0]->ID);
        }else{
            return false;
        }

    }

    public function findByTaxonomy($taxonomy, $value, $limit){

        $options = array();

        if(empty($limit)){
            $limit = get_option('posts_per_page');
        }

        $options['posts_per_page'] = $limit;

        $options['tax_query'] = array(
            array(
                'taxonomy' => $taxonomy,
                'field' => 'slug',
                'terms' => $value
            )
        );

        return $this->find($options);

    }

    public function paginate($limit = null){

        $paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;

        if(empty($limit)){
            $limit = get_option('posts_per_page');
        }

        return $this->find(array('limit' => $limit, 'query' => 'paged='.$paged));

    }

    private function buildQuery($options = null){

        $attrs = $this->getDefaultQuery();

        if(!empty($options)){

            if(is_int($options)){
                $options = array('limit' => $options);
            }

            // Posts limit
            if(!empty($options['limit'])){
                $attrs['posts_per_page'] = $options['limit'];
            }

            // Check if has a manual query

            if(is_array($options)){
                foreach($options as $key => $value){
                    $attrs[$key] = $value;
                }
            }

            if(!empty($options['query'])){

                $arr = explode('&', $options['query']);

                foreach($arr as $item){

                    $arr_item = explode('=', $item);
                    $attrs[$arr_item[0]] = $arr_item[1];

                }

            }

        }

        return $attrs;

    }

    /**
     * Create a default query
     *
     * @return array
     */
    private function getDefaultQuery(){

        return array(
            'post_type'     => $this->post_type,
            'post_status'   => 'publish'
        );

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

        if(!empty($this->fields))

            foreach($this->fields as $key => $value){

                if(($key =='title' || $key == 'editor' || $key == 'thumbnail' || $key == 'comments') && $value){
                    array_push($supports, $key);
                }

            }

        if(count($supports) == 0) $supports = false;

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
            'capability_type'     => 'page',
        );

        if(!empty($this->route))
            $args['rewrite'] = array('slug' => $this->route, 'with_front' => true);

        if($this->post_type != 'page')
            register_post_type( $this->post_type , $args );

    }

} 