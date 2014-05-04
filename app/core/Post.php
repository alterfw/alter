<?php
/**
 * Created by PhpStorm.
 * User: sergiovilar
 * Date: 03/05/14
 * Time: 02:09 PM
 */

class PostObject {

    public function __construct($postObject, $fields){

        $object = [];

        $modelDefaultFields = array('title', 'thumbnail', 'editor');
        $valueTypes = array('text', 'long_text', 'int', 'float', 'boolean');

        // Post default properties
        foreach($postObject as $key => $value){
            $chave = str_replace('post_', '', $key);
            $this->{$chave} = $value;
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

                            $this->{$key} = $this->getImage($postObject, $key);

                            break;

                        case 'file':

                            $this->{$key} = get_attached_file($key);

                            break;

                    }

                }

            }

        }

    }

    private function getImage($postObject, $key){

        $retorno = [];
        $images = get_post_meta($postObject->ID, $key);

        foreach($images as $image){

            $img = new stdClass();

            foreach( get_intermediate_image_sizes() as $s ){
                $wp_image = wp_get_attachment_image_src( $image, $s);
                $img->{$s} = $wp_image[0];
            }

            array_push($retorno, $img);

        }

        return $retorno;

    }

    public function date($format){

        if(!empty($format)){
            return date($format, strtotime($this->date));
        }else{
            return $this->date;
        }

    }

} 