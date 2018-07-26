<?php get_header(); ?>

<section id="content-wide" role="main">
    <h1 class="entry-title"><?php single_cat_title(); ?></h1>
    <?php if('' != category_description()) echo apply_filters('archive_meta', '<div class="archive-meta"><small>' . category_description() . '</small></div>'); ?>
    <?php if(have_posts()) : while(have_posts()) : the_post(); ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('thumbnail'); ?></a>
            <h3 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
            <small class="entry-meta">
                <span class="entry-date"><?php the_time(get_option('date_format')); ?></span>
            </small>
            <br>
            <?php echo substr(get_the_excerpt(), 0, 100); ?> [&hellip;]
        </article>
    <?php endwhile; endif; ?>
    <?php get_template_part('nav', 'below'); ?>
</section>

<?php get_footer();
