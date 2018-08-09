<?php get_header(); ?>

<section id="content-wide" role="main">
    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <h1 class="entry-title"><?php the_title(); ?></h1>

            <section class="entry-meta">
                <span class="author vcard"><?php the_author_posts_link(); ?></span>
                <span class="meta-sep"> | </span>
                <span class="entry-date"><?php the_time(get_option('date_format')); ?></span>
            </section>

            <section class="entry-content">
                <?php the_content(); ?>
            </section>

            <footer class="entry-footer">
                <span class="cat-links">Categories: <?php the_category(', '); ?></span>
                <?php if (comments_open()) {
                    echo '<span class="meta-sep">|</span> <span class="comments-link"><a href="' . get_comments_link() . '">Comments</a></span>';
                } ?>
            </footer>
        </article>

		<div id="disqus_thread"></div>
		<script>
		var disqus_shortname = 'posterspy';

		(function() {
			var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
			dsq.src = 'https://' + disqus_shortname + '.disqus.com/embed.js';
			(document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
		})();
		</script>
		<noscript>Please enable JavaScript to view the <a href="https://disqus.com/?ref_noscript" rel="nofollow">comments powered by Disqus.</a></noscript>
    <?php endwhile; endif; ?>

    <nav id="nav-below" class="navigation" role="navigation">
        <ul>
            <li><?php previous_post_link('%link', '<i class="fas fa-fw fa-chevron-left"></i> %title'); ?></li>
            <li class="right"><?php next_post_link('%link', '%title <i class="fas fa-fw fa-chevron-right"></i>'); ?></li>
        </ul>
    </nav>
</section>

<?php get_footer();
