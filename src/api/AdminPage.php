<?php
/**
 * Created by PhpStorm.
 * User: sergiovilar
 * Date: 04/05/14
 * Time: 09:59 PM
 */

class AdminPage extends WD_Creator_Page_TopLevel{

    private $conteudo;

    public function __construct($title, $cap, $content){

        $this->conteudo = $content;
        $this->setCapability($cap);

        parent::__construct($title);

        $this->init();

    }

    public function render(){

        $html[] = Html::tag('h2')->setContent($this->pageTitle);
        $html[] = $this->conteudo;

        return Html::tag('div', join($html))->setClass('wrap wd-page')->setId('wd-page-' . $this->id);
    }

} 