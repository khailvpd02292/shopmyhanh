<!DOCTYPE html>
<html <?php language_attributes();?>>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<!-- <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" /> -->
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<link rel="stylesheet" href="">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
	<div id="shop_container" class="shop_container">
		<header class="shop_header header trans_300" style="top: 0px;">

			<!-- Top Navigation -->

			<div class="top_nav">
				<div class="container">
					<div class="row">
						<div class="col-md-6">
							<div class="top_nav_left">Miễn Phí Ship trong khu vực</div>
						</div>
						<div class="col-md-6 text-right">
							<div class="top_nav_right">
								<ul class="top_nav_menu">
								</ul>
							</div>
						</div>
					</div>
				</div>
			</div>
			
			<!-- Main Navigation -->

			<div class="main_nav_container">
				<div class="container">
					<div class="row">
						<div class="col-lg-12 text-right">
							<div class="logo_container">
								<a href="<?php echo esc_url(home_url()); ?>">mỹhạnh<span>shop</span></a>
							</div>
							<nav class="navbar">
								<?php 

									
										$menu = array(
											
											'theme_location' => 'primary-menu',
											'items_wrap'=>'<ul class="navbar_menu">%3$s</ul>'
										);
										wp_nav_menu($menu);
									
								?>
								
								<ul class="navbar_user">
									<li class="shop-search"><a href="#"><i class="fa fa-search" aria-hidden="true"></i></a></li>
									<li class="shop-account">
										<?php echo shop_tiny_account(); ?>
									</li>
									<li class="shop-checkout">
										<?php echo shop_tiny_cart(); ?>
										
									</li>
									
									<li class="shop_bars"> 
										<div class="shop-bars"> <a href="#"><i class="fa fa-bars" aria-hidden="true"></i></a>
										</div>
									</li>
								</ul>
								
							</nav>
							<!-- <div class="shop-hamburger"> <a href="#"><i class="fa fa-bars" aria-hidden="true"></i></a>
								</div> -->
						</div>
					</div>
				</div>
			</div>
			

		</header>
		<div class="shop_bars_menu">
			<div class="shop_bars_close"><i class="fa fa-times" aria-hidden="true"></i></div>
			<div class="shop_bars_menu_content text-right">
				<ul class="menu_top_nav">
					<?php 

									
							$menu = array(
								
								'theme_location' => 'primary-menu',
								'items_wrap'=>'<ul class="navbar_menu_bars">%3$s</ul>'
							);
							wp_nav_menu($menu);
						
					?>
				</ul>
			</div>
		</div>
	<?php if(is_home()): ?>
		<?php get_template_part( 'templates-parts/content', 'slider' );?>
	<?php elseif(is_singular('product') || is_shop() || is_product_category() || is_product_tag() ): ?>

	<?php else: ?>
		<div class="shop-banner-area">
			<div class="container">
				<div class="row">
					<div class="col">
						<div class="breadcrumbs align-items-nav flex-row">
							<ul>

								<?php wp_breadcrumbs(); ?>
								
							</ul>
						</div>

					</div>
				</div>				
			</div>
		</div>
	<?php endif;?>
	
