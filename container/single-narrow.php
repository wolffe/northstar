<?php
/*
Single Post Template: Narrow Post
*/

get_header(); ?>
<div style="text-align: center;">
    <div id="adsense">
        <script>
        google_ad_client = "ca-pub-0173397358531894";
        google_ad_slot = "4324144327";
        google_ad_width = 728;
        google_ad_height = 90;
        </script>
        <!-- Leaderboard PS -->
        <script src="https://pagead2.googlesyndication.com/pagead/show_ads.js"></script>
    </div>
</div>

<section id="content-wide" role="main" style="width: 900px; margin: 0 auto;" class="box-ps">
    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <h1 class="entry-title"><?php the_title(); ?></h1>

            <section class="entry-meta">
                <span class="author vcard"><i class="fas fa-user"></i> <?php the_author_posts_link(); ?></span>
                <span class="meta-sep"> | </span>
                <span class="entry-date"><i class="far fa-clock"></i> <?php the_time(get_option('date_format')); ?></span>
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

			<?php the_about_the_author_box(); ?>
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
