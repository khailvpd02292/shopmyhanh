<?php get_header(); ?>
<?php
if ( ! is_active_sidebar( 'sidebar-bar' ) ) {
		$cols = 'col-md-12';
	}else{
		$cols = 'col-md-9';
	}
?>
<div id="shop-primary" >
	<div class="container">
		<div class="row">
		<div id="site-main" class="site-main <?php echo $cols; ?>">

			<?php
			if(have_posts()):

				while ( have_posts() ) : the_post();
					
					get_template_part( 'templates-parts/content', 'single' );

					if ( (comments_open() || get_comments_number()) ) :
						comments_template();
					endif;

				endwhile; 

			// elseif(is_singular('product')) :
			// 	echo 'sang';
			else:	
				echo wpautop( 'Sorry, no posts were found' );
			endif;
			?>

		</div><!-- #main -->
		<?php //if(!is_singular('product')):?>

		<?php get_sidebar(); ?>

		<?php //endif;?>	
		</div>
	</div>
</div><!-- #primary -->

<?php

get_footer();