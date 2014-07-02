<?php
add_filter('ot_theme_mode', '__return_true');

abstract class OptionTree
{
    protected $page_title = 'Theme Options';
    protected $menu_title = 'Theme Options';
    protected $settings_id = 'theme_options';
    protected $header_logo = null;
    protected $header_version_text = null;
    protected $header_logo_link = null;
    protected $show_new_layout = false;
    protected $show_docs = false;
    protected $show_pages = false;

    protected $contextual_help
       = array(
          'content' => array(),
          'sidebar' => ''
       );
    protected $sections = array();
    protected $settings = array();

    protected $current_section = null;

    public function __construct()
    {
        add_filter('ot_settings_id', array($this, 'getSettingsId'));
        add_filter('ot_show_new_layout', array($this, 'getShowNewLayout'));
        add_filter('ot_show_docs', array($this, 'getShowDocs'));
        add_filter('ot_show_pages', array($this, 'getShowPages'));
        add_filter('ot_theme_options_page_title', array($this, 'getPageTitle'));
        add_filter('ot_theme_options_menu_title', array($this, 'getMenuTitle'));
        add_filter('ot_header_logo_link', array($this, 'getHeaderLogoLink'));
        add_filter('ot_header_version_text', array($this, 'getHeaderVersionText'));

        require_once __DIR__ . "/../vendor/valendesigns/option-tree/ot-loader.php";

        add_action('admin_init', array($this, 'admin_init'));
    }

    /**
     * @return boolean
     */
    public function getShowPages()
    {
        return $this->show_pages;
    }

    /**
     * @return string
     */
    public function getMenuTitle()
    {
        return $this->menu_title;
    }

    /**
     * @return string
     */
    public function getHeaderLogo()
    {
        return $this->header_logo;
    }

    /**
     * @return string
     */
    public function getHeaderLogoLink()
    {
        return $this->header_logo_link;
    }

    /**
     * @return string
     */
    public function getHeaderVersionText()
    {
        return $this->header_version_text;
    }

    /**
     * @return string
     */
    public function getPageTitle()
    {
        return $this->page_title;
    }

    /**
     * @return string
     */
    public function getSettingsId()
    {
        return $this->settings_id;
    }

    /**
     * @return boolean
     */
    public function getShowDocs()
    {
        return $this->show_docs;
    }

    /**
     * @return boolean
     */
    public function getShowNewLayout()
    {
        return $this->show_new_layout;
    }

    public function  admin_init()
    {
        /**
         * Get a copy of the saved settings array.
         */
        $saved_settings = get_option(ot_settings_id(), array());

        /**
         * Custom settings array that will eventually be
         * passes to the OptionTree Settings API Class.
         */
        $custom_settings = array(
           'contextual_help' => $this->contextual_help,
           'sections'        => $this->sections,
           'settings'        => $this->settings
        );

        /* allow settings to be filtered before saving */
        $custom_settings = apply_filters(ot_settings_id() . '_args', $custom_settings);

        /* settings are not the same update the DB */
        if ($saved_settings !== $custom_settings) {
            update_option(ot_settings_id(), $custom_settings);
        }
    }

    /**
     * @param $id
     * @param $title
     *
     * @return $this
     */
    public function addSection($id, $title)
    {
        $this->sections[] = array(
           'id'    => $id,
           'title' => $title,
        );

        $this->current_section = $id;

        return $this;
    }

    /**
     * @return array
     */
    public function getSettings()
    {
        return $this->settings;
    }

    /**
     * @param array $args
     *
     * @return $this
     */
    public function addOption(array $args)
    {
        $default = array(
           'id'           => '',
           'label'        => '',
           'desc'         => '',
           'std'          => '',
           'type'         => 'text',
           'section'      => '',
           'rows'         => '',
           'taxonomy'     => '',
           'min_max_step' => '',
           'class'        => '',
           'condition'    => '',
           'operator'     => 'and'
        );

        if (empty($args['section'])):
            $args['section'] = $this->current_section;
        endif;

        $args = wp_parse_args($args, $default);

        $this->settings[] = $args;

        return $this;
    }

    /**
     * @param string $id
     * @param string $label
     * @param null   $desc
     * @param null   $std
     * @param null   $section
     * @param array  $extra
     *
     * @return $this
     */
    public function addText($id, $label, $desc = null, $std = null, $section = null, array $extra = array())
    {
        $args = array(
           'id'      => $id,
           'label'   => $label,
           'std'     => $std,
           'desc'    => $desc,
           'type'    => 'text',
           'section' => $section
        );

        $args = array_merge($args, $extra);

        return $this->addOption($args);

    }

    /**
     * @param string $id
     * @param string $label
     * @param null   $desc
     * @param null   $std
     * @param null   $section
     * @param array  $extra
     *
     * @return $this
     */
    public function addTextarea($id, $label, $desc = null, $std = null, $section = null, array $extra = array())
    {
        $args = array(
           'id'      => $id,
           'label'   => $label,
           'std'     => $std,
           'desc'    => $desc,
           'type'    => 'textarea-simple',
           'rows'    => '7',
           'section' => $section,
        );

        $args = array_merge($args, $extra);

        return $this->addOption($args);

    }

    /**
     * @param       $id
     * @param       $label
     * @param null  $desc
     * @param null  $std
     * @param null  $section
     * @param array $extra
     *
     * @return $this
     */
    public function addWYSIWYG($id, $label, $desc = null, $std = null, $section = null, array $extra = array())
    {
        $args = array(
           'id'      => $id,
           'label'   => $label,
           'std'     => $std,
           'desc'    => $desc,
           'type'    => 'textarea',
           'section' => $section
        );

        $args = array_merge($args, $extra);

        return $this->addOption($args);

    }

    /**
     * @param       $id
     * @param       $label
     * @param null  $desc
     * @param null  $std
     * @param null  $section
     * @param array $extra
     *
     * @return $this
     */
    public function addUpload($id, $label, $desc = null, $std = null, $section = null, array $extra = array())
    {
        $args = array(
           'id'      => $id,
           'label'   => $label,
           'std'     => $std,
           'desc'    => $desc,
           'type'    => 'upload',
           'section' => $section,
        );

        $args = array_merge($args, $extra);

        return $this->addOption($args);

    }
}

class OPT
{
    /**
     * @var OptionTree
     */
    static private $instance = null;

    public function __construct(OptionTree $instance)
    {
        self::$instance = $instance;
    }

    /**
     * @param   string $option
     * @param null     $default
     *
     * @return null|string
     */
    public function get($option, $default = null)
    {
        return function_exists('ot_get_option') ? ot_get_option($option, $default) : $default;
    }

    /**
     * @param  string $option
     * @param null    $default
     *
     * @return void
     */
    public function _get($option, $default = null)
    {
        echo $this->get($option, $default);
    }

    /**
     * @param      $option
     * @param null $default
     *
     * @return void
     */
    public function _nl2br_get($option, $default = null)
    {
        echo nl2br($this->get($option, $default));
    }

    /**
     * @param   string $option
     * @param   string $size
     * @param null     $default
     *
     * @return null|string
     */
    public function get_optImg($option, $size, $default = null)
    {
        $id = $this->get($option, $default);
        if (empty($id)):
            return null;
        endif;

        $im = wp_get_attachment_image_src($id, $size);
        if (isset($im[0])):
            return $im[0];
        endif;

        return null;
    }

    public function __get($var)
    {
        return $this->get($var);
    }

    /**
     * @return \OptionTree
     */
    public static function getInstance()
    {
        return self::$instance;
    }

}

/**
 * @return OPT
 */
function OPT()
{
    global $OPT;
    return $OPT;
}