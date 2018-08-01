<?php get_header(); ?>

<section id="content-wide" class="taxonomy" role="main">
    <style>
    #content-wide {
        padding: 0 0 8px 0;
    }
    </style>

    <?php
    echo getDiscoverFilters();

    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

    $args1 = [
        'post_type' => 'poster',
        'paged' => $paged,
    ];
    $wp_query = new WP_Query($args1);
    ?>
    <div id="ip-boxes">
        <?php if ($wp_query->have_posts()) : ?>
            <?php while ($wp_query->have_posts()) : $wp_query->the_post(); ?>
                <?php
                $i = get_the_ID();

                $user_info = get_userdata(get_the_author_id());
                $post_thumbnail_id = get_post_thumbnail_id($i);

                $image_attributes = wp_get_attachment_image_src($post_thumbnail_id, 'imagepress_pt_lrg');

                echo '<div class="ip_box">
                    <a href="' . get_permalink($i) . '" class="ip_box_img">
                        <img src="' . $image_attributes[0] . '" alt="' . get_the_title($i) . '">
                    </a>
                    <div class="ip_box_top">
                        <a href="' . get_permalink($i) . '" class="imagetitle">' . get_the_title($i) . '</a>
                        <span class="name">' . get_avatar(get_the_author_id(), 16) . ' <a href="' . get_author_posts_url(get_the_author_id()) . '">' . get_the_author() . '</a></span>
                    </div>
                </div>';
                ?>
            <?php endwhile; ?>
        <?php endif; ?>
    </div>

    <?php if ($wp_query->max_num_pages > 1) { ?>
        <nav id="nav-below" class="navigation" role="navigation">
            <div class="nav-next"><?php previous_posts_link('<i class="fa fa-chevron-left"></i> Back'); ?></div>
            <div class="nav-previous"><?php next_posts_link('Next <i class="fa fa-chevron-right"></i>'); ?></div>
        </nav>
        <div class="nav-below-stats">
            <?php
            if (get_query_var('paged') == 0) {
                $p = 1;
            } else {
                $p = get_query_var('paged');
            }
            ?>
            <p>Currently displaying page <b><?php echo $p; ?></b> of <b><?php echo $wp_query->max_num_pages; ?></b></p>
            <?php if ($p > 1 && !is_search()) { ?>
                <p><a href="<?php echo preg_replace("/(\/page\/)\w+/", '', strtok($_SERVER['REQUEST_URI'], '?')); ?>">Return to page 1</a></p>
            <?php } ?>
        </div>
    <?php } ?>
</section>

<?php get_footer();
