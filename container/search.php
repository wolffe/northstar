<?php get_header();

$post_type = $_GET['post_type'];

if ((string) $post_type !== 'poster') { ?>
    <section id="content-wide" role="main">
         <?php if (have_posts()) : ?>
            <h1 class="entry-title"><?php printf('Search Results for: %s', get_search_query()); ?></h1>
            <?php while (have_posts()) : the_post(); ?>
                <div class="noir-box">
                    <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_post_thumbnail('imagepress_sq_std'); ?></a>
                    <p>
                        <b><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></b>
                        <br><small><?php the_author_posts_link(); ?> | <?php the_time(get_option('date_format')); ?></small>
                    </p>
                </div>
            <?php endwhile; ?>
            <?php get_template_part('nav', 'below'); ?>
        <?php else : ?>
            <h2 class="entry-title">Nothing Found</h2>
            <section class="entry-content">
                <p>Sorry, nothing matched your search. Please try again.</p>
                <?php get_search_form(); ?>
            </section>
        <?php endif; ?>
    </section>
<?php } else { ?>
    <style>
    #content-wide {
        padding: 0 0 8px 0;
    }
    </style>

    <?php $s = get_search_query(); ?>
    <?php $num = $wp_query->found_posts; ?>

    <div class="ip-search-page-header">
        <h3>Poster Search Results</h3>
        <p>
            <?php printf('Results for "%s"', $s); ?>
            <br><small><?php echo 'About ' . number_format($num) . ' results'; ?></small>
        </p>
    </div>

    <section id="content-wide" role="main">
        <div id="ip-boxes">
            <?php if(have_posts()) : ?>
                <?php while(have_posts()) : the_post(); ?>
                    <?php
                    $i = get_the_ID();

                    $user_info = get_userdata(get_the_author_id());
                    $post_thumbnail_id = get_post_thumbnail_id($i);

                    $image_attributes = wp_get_attachment_image_src($post_thumbnail_id, 'imagepress_pt_lrg');

                    echo '<div class="ip_box"><a href="' . get_permalink($i) . '" class="ip_box_img"><img src="' . $image_attributes[0] . '" alt="' . get_the_title($i) . '"></a><div class="ip_box_top"><a href="' . get_permalink($i) . '" class="imagetitle">' . get_the_title($i) . '</a><span class="name">' . get_avatar(get_the_author_id(), 16) . ' <a href="' . get_author_posts_url(get_the_author_id()) . '">' . get_the_author() . '</a></span></div></div>';
                    ?>
                <?php endwhile; ?>
            <?php endif; ?>
            <?php get_template_part('nav', 'below'); ?>
        </div>
    </section>
<?php }

get_footer();
