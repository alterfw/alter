<?php
/**
 * Created by PhpStorm.
 * User: sergiovilar
 * Date: 07/05/14
 * Time: 07:49 PM
 */

abstract class OptionPage extends WD_Creator_Page_TopLevel{

    public function __construct(){

        $this->setCapability($this->capability);
        parent::__construct($this->title);

        $this->parseFields();

    }

    private function parseFields(){

        if(!empty($this->fields))

        foreach($this->fields as $key => $value){

            switch($value['type']){

                case 'text':
                    $obj = Form::text($key);
                    break;

                case 'long_text':
                    $obj = Form::textarea($key);
                    break;

                default:
                    $obj = Form::text($key);

            }

            $obj->setLabel($value['label']);

            $this->add($obj);

        }

        $this->init();

    }

} 