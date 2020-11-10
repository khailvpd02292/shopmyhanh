<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php if ( has_post_thumbnail() ) : ?>
	<figure class="entry-thumbnail">
		<?php the_post_thumbnail(); ?>
	</figure>
	<?php endif; ?>
	<?php if(!is_singular('product')): ?>
	<header class="entry-header">
		<?php if ( !$hide_meta ) : ?>
		<div class="meta-category">
			<?php //leto_show_cats(); ?>
		</div>
		<?php endif; ?>

		<?php

			the_title( '<h4 class="entry-title">', '</h4>' );

		if ( ( 'post' === get_post_type() )) : ?>
		<div class="entry-meta">
			<?php shop_posted_on(); ?>
		</div><!-- .entry-meta -->
		<?php
		endif; ?>
	</header><!-- .entry-header -->
	<?php endif; ?>
	<div class="entry-content">
		<?php
			the_content();
		?>
	</div><!-- .entry-content -->

	<footer class="entry-footer entry-footer-links">
		<?php shop_entry_footer(); ?>
	</footer><!-- .entry-footer -->
</article><!-- #post-<?php the_ID(); ?> -->
