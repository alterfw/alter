<?php
/**
 * Created by PhpStorm.
 * User: sergiovilar
 * Date: 03/05/14
 * Time: 02:40 PM
 */

class App {

    private $smtp;

    public function registerModel($model){

        $modelName = str_replace('model', '', strtolower(get_class($model)));
        $this->{$modelName} = $model;

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

        if($this->stmp->ssl){
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