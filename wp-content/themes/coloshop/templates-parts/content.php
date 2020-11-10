<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php if ( has_post_thumbnail() ) : ?>
	<figure class="entry-thumbnail">
		<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
			<?php the_post_thumbnail(); ?>			
		</a>
	</figure>
	<?php endif; ?>

	<header class="entry-header">
		<?php the_title( '<h4 class="entry-title">', '</h4>' );?>
	</header><!-- .entry-header -->

	<footer class="entry-meta-footer">
		<?php shop_posted_on(); ?>
	</footer><!-- .entry-footer -->
</article><!-- #post-<?php the_ID(); ?> -->
