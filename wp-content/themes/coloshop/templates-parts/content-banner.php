<?php
$delete = null;
if ($delete) {
    ?>
<div class="shop_banner">
    <div class="container">
        <div class="row">
            <?php

    $taxonomy = 'product_cat';
    $orderby = 'ASC';
    $show_count = 0;
    $pad_counts = 0;
    $hierarchical = 1;
    $title = '';
    $empty = 0;

    $args = array(
        'taxonomy' => $taxonomy,
        'orderby' => $orderby,
        'show_count' => $show_count,
        'pad_counts' => $pad_counts,
        'hierarchical' => $hierarchical,
        'title_li' => $title,
        'hide_empty' => $empty,
    );

    $all_categories = get_terms($args);
    $i = 0;
    if (count($all_categories) > 0) {
        foreach ($all_categories as $cat) {
            $i++;
            if ($i <= 3) {

                $metaterms = get_term_meta($cat->term_id);
                $thumbnail_id = get_woocommerce_term_meta($cat->term_id, 'thumbnail_id', true);
                $image = wp_get_attachment_url($thumbnail_id);
                ?>


                <div class="col-md-4">
                    <div class="banner_item align-items-center" style="background-image:url(<?php echo $image; ?>)">
                        <div class="banner_category">
                            <a href="<?php echo get_term_link($cat->slug, 'product_cat'); ?>"><?php echo $cat->name; ?></a>
                        </div>
                    </div>
                </div>

                <?php }}}?>

        </div>
    </div>
</div>

<?php
}
?>