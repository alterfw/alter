<?php
/**
 * Created by PhpStorm.
 * User: sergiovilar
 * Date: 03/05/14
 * Time: 02:40 PM
 */

class App {

    private $smtp;
    private $taxonomies = [];
    private $models = [];
    public $option;

    public function __construct(){
        $this->option = new stdClass();
    }

    public function registerModel($model){

        $modelName = str_replace('model', '', strtolower(get_class($model)));
        $this->{$modelName} = $model;

        array_push($this->models, $model);

    }

    public function registerOption($option){
        $this->option->{strtolower(get_class($option))} = $option;
    }

    /**
     * Add a taxonomy
     *
     * @param $taxonomy
     * @param $singular
     * @param $plural
     */
    public function registerTaxonomy($taxonomy, $singular, $plural){

        $arr = array('key'=> $taxonomy, 'singular' => $singular, 'plural' => $plural);
        array_push($this->taxonomies, $arr);

    }

    /**
     * Register all taxonomies
     */
    public function registerTaxonomies(){

        foreach($this->taxonomies as $tax){

            $post_type = [];

            foreach($this->models as $model){

                if($model->getTaxonomies() && in_array($tax['key'], $model->getTaxonomies())){
                    array_push($post_type, $model->getPostType());
                }

            }

            if(count($post_type) > 0){
                new AppTaxonomy($tax, $post_type);
            }

        }

    }

    public function SMTP($host, $login, $password, $port = 587, $ssl = false){

        $this->smtp = new stdClass();

        $this->smtp->host = $host;
        $this->smtp->login = $login;
        $this->smtp->password = $password;
        $this->smtp->port = $port;
        $this->smtp->ssl = $ssl;

        add_action( 'phpmailer_init', array($this, 'configureSMTP'));

    }

    public function configureSMTP( PHPMailer $phpmailer){

        $phpmailer->Host = $this->smtp->host;
        $phpmailer->Port = $this->smtp->port; // could be different
        $phpmailer->Username = $this->smtp->login; // if required
        $phpmailer->Password = $this->smtp->password; // if required
        $phpmailer->SMTPAuth = true; // if required

        if($this->smtp->ssl){
            $phpmailer->SMTPSecure = 'ssl'; // enable if required, 'tls' is another possible value
        }

        $phpmailer->IsSMTP();

    }

    public function defaultPage($title, $slug, $parent = 0, $content = ''){

        $_page = get_page_by_title($title);

        // Check if page exists
        if(!$_page || $_page->post_status == 'trash'){

            $page = wp_insert_post(array(
                'post_type'     => 'page',
                'post_status'   => 'publish',
                'post_title'    => $title,
                'post_name'     => $slug,
                'post_content'  => $content,
                'post_parent'   => $parent
            ));

            return $page;

        }else{
            return false;
        }

    }

}
