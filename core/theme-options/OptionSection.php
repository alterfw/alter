<?php

/**
 * Class OptionSection
 * Add a section in the theme options.
 *
 * @author vinicius73 <vinicius73@mail.com>
 */
abstract class OptionSection
{

    public $title = 'General';
    public $section_id;

    public $fields = array();

    public function __construct()
    {
        $this->parseFields();
    }

    /**
     * Adds fields to section
     */
    private function parseFields()
    {
        $section = OPT::getInstance()->addSection($this->getSectionId(), $this->title);

        foreach ($this->fields as $key => $field):
            $field['id'] = $key;
            $section->addOption($field);
        endforeach;
    }

    /**
     * Id section
     *
     * @return string
     */
    protected function getSectionId()
    {
        if (empty($this->section_id)):
            $this->section_id = strtolower(get_class($this));
        endif;

        return $this->section_id;
    }

}