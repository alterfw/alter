<?php
/**
 * Created by PhpStorm.
 * User: sergiovilar
 * Date: 04/05/14
 * Time: 10:52 AM
 */

class Helper {

    // Path methods

    public function img(){
        return ALTER_IMG;
    }

    public function css(){
        return ALTER_CSS;
    }

    public function js(){
        return ALTER_JS;
    }

    public function theme(){
        return THEME_PATH;
    }

    // Utility methods

    public function title(){

        global $page, $paged;

        wp_title( '|', true, 'right' );
        bloginfo( 'name' );

        $site_description = get_bloginfo( 'description', 'display' );
        if ( $site_description && ( is_home() || is_front_page() ) )
            echo " | $site_description";

        if ( $paged >= 2 || $page >= 2 )
            echo ' | ' . sprintf( __( 'Page %s', 'twentyten' ), max( $paged, $page ) );

    }

    public function name(){
        return get_bloginfo('name');
    }

    public function domain(){
        return $_SERVER['HTTP_HOST'];
    }

    public function url(){
        return get_bloginfo('url')."/";
    }

    function menu($menu){
        wp_nav_menu( array('menu' => $menu,'container'=>'false' ));
    }

    function option($option){
        return get_option($option);
    }

    function breadcrumb($home = 'Home', $separator = "/", $el = ''){

        $el_b = '';
        $el_e = '';

        global $post;

        if(!empty($el)){

            $el_b = '<'.$el.'>';
            $el_e = '</'.$el.'>';

        }

        if (!is_home()) {

            echo $el_b;
            echo '<a href="'.$this->url().'">'.$home.' '.$separator.'</a>';
            echo $el_e;

            if(get_post_type( $post ) == 'post'){

                if (is_category() || is_single()) {

                    $cats = get_the_category();

                    echo $el_b;
                    echo "<a href='".$this->url().$cats[0]->slug."'>".$cats[0]->name."</a>";
                    echo $el_e;

                }

            }
                
            if (is_single()) {

                echo $el_b;
                the_title();
                echo $el_e;

            } elseif (is_page()) {

                global $post;

                if($post->post_parent > 0){

                    echo $el_b;
                    echo "<a href='".get_permalink($post->post_parent)."'> ".$separator." ";
                    echo get_the_title($post->post_parent);
                    echo "</a>";
                    echo $el_e;

                }

                echo $el_b;                
                echo the_title();                
                echo $el_e;
            }
        }

    }

} 