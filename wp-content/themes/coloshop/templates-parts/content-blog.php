<?php 
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
$wp_query = new WP_Query( array(
			'post_type' => 'post',
			'post_status'         => 'publish',
			'cat'		  		  => '',
			'posts_per_page'   	  => 6,
			'paged' => $paged
		) );
		//var_dump($r);
echo $args['before_widget'];

if ($wp_query->have_posts()) :
?>
	<div class="row blogs_gird_container">
		<?php while ( $wp_query->have_posts() ) : $wp_query->the_post();?>
		<?php 
			$feat_image = wp_get_attachment_url( get_post_thumbnail_id() );
		?>
		<div class="col-sm-4 col-md-4">
			<div class="blog_item">
				<div class="blog_background" style="background-image:url(<?php echo $feat_image; ?>)"></div>
				<div class="blog_content align-items-nav text-center">
					
					<h4 class="blog_title"><?php the_title() ; ?></h4> 
					<span class="blog_meta"><?php the_author(); ?> | <?php the_time('d/m/Y'); ?></span>
					<a class="blog_more" href="<?php echo esc_url( get_permalink() ); ?>">Read more</a>
				</div>
			</div>
		</div>
		<?php endwhile; ?>
	</div>
	
	<?php wp_reset_postdata(); ?>
	<?php numbered_pagination();
endif;

echo $args['after_widget'];

?>