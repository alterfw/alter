<?php


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
     * @param string $option
     * @param string $default
     *
     * @return null|string
     */
    public function get($option, $default = null)
    {
        return function_exists('ot_get_option') ? ot_get_option($option, $default) : $default;
    }

    /**
     * @param string $option
     * @param string $default
     *
     * @return void
     */
    public function _get($option, $default = null)
    {
        echo $this->get($option, $default);
    }

    /**
     * @param        $option
     * @param string $default
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
     * @param string   $default
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

/***
 * Class ThemeOptionsBase
 */
class ThemeOptionsEmpty extends OptionTree
{
    protected $page_title = 'Theme Options';
    protected $menu_title = 'Theme Options';
}

/**
 * Load Theme Options
 */
function loadThemeOptioninstance()
{
    global $OPT;
    $file = get_template_directory() . '/ThemeOptions.php';

    if (file_exists($file)):
        require_once($file);
        $themeOption = new ThemeOptions();
    else:
        $themeOption = new ThemeOptionsEmpty();
    endif;

    $OPT = new OPT($themeOption);
}

/**
 * @return OPT
 */
function OPT()
{
    global $OPT;
    return $OPT;
}