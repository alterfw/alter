<?php

/**
 * Created by PhpStorm.
 * User: sergiovilar
 * Date: 02/05/14
 * Time: 10:48 PM
 */
abstract class AppModel
{

    public $fields = array();
    public $taxonomies = array();
    public $labels = array();
    public $capabilities = array();
    public $args = array();
    public $supports = array();
    public $icon = 'dashicons-admin-post';
    public $capability_type = 'page';
    public $text_domain = 'text_domain';
    public $singular;
    public $plural;
    public $description;
    public $route;
    private $post_type;

    function __construct()
    {
        // Register the meta-boxes and post-type
        add_action('init', array($this, 'registerPostType'), 0);

    }

    /**
     * @return mixed
     */
    public function getFields()
    {
        return (empty($this->fields)) ? false : $this->fields;
    }

    /**
     * Register the post type
     */
    public function registerPostType()
    {
        $post_type       = $this->getPostType();
        $singular        = (empty($this->singular)) ? ucfirst($post_type) : $this->singular;
        $plural          = (empty($this->plural)) ? ucfirst($post_type) . 's' : $this->plural;
        $tax             = ($this->getTaxonomies()) ? $this->getTaxonomies() : array();
        $capability_type = $this->capability_type;
        $capabilities    = $this->capabilities;


        if (empty($this->icon)):
            $icon = 'dashicons-admin-post';
        else:
            if (strpos($this->icon, '.') > 0):
                $icon = ALTER_IMG . $this->icon;
            else:
                $icon = $this->icon;
            endif;
        endif;

        $supports           = array();
        $_supportsAvailable = array('title', 'editor', 'thumbnail', 'comments');

        if (!empty($this->fields)) :
            foreach ($_supportsAvailable as $value):
                if (isset($this->fields[$value]) and $this->fields[$value]) array_push($supports, $value);
            endforeach;
        endif;

        $supports = array_merge($supports, $this->supports);
        if (empty($supports)) $supports = false;

        $labels = wp_parse_args(
           $this->labels,
           array(
              'name'               => __($plural),
              'singular_name'      => __($singular),
              'menu_name'          => __($plural),
              'parent_item_colon'  => __('Parent Item:'),
              'all_items'          => __($plural),
              'view_item'          => __('View') . ' ' . __($plural),
              'add_new_item'       => __('Add') . ' ' . __($singular),
              'add_new'            => __('Add') . ' ' . __($singular),
              'edit_item'          => __('Edit') . ' ' . __($singular),
              'update_item'        => __('Update') . ' ' . __($singular),
              'search_items'       => __('Search') . ' ' . __($singular),
              'not_found'          => __('Not found'),
              'not_found_in_trash' => __('Not found in Trash'),
           )
        );

        $args = array(
           'label'               => __($post_type, $this->text_domain),
           'description'         => __($this->description, $this->text_domain),
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

        if (!empty($this->route)):
            $args['rewrite'] = array('slug' => $this->route, 'with_front' => true);
        endif;

        $args = wp_parse_args($this->args, $args);

        if ($post_type != 'page'):
            register_post_type($post_type, $args);
        endif;

    }

    /**
     * @return string
     */
    public function getPostType()
    {
        if (empty($post_type)):
            // Define the post_type by convention (Ex: PostModel);
            $this->post_type = strtolower(str_replace('Model', '', get_class($this)));
        else:
            $this->post_type = $post_type;
        endif;

        return $this->post_type;
    }

    /**
     * @param string $post_type
     */
    public function setPostType($post_type)
    {
        if (!empty($post_type)):
            $this->post_type = $post_type;
        endif;
    }

    /**
     * @return bool
     */
    public function getTaxonomies()
    {
        return (empty($this->taxonomies)) ? false : $this->taxonomies;
    }

} 