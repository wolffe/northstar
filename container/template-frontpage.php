<center>
    <section class="carousel-ps">
        <?php
        query_posts('post_type=home_banner&posts_per_page=1');

        if (have_posts()) : while (have_posts()) : the_post();
        $thumb_id = get_post_thumbnail_id();
        $thumb_url = wp_get_attachment_image_src($thumb_id, 'full', true);
        ?>
        <a href="<?php echo esc_url(get_the_content()); ?>" target="_blank"><img src="<?php echo $thumb_url[0]; ?>" alt="<?php the_title(); ?>"></a>
        <?php endwhile; endif; wp_reset_query(); ?>
    </section>
</center>

<div class="contests">
    <section class="contest-copy-box">
        <h2>Creative Briefs</h2>
        <p>We work with the world's leading studios to bring artists exclusive creative opportunities.</p>
        <p><a class="btn btn-primary" href="https://posterspy.com/creative-briefs/">View all Creative Briefs</a></p>
    </section>

    <?php
    $args = array(
        'post_type' => 'creative-brief',
        'posts_per_page' => 3,
    );
    $the_query = new WP_Query($args);

    if ($the_query->have_posts()) {
        while ($the_query->have_posts()) {
            $the_query->the_post();

            // Check if date is not in the past
            $contest_end_date = get_field('brief_end_date', get_the_ID());

            $box_class = 'contest-inactive';
            if (strtotime($contest_end_date) >= strtotime('-1 day', time())) {
                $box_class = 'contest-active';
            }

            $contest_title = str_replace('Creative Brief: ', '', get_the_title());
            $contest_description = get_the_excerpt();

            $hero = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'hero-thumbnail');
            ?>
            <section class="contest-box <?php echo $box_class; ?>" style="background: url(<?php echo $hero[0]; ?>) center center no-repeat; background-size: cover;">
                <div class="contest-tag">new</div>
                <div class="contest-inlay">
                    <div class="title"><?php echo $contest_title; ?></div>
                    <?php if (strtotime($contest_end_date) >= strtotime('-1 day', time())) { ?>
                        <a class="btn btn-primary" href="<?php the_permalink(); ?>">View the Brief</a>
                    <?php } else { ?>
                        <a class="btn btn-secondary" href="<?php the_permalink(); ?>">View Entries</a>
                    <?php } ?>
                    <div class="description"><?php echo $contest_description; ?></div>

                    <div class="contest-status">
                        <?php if (strtotime($contest_end_date) >= strtotime('-1 day', time())) { ?>
                            Open <?php echo date('F j<\s\up>S</\s\up> Y', strtotime(get_field('brief_start_date'))); ?> - <?php echo date('F j<\s\up>S</\s\up> Y', strtotime(get_field('brief_end_date'))); ?>
                        <?php } else { ?>
                            Closed
                        <?php } ?>
                    </div>
                </div>
            </section>
            <?php
        }
    } else {
        // no contests found
    }
    wp_reset_postdata();
    ?>

    <div style="clear: both;"></div>
</div>

<center>
    <div id="adsense">
        <!-- Leaderboard PS -->
        <ins class="adsbygoogle" style="display:inline-block;width:728px;height:90px" data-ad-client="ca-pub-0173397358531894" data-ad-slot="4324144327"></ins>
        <script>(adsbygoogle = window.adsbygoogle || []).push({});</script>
    </div>
</center>

<center>
    <div id="Advertisement">
        <h6>Advertisement</h6>
    </div>
</center>

<h1 class="homepage-heading"><small><i class="fa fa-star yellow"></i></small> Staff Picks</h1>
<?php echo do_shortcode('[imagepress-show category="staffpicks" limit="12" count="12" type="random" size="imagepress_pt_std"]'); ?>
<h4><p class="right"><a href="https://posterspy.com/genre/staffpicks/">Browse all Staff Picks</a> <i class="fa fa-arrow-circle-right"></i></p></h4>

<h1 class="homepage-heading"><small><i class="fa fa-film"></i></small> Movie Posters</h1>
<?php echo do_shortcode('[imagepress-show category="movies" limit="12" count="12" type="random" size="imagepress_pt_std"]'); ?>
<h4><p class="right"><a href="https://posterspy.com/genre/movies/">Browse all Movie posters</a> <i class="fa fa-arrow-circle-right"></i></p></h4>

<h1 class="homepage-heading"><small><i class="fa fa-television"></i></small> TV Show Posters</h1>
<?php echo do_shortcode('[imagepress-show category="tv-shows" limit="12" count="12" type="random" size="imagepress_pt_std"]'); ?>
<h4><p class="right"><a href="https://posterspy.com/genre/tv-shows/">Browse all TV Show posters</a> <i class="fa fa-arrow-circle-right"></i></p></h4>

<h1 class="homepage-heading"><small><i class="fa fa-th-large"></i></small> Collections</h1>
<?php echo do_shortcode('[imagepress-collections count="5"]'); ?>
<h4><p class="right"><a href="https://posterspy.com/all-collections/">Browse all Collections</a> <i class="fa fa-arrow-circle-right"></i></p></h4>

<center>
    <div id="adsense">
        <!-- Leaderboard PS -->
        <ins class="adsbygoogle" style="display:inline-block;width:728px;height:90px" data-ad-client="ca-pub-0173397358531894" data-ad-slot="4324144327"></ins>
        <script>(adsbygoogle = window.adsbygoogle || []).push({});</script>
    </div>
</center>

<center>
    <div id="Advertisement">
        <h6>Advertisement</h6>
    </div>
</center>
