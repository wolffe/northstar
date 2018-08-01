<?php get_header(); ?>
<section id="content" role="main">
<header class="header">
<h1 class="entry-title"><?php 
if ( is_day() ) { printf( 'Daily Archives: %s', get_the_time( get_option( 'date_format' ) ) ); }
elseif ( is_month() ) { printf( 'Monthly Archives: %s', get_the_time( 'F Y' ) ); }
elseif ( is_year() ) { printf( 'Yearly Archives: %s', get_the_time( 'Y' ) ); }
else { echo 'Archives'; }
?></h1>
</header>
<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
<?php get_template_part( 'entry' ); ?>
<?php endwhile; endif; ?>
<?php get_template_part( 'nav', 'below' ); ?>
</section>
<?php get_sidebar(); ?>
<?php get_footer();
