<?php  get_header(); ?>

<?php 
	get_template_part( 'templates-parts/content', 'banner' );	
	
	if( is_active_sidebar('boder-sidebar') ){
		dynamic_sidebar('boder-sidebar' );	
	}

?>

	<div class="shop_deal_ofthe_week">
		<div class="container">
			<div class="row align-items-center">
				<?php if(is_active_sidebar('deal-widget')){
					dynamic_sidebar('deal-widget');
				} ?>
				
			</div>
		</div>
	</div>
	
	<div class="shop_best_sellers">
		<div class="container">
			<?php if(is_active_sidebar('seller-sidebar')){
				dynamic_sidebar('seller-sidebar');
			} ?>		
		</div>
	</div>

	<div class="shop_benefit">
		<div class="container">
			<!-- <div class="row benefit_row">
				<div class="col-lg-3 shop_benefit_col">
					<div class="shop_benefit_item align-items-nav flex-row">
						<div class="benefit_icon"><i class="fa fa-truck" aria-hidden="true"></i></div>
						<div class="benefit_content">
							<h6>free shipping</h6>
							<p>Suffered Alteration in Some Form</p>
						</div>
					</div>
				</div>

				<div class="col-lg-3 shop_benefit_col">
					<div class="shop_benefit_item align-items-nav flex-row">
						<div class="benefit_icon"><i class="fa fa-money" aria-hidden="true"></i></div>
						<div class="benefit_content">
							<h6>cach on delivery</h6>
							<p>The Internet Tend To Repeat</p>
						</div>
					</div>
				</div>

				<div class="col-lg-3 shop_benefit_col">
					<div class="shop_benefit_item align-items-nav flex-row">
						<div class="benefit_icon"><i class="fa fa-undo" aria-hidden="true"></i></div>
						<div class="benefit_content">
							<h6>45 days return</h6>
							<p>Making it Look Like Readable</p>
						</div>
					</div>
				</div>

				<div class="col-lg-3 shop_benefit_col">
					<div class="shop_benefit_item align-items-nav flex-row">
						<div class="benefit_icon"><i class="fa fa-clock-o" aria-hidden="true"></i></div>
						<div class="benefit_content">
							<h6>opening all week</h6>
							<p>8AM - 09PM</p>
						</div>
					</div>
				</div>
			</div> -->
		</div>
	</div>

	<div class="shop-blog-section">
		<div class="container">
			<?php if(is_active_sidebar('blog-widget')){
				dynamic_sidebar('blog-widget');
			} ?>
		</div>
	</div>

	

	
<?php get_footer(); ?>

<script>
	product-name
</script>

