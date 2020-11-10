<?php get_header(); ?>
<?php 


if ( ! is_active_sidebar( 'sidebar-bar' ) ) {
		$cols = 'col-md-12';
	}else{
		$cols = 'col-md-9';
	}
?>
<div id="shop-primary">
	<div class="container">
		<div class="row">
		<div id="site-main" class="site-main <?php echo $cols; ?>">
			<?php if( have_post() ) : ?>
				<div class="blog-layout-gird clearfix">
					<?php while( have_post() )  : the_post(); 
						

						get_template_part('templates-parts/content', get_post_format());


					endwhile; ?>
				</div>
				<?php the_posts_navigation(); ?>
			<?php else: 
				get_template_part('templates-parts/content', 'none');
			endif; ?>
		</div>
		<?php if ( $sidebar ) { get_sidebar();	} ?>
	</div>
</div><!-- #primary -->

<?php

get_footer();