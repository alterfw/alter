<?php

class AppTheme
{
    protected $imagesSize = array();
    protected $menus = array();
    protected $themeSupport = array();
    protected $postSupport = array();
    protected $css = array();
    protected $js = array();
    protected $js_enqueue = array();
    protected $jQueryCDN
       = array(
          'cdn_url'  => null,
          'fallback' => null
       );

    public static $jQueryCDN_URL = '//ajax.googleapis.com/ajax/libs/jquery/%1s/jquery.min.js';
    public static $GOOGLE_ANALYTICS_ID = false;
    public static $add_jquery_fallback = false;

    public function __construct()
    {
        add_action('after_setup_theme', array($this, '_after_setup_theme'));

        add_action('wp_enqueue_scripts', array($this, '_wp_enqueue_scripts'), 100);

        add_action('wp_footer', array($this, '_google_analytics'), 20);

    }


    /**
     * @param string $location
     * @param string $description
     *
     * @return $this
     */
    public function addMenu($location, $description)
    {
        $this->menus[$location] = $description;

        return $this;
    }

    /**
     * @param string $name
     * @param int    $width
     * @param int    $height
     * @param bool   $crop
     *
     * @return $this
     */
    public function addImageSize($name, $width = 0, $height = 0, $crop = false)
    {
        $size = array(
           'name'   => $name,
           'width'  => $width,
           'height' => $height,
           'crop'   => $crop
        );

        $this->imagesSize[] = $size;

        return $this;
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function addThemeSupport($feature)
    {
        $this->themeSupport[] = $feature;

        return $this;
    }

    /**
     * @param string $post_type
     * @param string $feature
     *
     * @return $this
     */
    public function addPostTypeSupport($post_type, $feature)
    {
        $this->postSupport[$post_type] = $feature;

        return $this;
    }

    /**
     * @param string $handle
     * @param string $src
     * @param array  $deps
     * @param bool   $ver
     * @param string $media
     *
     * @return $this
     */
    public function addCss($handle, $src = false, $deps = array(), $ver = null, $media = 'all')
    {
        $this->css[] = array(
           'handle' => $handle,
           'src'    => $this->prepUrl($src),
           'deps'   => $deps,
           'ver'    => $ver,
           'media'  => $media
        );

        return $this;
    }

    /**
     * @param string $handle
     * @param string $src
     * @param array  $deps
     * @param bool   $ver
     * @param bool   $in_footer
     *
     * @return $this
     */
    public function addJs($handle, $src, $deps = array(), $ver = null, $in_footer = false)
    {
        $this->js[] = array(
           'handle'    => $handle,
           'src'       => $this->prepUrl($src),
           'deps'      => $deps,
           'ver'       => $ver,
           'in_footer' => $in_footer
        );

        $this->addJsEnqueue($handle);

        return $this;
    }

    /**
     * @param string $handle
     * @param string $src
     * @param array  $deps
     * @param bool   $ver
     *
     * @return $this
     */
    public function addJsToFooter($handle, $src, $deps = array(), $ver = null)
    {
        return $this->addJs($handle, $src, $deps, $ver, true);
    }

    /**
     * @param string $handle
     * @param string $src
     * @param array  $deps
     * @param bool   $ver
     *
     * @return $this
     */
    public function addJsToHead($handle, $src, $deps = array(), $ver = null)
    {
        return $this->addJs($handle, $src, $deps, $ver, null);
    }

    /**
     * @param string $handle
     * @param bool   $src
     * @param array  $deps
     * @param bool   $ver
     * @param bool   $in_footer
     *
     * @return $this
     */
    public function addJsEnqueue($handle, $src = false, $deps = array(), $ver = false, $in_footer = false)
    {
        $this->js_enqueue[] = array(
           'handle'    => $handle,
           'src'       => $src,
           'deps'      => $deps,
           'ver'       => $ver,
           'in_footer' => $in_footer
        );

        return $this;
    }

    /**
     * @param string $version
     * @param bool   $fallback
     *
     * @return $this
     */
    public function setJqueryCDNSupport($version, $fallback = false)
    {
        $cdnUrl = sprintf(self::$jQueryCDN_URL, $version);

        $this->jQueryCDN['cdn_url']  = $cdnUrl;
        $this->jQueryCDN['fallback'] = ($fallback) ? $this->prepUrl($fallback) : null;

        return $this;
    }

    /**
     * @param string $id
     *
     * @return $this
     */
    public function setGoogleAnalytcsID($id)
    {
        self::$GOOGLE_ANALYTICS_ID = $id;

        return $this;
    }

    public function _google_analytics()
    {
        $GOOGLE_ANALYTICS_ID = self::$GOOGLE_ANALYTICS_ID;

        if (!empty($GOOGLE_ANALYTICS_ID)) {
            echo <<< EOT
<script>
	(function(b,o,i,l,e,r){b.GoogleAnalyticsObject=l;b[l]||(b[l]=
	function(){(b[l].q=b[l].q||[]).push(arguments)});b[l].l=+new Date;
	e=o.createElement(i);r=o.getElementsByTagName(i)[0];
	e.src='//www.google-analytics.com/analytics.js';
	r.parentNode.insertBefore(e,r)}(window,document,'script','ga'));
	ga('create','{$GOOGLE_ANALYTICS_ID}');ga('send','pageview');
</script>
EOT;
        }
    }

    /**
     * @param array $data
     *
     * @return $this
     */
    public function setJsEnqueue(array $data)
    {
        $this->js_enqueue = array();

        $default = array(
           'src'       => false,
           'deps'      => array(),
           'ver'       => false,
           'in_footer' => false
        );

        if ($this->ArrayIsMulti($data)):
            foreach ($data as $enqueue):
                $this->js_enqueue[] = wp_parse_args($enqueue, $default);
            endforeach;
        else:
            foreach ($data as $handle):
                $this->js_enqueue[] = wp_parse_args(array('handle' => $handle), $default);
            endforeach;
        endif;

        return $this;
    }

    public function _after_setup_theme()
    {
        // Menus
        foreach ($this->menus as $location => $description):
            register_nav_menu($location, $description);
        endforeach;

        // Images
        foreach ($this->imagesSize as $size):
            add_image_size($size['name'], $size['width'], $size['height'], $size['crop']);
        endforeach;

        // Theme Support
        foreach ($this->themeSupport as $feature):
            add_theme_support($feature);
        endforeach;

        // Post Type Support
        foreach ($this->postSupport as $post_type => $feature):
            add_post_type_support($post_type, $feature);
        endforeach;
    }

    public function _wp_enqueue_scripts()
    {
        if (!empty($this->jQueryCDN['cdn_url'])):
            wp_deregister_script('jquery');
            wp_register_script('jquery', $this->jQueryCDN['cdn_url'], false, null, false);
            wp_enqueue_script('jquery');
            if (!empty($this->jQueryCDN['fallback'])):
                add_filter('script_loader_src', array($this, '_jquery_local_fallback'), 10, 2);
            endif;
        endif;

        //CSS
        foreach ($this->css as $css):
            wp_enqueue_style($css['handle'], $css['src'], $css['deps'], $css['ver'], $css['media']);
        endforeach;
        //JS
        foreach ($this->js as $js):
            wp_register_script($js['handle'], $js['src'], $js['deps'], $js['ver'], $js['in_footer']);
        endforeach;
        //JS Enqueue
        foreach ($this->js_enqueue as $enqueue):
            wp_enqueue_script($enqueue['handle'], $enqueue['src'], $enqueue['deps'], $enqueue['ver'], $enqueue['in_footer']);
        endforeach;
    }

    public function _jquery_local_fallback($src, $handle = null)
    {
        if (self::$add_jquery_fallback):
            echo '<script>window.jQuery||document.write(\'<script src="' . $this->jQueryCDN['fallback'] . '"><\/script>\')</script>' . "\n";
            self::$add_jquery_fallback = false;
        endif;

        if ($handle === 'jquery'):
            self::$add_jquery_fallback = true;
        endif;

        return $src;
    }

    /**
     * @param $array
     *
     * @return bool
     */
    private function ArrayIsMulti(array $array)
    {
        foreach ($array as $v) {
            if (is_array($v)) return true;
        }
        return false;
    }

    /**
     * @param string $path
     *
     * @return string
     */
    private function prepUrl($path)
    {
        $info = parse_url($path);

        if (!isset($info['host'])):
            return THEME_PATH . '/' . trim($info['path'], '/');
        endif;

        return trim($path, '/');
    }
}
