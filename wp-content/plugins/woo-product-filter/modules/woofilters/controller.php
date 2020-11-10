<?php
class woofiltersControllerWpf extends controllerWpf {

	protected $_code = 'woofilters';

	protected function _prepareTextLikeSearch($val) {
		$query = '(title LIKE "%'. $val. '%"';
		if(is_numeric($val)) {
			$query .= ' OR id LIKE "%'. (int) $val. '%"';
		}
		$query .= ')';
		return $query;
	}
	public function _prepareListForTbl($data){
		foreach($data as $key=>$row){
			$id = $row['id'];
			$shortcode = "[".WPF_SHORTCODE." id=".$id."]";
			$showPrewiewButton = "<button data-id='".$id."' data-shortcode='".$shortcode."' class='button button-primary button-prewiew' style='margin-top: 1px;'>".__('Prewiew', WPF_LANG_CODE)."</button>";
			$titleUrl = "<a href=".$this->getModule()->getEditLink( $id ).">".$row['title']." <i class='fa fa-fw fa-pencil'></i></a>";
			$data[$key]['shortcode'] = $shortcode;
			$data[$key]['rewiew'] = $showPrewiewButton;
			$data[$key]['title'] = $titleUrl;
		}
		return $data;
	}

	public function save(){
		$res = new responseWpf();
		if(($id = $this->getModel('woofilters')->save(reqWpf::get('post'))) != false) {
			$res->addMessage(__('Done', WPF_LANG_CODE));
			$res->addData('edit_link', $this->getModule()->getEditLink( $id ));
		} else
			$res->pushError ($this->getModel('woofilters')->getErrors());
		return $res->ajaxExec();
	}

	public function deleteByID(){
		$res = new responseWpf();

		if($this->getModel('woofilters')->delete(reqWpf::get('post')) != false){
			$res->addMessage(__('Done', WPF_LANG_CODE));
		}else{
			$res->pushError ($this->getModel('woofilters')->getErrors());
		}
		return $res->ajaxExec();
	}

	public function createTable(){
		$res = new responseWpf();
		if(($id = $this->getModel('woofilters')->save(reqWpf::get('post'))) != false) {
			$res->addMessage(__('Done', WPF_LANG_CODE));
			$res->addData('edit_link', $this->getModule()->getEditLink( $id ));
		} else
			$res->pushError ($this->getModel('woofilters')->getErrors());
		return $res->ajaxExec();
	}

	public function filtersFrontend(){
		$res = new responseWpf();
		$params = reqWpf::get('post');

		$settings = utilsWpf::jsonDecode(stripslashes($params['options']));
		$queryvars = utilsWpf::jsonDecode(stripslashes($params['queryvars']));
		$curUrl = $params['currenturl'];

		$args = $this->createArgsForFilteringBySettings($settings, $queryvars);
		//get products
		ob_start();
		$loop = new WP_Query($args);
		$loopFoundPost = $loop->found_posts;
		if ($loop->have_posts()) {
			while ($loop->have_posts()) : $loop->the_post();
				wc_get_template_part('content', 'product');
			endwhile;
		} else {
			echo __('No products found');
		}
		$productsHtml = ob_get_clean();

		//get result count
		ob_start();
		$args = array(
			'total'    => $loopFoundPost,
			'per_page' => $queryvars['posts_per_page'],
			'current'  => 1,//$queryvars['paged'],
		);
		wc_get_template( 'loop/result-count.php', $args );
		$resultCountHtml = ob_get_clean();

		//get pagination
		ob_start();
		$base    =  $queryvars['base'];

		//get query params
		$curUrl = explode( '?', $curUrl );
		$curUrl = isset($curUrl[1]) ? $curUrl[1] : '';
		//add quary params to base url
		$fullBaseUrl =  $base . '?' . $curUrl;

		$format  = '';
		$total = ceil($loopFoundPost / $queryvars['posts_per_page']);

		//after filtering we always start from 1 page
		$args = array(
			'base'         => $fullBaseUrl,
			'format'       => $format,
			'add_args'     => false,
			'current'      => 1,//$queryvars['paged'],
			'total'        => $total,
			'prev_text'    => '&larr;',
			'next_text'    => '&rarr;',
			'type'         => 'list',
			'end_size'     => 3,
			'mid_size'     => 3,
		);

		wc_get_template( 'loop/pagination.php', $args );
		$paginationHtml = ob_get_clean();
		wp_reset_postdata();

		$res->addData('productHtml', $productsHtml);
		$res->addData('paginationHtml', $paginationHtml);
		$res->addData('resultCountHtml', $resultCountHtml);

		return $res->ajaxExec();
	}

	public function order_by_popularity_post_clauses_clone( $args ) {
		global $wpdb;
		$args['orderby'] = "$wpdb->postmeta.meta_value+0 DESC, $wpdb->posts.post_date DESC";
		return $args;
	}

	public function getTaxonomyTerms(){
		$res = new responseWpf();
		$attrId = reqWpf::getVar('attr_id');

		$data = array();
		if(!is_null($attrId)) {

			$attrName = wc_attribute_taxonomy_name_by_id($attrId);
			$args = array(
				'hide_empty' => false,
			);
			$terms = get_terms( $attrName, $args);
			foreach($terms as $term ){
				if(!empty($term->term_id)){
					$data[] = array('id' => $term->term_id, 'name' => $term->name);
				}
			}
		}
		$res->addData('terms', $data);
		return $res->ajaxExec();
	}

	public function createArgsForFilteringBySettings($settings, $queryvars){
		$args = array(
			'post_status' => 'publish',
			'post_type' => 'product',
			'paged' => 1,
			'posts_per_page' => $queryvars['posts_per_page'],
			'ignore_sticky_posts' => true,
		);
		if(isset($queryvars['product_category_id'])){
			$args["tax_query"][] = array(
				'taxonomy' => 'product_cat',
				'field'    => 'id',
				'terms'    => $queryvars['product_category_id'],
				'include_children' => true
			);
		}
		$temp = array();
		foreach ($settings as $setting){
			if(!empty($setting['settings'])) {
				switch ($setting['id']){
					case 'wpfPrice':
						$priceStr = $setting['settings'][0];
						$priceVal = explode(',', $priceStr);
						if($priceVal[0] && $priceVal[1]){
							$temp['wpfPrice']["min_price"] = $priceVal[0];
							$temp['wpfPrice']["max_price"] = $priceVal[1];
						}
						break;
					case 'wpfPriceRange':
						$priceStr = $setting['settings'][0];
						$priceVal = explode(',', $priceStr);
						if($priceVal[0] && $priceVal[1]){
							$temp['wpfPrice']["min_price"] = $priceVal[0];
							$temp['wpfPrice']["max_price"] = $priceVal[1];
						}
						break;
					case 'wpfSortBy':
						switch ( $setting['settings'] ) {
							case 'title':
								$args['orderby'] = 'title';
								$args["order"] = 'ASC';
								break;
							case 'rand':
								$args['orderby'] = 'rand';
								break;
							case 'date':
								$args['orderby'] = 'date';
								break;
							case 'price':
								$args['meta_key'] = '_price';
								$args['orderby'] = 'meta_value_num';
								$args['order'] = 'ASC';
								break;
							case 'price-desc':
								$args['meta_key'] = '_price';
								$args['orderby'] = 'meta_value_num';
								$args['order'] = 'DESC';
								break;
							case 'popularity':
								$args['orderby'] = 'meta_value_num';
								$args['order'] = 'DESC';
								$args['meta_key'] = 'total_sales';
								break;
							case 'rating':
								$args['meta_key'] = '_wc_average_rating'; // @codingStandardsIgnoreLine
								$args['orderby']  = array(
									'meta_value_num' => 'DESC',
									'ID'             => 'ASC',
								);
								break;
						}
						break;
					case 'wpfCategory':
						$categoryIdStr = $setting['settings'][0];
						$args["tax_query"][] = array(
							'taxonomy' => 'product_cat',
							'field'    => 'id',
							'terms'    => $categoryIdStr,
							'include_children' => true
						);
						break;
					case 'wpfTags':
						$tagsIdStr = $setting['settings'];
						if($tagsIdStr){
							$args["tax_query"][] = array(
								'taxonomy' => 'product_tag',
								'field'    => 'id',
								'terms'    => $tagsIdStr,
								'operator' => "AND"
							);
						}
						break;
					case 'wpfAttribute':
						$attrIds = $setting['settings'];
						$taxonomy = '';
						foreach ($attrIds as $attr) {
							$term = get_term( $attr );
							$taxonomy = $term->taxonomy;
							break;
						}
						if($attrIds){
							$args["tax_query"][] = array(
								'taxonomy' => $taxonomy,
								'field'    => 'id',
								'terms'    => $attrIds,
								'operator' => (isset($setting['logic']) && $setting['logic'] == 'or' ? 'IN' : 'AND')
							);
						}
						break;
					case 'wpfAuthor':
						$authorId = $setting['settings'][0];
						if($authorId){
							$args['author'] = $authorId;
						}
						break;
					case 'wpfFeatured':
						$enable = $setting['settings'][0];
						if($enable === '1'){
							$args["tax_query"][] = array(
								'taxonomy' => 'product_visibility',
								'field'    => 'name',
								'terms'    => 'featured',
								'operator' => 'IN',
							);
						}
						break;
					case 'wpfOnSale':
						$enable = $setting['settings'][0];
						if($enable === '1'){
							$metaQuery = array(
								'relation' => 'OR',
								array( // Simple products type
									'key'           => '_sale_price',
									'value'         => 0,
									'compare'       => '>',
									'type'          => 'numeric'
								),
								array( // Variable products type
									'key'           => '_min_variation_sale_price',
									'value'         => 0,
									'compare'       => '>',
									'type'          => 'numeric'
								)
							);
							$args['meta_query'][] = $metaQuery;
						}
						break;
					case 'wpfInStock':
						$enable = $setting['settings'][0];
						if($enable === '1'){
							$metaQuery = array(
								'key' => '_stock_status',
								'value' => 'instock'
							);
							$args['meta_query'][] = $metaQuery;
						}
						break;
					case 'wpfRating':
						$ratingRange = $setting['settings'];
						if(is_array($ratingRange)){
							foreach($ratingRange as $range){
								$range = explode('-', $range);
								break;
							}
							if(intval($range[1]) !== 5){
								$range[1] = $range[1] - 0.001;
							}
							if($range[0] && $range[1]){
								$metaQuery = array(
									'key' => '_wc_average_rating',
									'value' => array($range[0], $range[1]),
									'type' => 'DECIMAL',
									'compare' => 'BETWEEN'
								);
								$args['meta_query'][] = $metaQuery;
							}
						}
						break;
				}
			}
		}
		dispatcherWpf::doAction('addArgsForFilteringBySettings', $settings);
		
		if(isset($args["tax_query"])) {
			$args["tax_query"]['relation'] = 'AND';
		}
		if(!empty($temp['wpfPriceRange']['min_price']) && !empty($temp['wpfPriceRange']['max_price'])) {
			$minPrice = $temp['wpfPriceRange']['min_price'];
			$maxPrice = $temp['wpfPriceRange']['max_price'];
		}else if(!empty($temp['wpfPrice']['min_price']) && !empty($temp['wpfPrice']['max_price'])){
			$minPrice = $temp['wpfPrice']['min_price'];
			$maxPrice = $temp['wpfPrice']['max_price'];
		}
		if( isset($temp['wpfPriceRange']) || isset($temp['wpfPrice'])){
			$metaQuery = array(
				'key' => '_price',
				'value' => array($minPrice, $maxPrice),
				'compare' => 'BETWEEN',
				'type' => 'NUMERIC'
			);
			$args['meta_query'][] = $metaQuery;
		}
		return $args;
	}



}

