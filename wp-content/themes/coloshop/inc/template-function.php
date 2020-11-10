<?php
function shop_comment_template($comment, $args, $depth) {

	?>
	<li id="comment-<?php comment_ID(); ?>" <?php comment_class( $comment->has_children ? 'parent' : '' ); ?>>
		<article id="div-comment-<?php comment_ID(); ?>" class="comment-body comment-entry clearfix">
			<figure class="comment-avatar">
				<?php echo get_avatar( $comment, $args['avatar_size'] ); ?>
			</figure>

			<div class="comment-text">
				<header class="comment-head">
					<h5 class="comment-author-name">
						<span><?php printf( '<b class="fn">%s</b>', get_comment_author_link() ) ; ?></span>
					</h5>
					 &#8211; 
					<time class="comment-time" datetime="<?php comment_time( 'c' ); ?>">
						<?php printf( _x( '%1$s at %2$s', '1: date, 2: time', 'shop' ), get_comment_date(), get_comment_time() ); ?>
					</time>
				</header>

				<div class="comment-body">
					<?php if ( '0' == $comment->comment_approved ) : ?>
					<p class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'shop' ); ?></p>
					<?php endif; ?>

					<div class="comment-content">
						<?php comment_text(); ?>
					</div>
					<div class="comment-links">
					<?php edit_comment_link( __( 'Edit', 'shop' ), '<span class="edit-link">', '</span>' ); ?>
					&nbsp;
					<?php comment_reply_link( array_merge( $args, array( 'add_below' => 'edit-link', 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>													
					</div>
				</div>
			</div>
		</article>
	</li>
	<?php
}

function shop_comment_shop_template(){
		
}