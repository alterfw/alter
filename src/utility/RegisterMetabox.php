<?php
/**
 * Created by PhpStorm.
 * User: sergiovilar
 * Date: 03/05/14
 * Time: 10:55 AM
 */

class RegisterMetabox {

    private $boxes = array();

    /**
     * Add a post type and his fields
     *
     * @param $post_type
     * @param $fields
     */
    public function add($post_type, $fields){

        if($fields){
            $this->boxes[$post_type] = $fields;
        }

    }

    public function register(){

        add_filter( 'rwmb_meta_boxes', array($this, 'doRegister') );

    }

    public function doRegister(){

        $meta_boxes = array();
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

        foreach($this->boxes as $post_type => $fields){

            $box = array(
                'id' => $post_type . '_metabox',
                'title' => __('More') .' '. __('About'),
                'pages' => array($post_type),
                'context' => 'normal',
                'priority' => 'high',
                'autosave' => true,

                // List of meta fields
                'fields' => array()
            );

            foreach($fields as $key => $content){

                if(!in_array($key, $wp_fields)){

                    switch($content['type']){

                        case 'int':

                            array_push($box['fields'], array(
                                'name' => $content['label'],
                                'id'   => $key,
                                'type' => 'number',
                            ));

                            break;

                        case 'text':

                            array_push($box['fields'], array(
                                'name' => $content['label'],
                                'id'   => $key,
                                'type' => 'text',
                            ));

                            break;

                        case 'long_text':

                            array_push($box['fields'], array(
                                'name' => $content['label'],
                                'id'   => $key,
                                'type' => 'textarea',
                            ));

                            break;

                        case 'float':

                            array_push($box['fields'], array(
                                'name' => $content['label'],
                                'id'   => $key,
                                'type' => 'text',
                            ));

                            break;

                        case 'boolean':

                            array_push($box['fields'], array(
                                'name' => $content['label'],
                                'id'   => $key,
                                'type' => 'checkbox',
                            ));

                            break;

                        case 'list':

                            $function = $content['options'];

                            if(strpos($function, '(') > -1){

                                eval('$options = '.$function.';');

                            }else{
                                $options = $function;
                            }

                            array_push($box['fields'], array(
                                'name' => $content['label'],
                                'id'   => $key,
                                'type' => 'select',
                                'options' => $options
                            ));

                            break;

                        case 'file':

                            array_push($box['fields'], array(
                                'name' => $content['label'],
                                'id'   => $key,
                                'type' => 'file',
                            ));

                            break;

                        case 'date':

                            array_push($box['fields'], array(
                                'name' => $content['label'],
                                'id'   => $key,
                                'type' => 'date',
                            ));

                            break;

                        case 'map':

                            array_push($box['fields'], array(
                                'name' => $content['label'],
                                'id'   => $key,
                                'type' => 'map',
                                'style' => 'height: 300px;',
                                'std' => '-7.1274404, -34.868966'
                            ));

                            break;

                        case 'image':

                            if(empty($content['multiple'])){

                                array_push($box['fields'], array(
                                    'name' => $content['label'],
                                    'id'   => $key,
                                    'type' => 'image',
                                ));

                            }else{

                                array_push($box['fields'], array(
                                    'name' => $content['label'],
                                    'id'   => $key,
                                    'type' => 'plupload_image',
                                ));

                            }

                            break;

                        default:
                            array_push($box['fields'], array(
                                'name' => $content['label'],
                                'id'   => $key,
                                'type' => $content['type'],
                            ));
                            break;

                    }

                }

            }

            if(count($box['fields']) > 0){
                array_push($meta_boxes, $box);
            }

        }

        if(count($meta_boxes) > 0){
            return $meta_boxes;
        }else{
            return array();
        }

    }

} 
