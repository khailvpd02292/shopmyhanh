<?php 

get_header();
	if ( ! is_active_sidebar( 'sidebar-bar' ) ) {
		$cols = 'col-md-12';
	}else{
		$cols = 'col-md-9';
	}
?>
<div id="shop-primary" class="shop-search-item">
	<div class="container">
		<div class="row">
			<main id="main" class="site-main <?php echo $cols; ?>">
				<?php if(have_posts()):?>
					<header class="page-header">
						<h1 class="page-title"><?php printf( esc_html__( 'Search Results for: %s', 'shop' ), '<span>' . get_search_query() . '</span>' );
						?></h1>
					</header><!-- .page-header -->

					<div class="page-blog-layout">
						<div class="row">

							<?php while(have_posts() ) : the_post(); ?>
								
								<div class="col-md-3">
									<?php get_template_part( 'templates-parts/content', get_post_format() ); ?>	
								</div>

							<?php endwhile;?>

						</div>
					</div>

					<?php the_post_navigation(); ?>
					
					<?php else : 

						get_template_part( 'template-parts/content', 'none' );

					?>
				<?php endif ?>
			</main>
			<?php get_sidebar(); ?>
		</div>
	</div>
</div>
<?php get_footer();?>
