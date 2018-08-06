<?php
if (!ip_is_ajax()) {
    get_header();
}
?>

<section id="content" role="main" class="ip-main">
    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
        <?php
        $i = get_the_ID();
        $full = wp_get_attachment_image_src(get_post_thumbnail_id($i), 'full');
        $full = $full[0];
        ?>
        <article id="post-<?php echo $i; ?>" <?php post_class(); ?>>
            <?php ip_editor(); ?>

            <nav class="navigation" role="navigation">
                <ul>
                    <li class=""><?php next_post_link('%link', '<i class="fa fa-fw fa-chevron-left"></i>', true, '', 'imagepress_image_category'); ?></li>
                    <li class="right"><?php previous_post_link('%link', '<i class="fa fa-fw fa-chevron-right"></i>', true, '', 'imagepress_image_category'); ?></li>
                </ul>
            </nav>

            <div class="poster-container">
                <?php the_post_thumbnail('full'); ?>

                <br>
                <div class="poster-container-overlay">
                    <a href="<?php echo $full; ?>" target="_blank"><i class="fa fa-expand" aria-hidden="true"></i></a>
                </div>

                <?php ip_setPostViews($i); ?>
                <?php imagepress_get_images($i); ?>

                <?php
                $imagepress_video = get_post_meta($i, 'imagepress_video', true);
                if ((string) $imagepress_video !== '') {
                    $embed_code = wp_oembed_get($imagepress_video);
                    echo '<br>';
                    echo $embed_code;
                    echo '<br>';
                }
                ?>
            </div>
        </article>
    <?php endwhile; endif; ?>
</section>

<?php
get_sidebar('hub');

if (!ip_is_ajax()) {
    get_footer();
}
