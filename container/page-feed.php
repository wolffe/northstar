<?php  
/* 
Template Name: Feed
*/

get_header(); ?>

<section id="content" role="main">
    <?php if(have_posts()) : while(have_posts()) : the_post(); ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <?php if(function_exists('yoast_breadcrumb')) { yoast_breadcrumb('<p id="breadcrumbs">', '</p>'); } ?>
            <h1 class="entry-title"><?php the_title(); ?></h1>
            <section class="entry-content">
                <?php the_content(); ?>

                <?php imagepress_feed(); ?>
            </section>
        </article>
    <?php endwhile; endif; ?>
</section>

<?php get_sidebar('feed'); ?>
<?php get_footer();
