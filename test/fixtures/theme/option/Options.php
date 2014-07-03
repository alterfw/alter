<?php
/**
 * Created by PhpStorm.
 * User: sergiovilar
 * Date: 07/05/14
 * Time: 08:00 PM
 */

class Opcoes extends OptionPage{

    protected $title = 'Options';
    protected $capability = 'switch_themes';

    protected $fields = array(

        'address' => array(
            'label' => 'Address',
            'type'  => 'text'
        ),

        'contact_email' => array(
            'label' => 'Email',
            'type'  => 'text'
        ),

    );

} 