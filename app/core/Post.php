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
                    $this->{$key} = get_post_meta($postObject->ID, $key, true);
                }

            }

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