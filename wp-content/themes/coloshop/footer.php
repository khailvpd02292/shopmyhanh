	<?php if(!is_home()): ?>
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
	<?php  endif; ?>
	<!-- <div class="newsletter">
		<div class="container">
			<div class="row">
				<div class="col-lg-6">
					<div class="newsletter_text align-items-newlet">
						<h4>Newsletter</h4>
						<p>Subscribe to our newsletter and get 20% off your first purchase</p>
					</div>
				</div>
				<div class="col-lg-6">
					<div class="newsletter_form align-items-newlet flex-md-row flext-content">
						<?php echo do_shortcode('[contact-form-7 id="115" title="Contact"]'); ?>
					</div>
				</div>
			</div>
		</div>
	</div> -->
	
	<?php get_template_part('templates-parts/search', 'dialog'); ?>

	<footer class="footer">
		<div class="container">
			<div class="row">
				<!-- <div class="col-lg-6">
					<div class="footer_nav_container align-items-newlet">
						<?php 
							$menu = array(
									'theme_location' => 'footer-menu',
									'items_wrap'=>'<ul class="navbar_menu footer-menu-nav">%3$s</ul>'
								);
							
							wp_nav_menu($menu);
						?>
					</div>
				</div> -->
				<!-- <div class="col-lg-6">
					<div class="footer_social ">
						<?php 
							$menu = array(
								'theme_location' => 'social-menu',
								'items_wrap'=>'<ul class="social">%3$s</ul>'
							);
							
							wp_nav_menu($menu);
						?>
					</div>
				</div> -->
			</div>
			<div class="row">
				<div class="col-lg-12">
					<div class="footer_nav_container">
						<div class="cr">©2020 All Rights Reserverd. khailvpd02292@gmail.com <i class="fa fa-heart-o" aria-hidden="true"></i> by <a href="#">KhaiLe</a></div>
					</div>
				</div>
			</div>
		</div>
	</footer>
	
	<span id="top-link-block" class="affix"> <a href="#top" class="back2top" onclick="jQuery('html,body').animate({scrollTop:0},'slow');return false;"> <i class="fa fa-angle-double-up "></i> </a> </span>
</div>
<?php wp_footer(); ?>
<script>


jQuery(document).ready(function($) {

	let scroll = $(window).scrollTop();
	if(scroll == 0){
			$("#top-link-block").hide();
	}else{
		$("#top-link-block").show();
	}
	$(window).scroll(function () {
		scroll = $(window).scrollTop();
		if(scroll == 0){
			$("#top-link-block").hide();
		}else{
			$("#top-link-block").show();
		}
	})
})



</script>
</body>
</html>