<?php
/**
 * Created by PhpStorm.
 * User: sergiovilar
 * Date: 03/05/14
 * Time: 02:40 PM
 */

class App {

	private $smtp;
	private $taxonomies = array();
	private $models = array();
	private $terms = array();
	public $option;

	public function __construct(){
		$this->option = new stdClass();
	}

	public function registerModel($model){

		$modelName = str_replace('model', '', strtolower(get_class($model)));

		$modelItem = new Model($model);
		$this->{$modelName} = $modelItem;

		array_push($this->models, $modelItem);

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
	public function registerTaxonomy($taxonomy, $singular, $plural, $hierarchical = true){

		$arr = array('key'=> $taxonomy, 'singular' => $singular, 'plural' => $plural, 'hierarchical' => $hierarchical);
		array_push($this->taxonomies, $arr);

	}

	/**
	 * Register all taxonomies
	 */
	public function registerTaxonomies(){

		foreach($this->taxonomies as $tax){

			$post_type = array();

			foreach($this->models as $model){

				if($model->getAppModel()->getTaxonomies() && in_array($tax['key'], $model->getAppModel()->getTaxonomies())){
					array_push($post_type, $model->getAppModel()->getPostType());
				}

			}

			if(count($post_type) > 0){
				new AppTaxonomy($tax, $post_type);
			}

		}

		add_action('init', array($this, 'registerTerms'), 1);

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

		if(!is_int($parent)){
			$parent = $this->getIdBySlug($parent);
		}

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

	public function registerTerm($taxonomy, $slug, $term){

		$item = new stdClass();
		$item->taxonomy = $taxonomy;
		$item->slug = $slug;
		$item->term = $term;

		array_push($this->terms, $item);

	}

	public function registerTerms(){

		foreach($this->terms as $item){

			if(!term_exists($item->slug, $item->taxonomy)){

				wp_insert_term($item->term, $item->taxonomy, array('slug'=> $item->slug));

			}

		}

	}

	public function getIdBySlug($page_slug) {
		$page = get_page_by_path($page_slug);
		if ($page) {
			return $page->ID;
		} else {
			return null;
		}
	}

}
