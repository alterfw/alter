<?php
/**
 * Created by PhpStorm.
 * User: sergiovilar
 * Date: 04/05/14
 * Time: 09:04 PM
 */

class FeatureModel extends AppModel{

    public $icon = "dashicons-star-empty";

    public $fields = array(

        'title'         => true,
        'thumbnail'     => true,

        'subtitle'     => array(
            'label'     => 'Subtitle',
            'type'      => 'text',
            'required'  => true
        ),

        'link'          => array(
            'label'     => 'Link',
            'type'      => 'text',
            'required'  => true
        )

    );

} 