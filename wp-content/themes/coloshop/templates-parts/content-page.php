<?php if( is_page('contact') ): ?>
	<div class="contact-box">
        <div class="col-lg-5 col-md-5">
          	<h3>Contact Info</h3>
          	<div class="cnct-section"><figure><i class="fa fa-map-marker" aria-hidden="true"></i></figure><p>Trần Sang - Thôn Hàm Phu - Xã Chính Tâm - Kim Sơn - Ninh Bình</p></div>
          	<div class="cnct-section"><figure><i class="fa fa-phone" aria-hidden="true"></i></figure><p>0984407771 </p></div>
          	<div class="cnct-section"><figure><i class="fa fa-envelope-o" aria-hidden="true"></i></figure><p>sangtrank64@gmail.com
          	</p></div>
          	<div class="cnct-section"><figure><i class="fa fa-fax" aria-hidden="true"></i></figure><p>0984407771 </p></div>
        </div>
        <div class="col-lg-7 col-md-7">
          	<h3>Send Us Message</h3>
            <?php echo do_shortcode('[contact-form-7 id="158" title="contact"]'); ?>
        </div>
     </div>
	
<?php else: ?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<div class="entry-content">
		<?php
			the_content();

			wp_link_pages( array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'shop' ),
				'after'  => '</div>',
			) );
		?>
	</div><!-- .entry-content -->

	<?php if ( get_edit_post_link() ) : ?>
		<footer class="entry-footer">
			<?php
				edit_post_link(
					sprintf(
						wp_kses(
							/* translators: %s: Name of current post. Only visible to screen readers */
							__( 'Edit <span class="screen-reader-text">%s</span>', 'shop' ),
							array(
								'span' => array(
									'class' => array(),
								),
							)
						),
						get_the_title()
					),
					'<span class="edit-link">',
					'</span>'
				);
			?>
		</footer><!-- .entry-footer -->
	<?php endif; ?>
</article><!-- #post-<?php the_ID(); ?> -->
<?php endif; ?>
