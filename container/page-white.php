<?php  
/* 
Template Name: White Page
*/

get_header(); ?>

<section id="content-wide" role="main">
    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <?php if(function_exists('yoast_breadcrumb')) { yoast_breadcrumb('<p id="breadcrumbs">', '</p>'); } ?>
            <h1 class="entry-title"><?php the_title(); ?></h1>

            <div class="contests">
                <?php
                $args = array(
                    'post_type' => 'creative-brief',
                    'posts_per_page' => 40,
                );
                $the_query = new WP_Query($args);

                if ($the_query->have_posts()) {
                    while ($the_query->have_posts()) {
                        $the_query->the_post();

                        // Check if date is not in the past
                        $contest_end_date = get_field('brief_end_date', get_the_ID());

                        if (strtotime($contest_end_date) >= strtotime('-1 day', time())) {
                            $box_class = 'contest-active';
                        } else {
                            $box_class = 'contest-inactive';
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



                <?php
                $args = array(
                    'post_type' => 'post',
                    'category_name' => 'contests',
                    'posts_per_page' => 40,
                );
                $the_query = new WP_Query($args);

                if ($the_query->have_posts()) {
                    while ($the_query->have_posts()) {
                        $the_query->the_post();

                        // Check if date is not in the past
                        $contest_start_date = get_post_meta(get_the_ID(), 'contest-start-date', true);
                        $contest_end_date = get_post_meta(get_the_ID(), 'contest-end-date', true);
                        if(strtotime($contest_end_date) >= strtotime('-1 day', time())) {
                            $box_class = 'contest-active';
                        } else {
                            $box_class = 'contest-inactive';
                        }

                        $contest_title = get_post_meta(get_the_ID(), 'contest-title', true);
                        $contest_description = get_post_meta(get_the_ID(), 'contest-description', true);

                        $hero = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'hero-thumbnail');
                        ?>
                        <section class="contest-box <?php echo $box_class; ?>" style="background: url(<?php echo $hero[0]; ?>) center center no-repeat; background-size: cover;">
                            <div class="contest-tag">new</div>
                            <div class="contest-inlay">
                                <div class="title"><?php echo $contest_title; ?></div>
                                <?php if(strtotime($contest_end_date) >= strtotime('-1 day', time())) { ?>
                                    <a class="btn btn-primary" href="<?php the_permalink(); ?>">View the Brief</a>
                                <?php } else { ?>
                                    <a class="btn btn-secondary" href="<?php the_permalink(); ?>">View Entries</a>
                                <?php } ?>
                                <div class="description"><?php echo $contest_description; ?></div>

                                <div class="contest-status">
                                    <?php if(strtotime($contest_end_date) >= strtotime('-1 day', time())) { ?>
                                        Open <?php echo date('F dS Y', strtotime($contest_start_date)); ?> - <?php echo date('F dS Y', strtotime($contest_end_date)); ?>
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

                <div class="contest-logos center">
                    <h3>Brands that have worked with us:</h3>
                    <img src="https://posterspy.com/wp-content/themes/moon-ui-theme/img/logos/ps-universal-logo.png" alt="">
		          <img src="https://posterspy.com/wp-content/uploads/2016/08/amazon-video-logogrey2.png" alt="">
                    <img src="https://posterspy.com/wp-content/themes/moon-ui-theme/img/logos/ps-disney-logo.png" alt="">
                    <img src="https://posterspy.com/wp-content/themes/moon-ui-theme/img/logos/ps-warner-bros-logo.png" alt="">
                    <img src="https://posterspy.com/wp-content/themes/moon-ui-theme/img/logos/ps-studiocanal-logo.png" alt="">
                    <img src="https://posterspy.com/wp-content/themes/moon-ui-theme/img/logos/ps-curzon-logo.png" alt="">
                    <img src="https://posterspy.com/wp-content/themes/moon-ui-theme/img/logos/ps-eone-logo.png" alt="">
                    <img src="https://posterspy.com/wp-content/themes/moon-ui-theme/img/logos/ps-studio-ghibli-logo.png" alt="">
                    <img src="https://posterspy.com/wp-content/themes/moon-ui-theme/img/logos/ps-altitude-logo.png" alt="">
                    <img src="https://posterspy.com/wp-content/themes/moon-ui-theme/img/logos/ps-ubisoft-logo.png" alt="">
                    <img src="https://posterspy.com/wp-content/themes/moon-ui-theme/img/logos/ps-jagex-logo.png" alt="">
                    <img src="https://posterspy.com/wp-content/themes/moon-ui-theme/img/logos/ps-wacom-logo.png" alt="">
                    <img src="https://posterspy.com/wp-content/themes/moon-ui-theme/img/logos/ps-nvidia-logo.png" alt="">
                </div>
            </div>
 
	      <div class="Get in touch center">
                    <h3>Want to work with us?
                    Send us an <a style="color:black;" href="mailto:projects@posterspy.com?Subject=Creative%20Brief%20Enquiry" target="blank">email</a> for more information.</h3>
                </div>
            </div>
            <section class="entry-content">
                <?php the_content(); ?>
            </section>

        </article>
    <?php endwhile; endif; ?>
</section>

<?php get_footer();
