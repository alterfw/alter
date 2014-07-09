<?php

/**
 * Created by PhpStorm.
 * User: sergiovilar
 * Date: 03/05/14
 * Time: 10:55 AM
 */
class RegisterMetabox
{
    private $boxes = array();

    /**
     * Add a post type and his fields
     *
     * @param $post_type
     * @param $fields
     */
    public function add($post_type, array $fields)
    {
        if ($fields) {
            $this->boxes[$post_type] = $fields;
        }
    }

    public function register()
    {
        add_filter('rwmb_meta_boxes', array($this, 'doRegister'));
    }

    /**
     * @return array
     */
    public function doRegister()
    {
        $meta_boxes = array();

        foreach ($this->boxes as $post_type => $fields) {

            $info = array(
               'id'       => $post_type . '_metabox',
               'title'    => __('More') . ' ' . __('About'),
               'context'  => 'normal',
               'priority' => 'high',
               'autosave' => true,
            );

            $_box = new BoxObject($post_type, $info, $fields);

            $box = $_box->getBox();

            if (!empty($box['fields'])) {
                array_push($meta_boxes, $box);
            }
        }
        return $meta_boxes;
    }
}