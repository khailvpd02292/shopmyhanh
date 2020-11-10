<?php get_header(); ?>
<?php 
	if(is_page('contact')){
		$col = ' ';
	}else{
		$col = 'col-md-9';
	} 
?>
<div id="shop-primary">
	<div class="container">
		<div class="row">
		<div id="site-main" class="site-main <?php echo $col; ?>">

			<?php
			if(!is_page('blog')):
				if(have_posts()):

					while ( have_posts() ) : the_post();
						
						get_template_part( 'templates-parts/content', 'page' );

						if ( comments_open() || get_comments_number() ) :
							comments_template();
						endif;
					endwhile; 

				else :
					echo wpautop( 'Sorry, no posts were found' );
				endif;
			else:
				get_template_part( 'templates-parts/content', 'blog' );
			endif;
			?>

		</div><!-- #main -->
		<?php ( !is_page('contact') ) ? get_sidebar()  : '' ; ?>	
		</div>
	</div>
</div><!-- #primary -->

<?php
get_footer();