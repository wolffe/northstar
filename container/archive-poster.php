<?php
/**
 * ImagePress archive template
 *
 * Archive template.
 *
 * @package ImagePress
 * @subpackage Template
 * @since 6.0.0-posterspy
 */
?>

<?php get_header(); ?>

<section id="content-wide" class="taxonomy" role="main">
    <style>
    #content-wide {
        padding: 0 0 8px 0;
    }
    </style>

    <?php
    echo getDiscoverFilters();

    $paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;

    $args1 = array(
        'post_type' => 'poster',
        'paged' => $paged,
    );
    $wp_query = new WP_Query($args1);
    ?>
    <div id="ip-boxes">
        <?php if($wp_query->have_posts()) : ?>
            <?php while($wp_query->have_posts()) : $wp_query->the_post(); ?>
                <?php
                $i = get_the_ID();

                $user_info = get_userdata(get_the_author_id());
                $post_thumbnail_id = get_post_thumbnail_id($i);

                $image_attributes = wp_get_attachment_image_src($post_thumbnail_id, 'imagepress_pt_lrg');

                echo '<div class="ip_box"><a href="' . get_permalink($i) . '" class="ip_box_img"><img src="' . $image_attributes[0] . '" alt="' . get_the_title($i) . '"></a><div class="ip_box_top"><a href="' . get_permalink($i) . '" class="imagetitle">' . get_the_title($i) . '</a><span class="name">' . get_avatar(get_the_author_id(), 16) . ' <a href="' . get_author_posts_url(get_the_author_id()) . '">' . get_the_author() . '</a></span></div></div>';
                ?>
            <?php endwhile; ?>
            <?php // Pagination ?>
        <?php endif; ?>
    </div>
    <?php get_template_part('nav', 'below'); ?>
</section>

<?php get_footer(); ?>