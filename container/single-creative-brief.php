<?php
get_header();

if (have_posts()) : while (have_posts()) : the_post();
    $featured = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'full');
    ?>
    <div class="brief-<?php echo get_field('page_template'); ?>">

    <div class="cb-featured-image" style="background: url(<?php echo get_field('brief_category_image'); ?>) no-repeat top center; background-size: cover;">
        <div class="cb-featured-image-inner">
            <div class="cb-featured-image-inner-left">
                <div class="cb-logo"><img src="<?php echo get_field('brief_logo'); ?>" alt=""></div>
                <div class="cb-details"><?php echo get_field('brief_order_details'); ?></div>
            </div>
            <div class="cb-clearfix"></div>
        </div>
    </div>
    <div class="cb-title"><span class="teal">Creative Brief:</span> <?php echo get_field('brief_title'); ?></div>

    <div class="cb-featured-image-inner-right">
        <div class="cb-video">
            <?php if (get_field('page_template') === 'light') { ?>
                <iframe width="920" height="518" src="https://www.youtube.com/embed/<?php echo get_field('brief_video'); ?>?rel=0" frameborder="0" allowfullscreen></iframe>
            <?php } else { ?>
                <iframe width="720" height="405" src="https://www.youtube.com/embed/<?php echo get_field('brief_video'); ?>?rel=0" frameborder="0" allowfullscreen></iframe>
            <?php } ?>
        </div>
        <div class="cb-social">
            <?php
            if (function_exists('sharing_display')) {
                echo sharing_display();
            }
            ?>
        </div>
    </div>

    <div class="cb-date">
        Submissions Open <?php echo date('F j<\s\up>S</\s\up> Y', strtotime(get_field('brief_start_date'))); ?> - <?php echo date('F j<\s\up>S</\s\up> Y', strtotime(get_field('brief_end_date'))); ?>
        <?php if (strtotime(get_field('brief_end_date')) >= strtotime('-1 day', time())) { ?>
            <div class="cb-date-timeleft">Time left: <span id="cb-timeleft"></span></div>

            <?php date_default_timezone_set("Europe/London"); ?>

            <script>
            var target_date = new Date("<?php echo date_i18n('F j, Y', strtotime(get_field('brief_end_date_countdown'))); ?>").getTime();
            var days, hours, minutes, seconds;
            var countdown = document.getElementById('cb-timeleft');

            setInterval(function () {
                var current_date = new Date().getTime();
                var seconds_left = (target_date - current_date) / 1000;

                days = parseInt(seconds_left / 86400);
                seconds_left = seconds_left % 86400;
                hours = parseInt(seconds_left / 3600);
                seconds_left = seconds_left % 3600;
                minutes = parseInt(seconds_left / 60);
                seconds = parseInt(seconds_left % 60);

                countdown.innerHTML = days + ' <em>days</em> ' + hours + ' <em>hours</em> ' + minutes + ' <em>minutes</em>';  
            }, 1000);
            </script>
        <?php } else { ?>
            <script>
            jQuery(document).ready(function() {
                jQuery('.submissions').trigger('click');
            });
            </script>
            <div class="cb-date-timeleft">Time left: <span id="cb-timeleft">Closed</span></div>
        <?php } ?>
    </div>

    <div class="cb-hashtag"><?php echo preg_replace('/#(\w+)/', ' <a target="blank" href="https://twitter.com/hashtag/$1">#$1</a>', get_field('brief_social_cta')); ?></div>

    <?php if (!empty(get_field('brief_entry'))) { ?>
        <div class="cb-entry">
            <div class="cb-entry-inner-left">
                <div class="cb-entry-inner-left-title">Winning Entry</div>
                <div class="cb-winning-entry"><img src="<?php echo get_field('brief_winning_entry'); ?>" alt=""></div>
            </div>
            <div class="cb-entry-inner-right">
                <?php echo get_field('brief_entry'); ?>
            </div>
            <div class="cb-clearfix"></div>
        </div>
    <?php } ?>

    <ul id="moon-tabs">
        <?php if (!empty(get_field('brief_details'))) { ?>
            <li class="active"><a href="#tab1">Brief</a></li>
        <?php } ?>
        <li><a href="#tab2" class="submissions">Submissions</a></li>
        <?php if (!empty(get_field('brief_hi_res_images'))) { ?>
            <li><a href="#tab3">Hi-Res Images</a></li>
        <?php } ?>
        <?php if (!empty(get_field('brief_updates'))) { ?>
            <li><a href="#tab4">Updates</a></li>
        <?php } ?>
        <?php if (!empty(get_field('brief_tcs'))) { ?>
            <li><a href="#tab5">T&amp;C's</a></li>
        <?php } ?>
    </ul>

    <section id="moon-tab-contents">
        <?php if (!empty(get_field('brief_details'))) { ?>
            <div id="tab1" class="moon-tab-contents moon-tab-brief active">
                <?php echo get_field('brief_details'); ?>
            </div>
        <?php } ?>
        <div id="tab2" class="moon-tab-contents">
            <div class="cb-entries">
                <?php the_content(); ?>
            </div>
        </div>
        <?php if (!empty(get_field('brief_hi_res_images'))) { ?>
            <div id="tab3" class="moon-tab-contents moon-tab-hires">
                <?php echo get_field('brief_hi_res_images'); ?>
            </div>
        <?php } ?>
        <?php if (!empty(get_field('brief_updates'))) { ?>
            <div id="tab4" class="moon-tab-contents moon-tab-updates">
                <?php echo get_field('brief_updates'); ?>
            </div>
        <?php } ?>
        <?php if (!empty(get_field('brief_tcs'))) { ?>
            <div id="tab5" class="moon-tab-contents moon-tab-tcs">
                <?php echo get_field('brief_tcs'); ?>
            </div>
        <?php } ?>
    </section>
    </div>

<?php endwhile; endif;

get_footer();
