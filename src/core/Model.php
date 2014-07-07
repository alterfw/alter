<?php
/**
 * Created by PhpStorm.
 * User: Desenvolvimento
 * Date: 07/07/14
 * Time: 18:04
 */

class Model {

	private $post_type;
	private $appModel;

	public function __construct($appModel){
		$this->post_type = $appModel->getPostType();
		$this->appModel = $appModel;
	}

	/**
	 * Find posts in the Wordpress database using WP_Query
	 *
	 * @param $options
	 * @return bool|WP_Query
	 */
	public function find($options = null){

		try{

			$attrs = $this->buildQuery($options);

			if(empty($attrs['limit'])){
				$attrs['limit'] = -1;
			}

			$qr = new WP_Query($attrs);

			if(!$qr->have_posts()){
				throw new NoPostFoundException();
			}else{

				$posts = array();

				while($qr->have_posts()){

					$qr->the_post();

					$obj = new PostObject(get_post(get_the_ID()), $this->appModel);
					array_push($posts, $obj);

				}

				return $posts;

			}

		}catch(NoPostFoundException $e){
			return false;
		}

	}

	public function findById($id){
		return new PostObject(get_post($id), $this->appModel);
	}

	public function findBySlug($slug){

		$args = array(
			'name' => $slug,
			'post_type' => $this->post_type,
			'post_status' => 'publish',
			'numberposts' => 1
		);

		$my_posts = get_posts($args);

		if( $my_posts ) {
			return $this->findById($my_posts[0]->ID);
		}else{
			return false;
		}

	}

	public function findByTaxonomy($taxonomy, $value, $limit){

		$options = array();

		if(empty($limit)){
			$limit = get_option('posts_per_page');
		}

		$options['posts_per_page'] = $limit;

		$options['tax_query'] = array(
			array(
				'taxonomy' => $taxonomy,
				'field' => 'slug',
				'terms' => $value
			)
		);

		return $this->find($options);

	}

	public function paginate($limit = null, $paged = null){

		if($paged == null){
			$paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
		}

		if(empty($limit)){
			$limit = get_option('posts_per_page');
		}

		return $this->find(array('limit' => $limit, 'query' => 'paged='.$paged));

	}

	private function buildQuery($options = null){

		$attrs = $this->getDefaultQuery();

		if(!empty($options)){

			if(is_int($options)){
				$options = array('limit' => $options);
			}

			// Posts limit
			if(!empty($options['limit'])){
				$attrs['posts_per_page'] = $options['limit'];
			}

			// Check if has a manual query

			if(is_array($options)){
				foreach($options as $key => $value){
					$attrs[$key] = $value;
				}
			}

			if(!empty($options['query'])){

				$arr = explode('&', $options['query']);

				foreach($arr as $item){

					$arr_item = explode('=', $item);
					$attrs[$arr_item[0]] = $arr_item[1];

				}

			}

		}

		return $attrs;

	}

	/**
	 * Create a default query
	 *
	 * @return array
	 */
	private function getDefaultQuery(){

		return array(
			'post_type'     => $this->post_type,
			'post_status'   => 'publish'
		);

	}

} 