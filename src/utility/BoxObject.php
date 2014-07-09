<?php

class BoxObject
{
    protected $pages = array();
    protected $info = array();
    protected $id = null;
    protected $fields = array();


    public function __construct($pages, array $info = array(), array $fields = array())
    {
        $this->pages = (array)$pages;

        $infosDefault = array(
           'title'    => __('More') . ' ' . __('About'),
           'pages'    => array(),
           'context'  => 'normal',
           'priority' => 'high',
           'autosave' => true,
        );

        $this->info = wp_parse_args($info, $infosDefault);

        $fields = $this->parseFields($fields);

        $this->fields = $fields;
    }

    /**
     * @param array $_fields
     *
     * @return array
     */
    public function parseFields(array $_fields)
    {
        $_fields       = $this->clearFields($_fields);
        $fields        = array();
        $_typeText     = array('float');
        $_typeTextArea = array('long_text');
        $_typeImage    = array('image');
        $_typeSelect   = array('list');
        $_typeCheckbox = array('boolean');
        $_typeNumber   = array('int');

        foreach ($_fields as $key => $field):
            //ID
            if (!isset($field['id'])) $field['id'] = $key;

            //Label
            if (!isset($field['name'])):
                if (isset($field['label'])):
                    $field['name'] = $field['label'];
                    unset($field['label']);
                else:
                    $field['name'] = $key;
                endif;
            endif;

            //Type
            if (!isset($field['type']) or empty($field['type'])) $field['type'] = 'text';

            //Text
            if (in_array($field['type'], $_typeText)):
                $field['type'] = 'text';
            //Textarea
            elseif (in_array($field['type'], $_typeTextArea)):
                $field['type'] = 'textarea';
            //Select
            elseif (in_array($field['type'], $_typeSelect)):
                $field['type'] = 'select';
            //Checkbox
            elseif (in_array($field['type'], $_typeCheckbox)):
                $field['type'] = 'checkbox';
            //Number
            elseif (in_array($field['type'], $_typeNumber)):
                $field['type'] = 'number';
            //Image
            elseif (in_array($field['type'], $_typeImage)):
                $multiple      = (isset($field['multiple'])) ? $field['multiple'] : false;
                $field['type'] = ($multiple) ? 'plupload_image' : 'image';
            endif;

            array_push($fields, $field);
        endforeach;

        return $fields;
    }

    /**
     * @param array $fields
     *
     * @return array
     */
    private function clearFields(array $fields)
    {
        $remove = array('title', 'editor', 'thumbnail', 'comments');
        foreach ($remove as $r):
            if (isset($fields[$r])) unset($fields[$r]);
        endforeach;
        return $fields;
    }

    /**
     * @return array
     */
    public function __toArray()
    {
        $box = $this->info;

        if (!isset($box['id'])) $box['id'] = implode('_', $this->pages);
        $box['pages'] = array_merge($box['pages'], $this->pages);

        $box['fields'] = $this->fields;

        return $box;
    }

    /**
     * @return array
     */
    public function getBox()
    {
        return $this->__toArray();
    }
}