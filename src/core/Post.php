<?php
/**
 * Created by PhpStorm.
 * User: sergiovilar
 * Date: 03/05/14
 * Time: 02:09 PM
 */

class PostObject {

    public function __construct($postObject, $model){

        $object = array();

        $fields = $model->fields;
        $taxonomies = $model->getTaxonomies();

        $modelDefaultFields = array('title', 'thumbnail', 'editor');
        $valueTypes = array('text', 'long_text', 'int', 'float', 'boolean', 'list');

        // Post default properties
        foreach($postObject as $key => $value){
            $chave = str_replace('post_', '', $key);
            $this->{$chave} = $value;
        }

        // Permalink
        $this->permalink = get_permalink($this->ID);

        // Terms
        if( !empty($taxonomies))

            foreach($taxonomies as $taxonomy){
                $this->{$taxonomy} = wp_get_post_terms( $this->ID, $taxonomy);
            }

        // Custom fields
        foreach($fields as $key => $value){

            if(!in_array($key, $modelDefaultFields)){

                if(in_array($value['type'], $valueTypes)){

                    if(empty($value['multiple']) || !$value['multiple']){
                        $this->{$key} = get_post_meta($postObject->ID, $key, true);
                    }else{
                        $this->{$key} = get_post_meta($postObject->ID, $key);
                    }

                }else{

                    switch($value['type']){

                        case 'image':

                            $this->{$key} = $this->getImage($postObject, $key, $value);

                            break;

                        case 'file':

                            $this->{$key} = $this->getFile($postObject, $key, $value);

                            break;

                    }

                }

            }

        }

        // Include subpages    
        if($model->getPostType() == 'page'){

            $my_wp_query = new WP_Query();
            $all_wp_pages = $my_wp_query->query(array('post_type' => 'page'));            

            // Filter through all pages and find Portfolio's children
            $children = get_page_children( $this->ID, $all_wp_pages );
            $this->children = array();

            foreach($children as $child){
                array_push($this->children, new PostObject($child, $model));
            }

        }

        // Set the thumbnail
        $image = get_post_thumbnail_id($postObject->ID);

        $img = new stdClass();

        foreach( get_intermediate_image_sizes() as $s ){
            $wp_image = wp_get_attachment_image_src( $image, $s);
            $img->{$s} = $wp_image[0];
        }

        $wp_image = wp_get_attachment_image_src( $image, 'full');
        $img->full = $wp_image[0];

        $this->thumbnail = $img;

    }

    private function getImage($postObject, $key, $value){

        $retorno = array();

        if(empty($value['multiple']) || !$value['multiple']){

            $image = get_post_meta($postObject->ID, $key, true);

            $img = new stdClass();

            foreach( get_intermediate_image_sizes() as $s ){
                $wp_image = wp_get_attachment_image_src( $image, $s);
                $img->{$s} = $wp_image[0];
            }

            $wp_image = wp_get_attachment_image_src( $image, 'full');
            $img->full = $wp_image[0];
            $img->caption = get_post($image)->post_excerpt;

            $retorno = $img;

        }else{

            $images = get_post_meta($postObject->ID, $key);

            foreach($images as $image){

                $img = new stdClass();

                foreach( get_intermediate_image_sizes() as $s ){
                    $wp_image = wp_get_attachment_image_src( $image, $s);
                    $img->{$s} = $wp_image[0];
                }

                $img->caption = get_post($image)->post_excerpt;

                array_push($retorno, $img);

            }

        }

        return $retorno;

    }

    private function getFile($postObject, $key, $value){

        if(empty($value['multiple']) || !$value['multiple']){

            return wp_get_attachment_url(get_post_meta($postObject->ID, $key, true));

        }else{

            $files = array();
            $wpfiles = get_post_meta($postObject->ID, $key);
            foreach($wpfiles as $file){
                array_push($files, wp_get_attachment_url($file));
            }

            return $files;

        }

    }

    public function date($format){

        if(!empty($format)){
            return date($format, strtotime($this->date));
        }else{
            return $this->date;
        }

    }

} 