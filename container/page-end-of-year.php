<?php  
/* 
Template Name: End of Year Portfolio Reviews
*/

get_header(); ?>

<section id="content-wide" class="content-1170" role="main">
    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <div class="stripe-top">
                <h1 class="entry-title"><?php the_title(); ?></h1>

                <?php
                // Check if the repeater field has rows of data
                if (have_rows('sponsors')) { ?>
                    <section class="entry-eoy-sponsors">
                        <h3>Sponsored by</h3>
                        <?php
                        // Loop through the rows of data
                        while (have_rows('sponsors')) {
                            the_row();
                            // Display a sub field value ?>
                            <a href="<?php the_sub_field('sponsor_uri'); ?>" target="_blank"><img src="<?php the_sub_field('sponsor_logo'); ?>" alt=""></a>
                            <?php
                        } ?>
                    </section>
                <?php } else {
                    // No rows found
                } ?>
            </div>
            <?php /** ?><div class="stripe-top-overlay"></div><?php /**/ ?>

            <section class="entry-content stripe-content">
                <?php the_content(); ?>
            </section>

            <div class="stripe-judges">
                <?php
                // Check if the repeater field has rows of data
                if (have_rows('judges')) { ?>
                    <section class="entry-eoy-judges">
                        <h2>The Judges</h2>
                        <?php
                        // Loop through the rows of data
                        $counter = 0;
                        while (have_rows('judges')) {
                            the_row();

                            $judge_uri = (empty(get_sub_field('judge_uri')) ? '#' : get_sub_field('judge_uri'));
                            // Display a sub field value ?>
                            <a href="<?php echo $judge_uri; ?>" target="_blank">
                                <img src="<?php the_sub_field('judge_logo'); ?>" alt="<?php the_sub_field('judge_name'); ?>">
                                <span><?php the_sub_field('judge_name'); ?></span>
                            </a>
                            <?php
                            $counter++;
                            if ((int) $counter === 3) {
                                echo '<br>';
                            }
                        } ?>
                    </section>
                <?php } else {
                    // No rows found
                } ?>

                <?php if (!empty(get_field('judges_post_content'))) { ?>
                    <section>
                        <?php the_field('judges_post_content'); ?>
                    </section>
                <?php } ?>
            </div>

            <?php
            // Check if the repeater field has rows of data
            if (have_rows('awards')) { ?>
                <section class="entry-eoy-awards">
                    <h2>The Awards</h2>

                    <?php if (!empty(get_field('awards_pre_content'))) { ?>
                        <section>
                            <?php the_field('awards_pre_content'); ?>
                        </section>
                    <?php } ?>

                    <?php
                    // Loop through the rows of data
                    while (have_rows('awards')) {
                        the_row();
                        // Display a sub field value ?>
                        <div class="eoy-award">
                            <img src="<?php the_sub_field('award_image'); ?>" alt="">
                            <span><?php the_sub_field('award_description'); ?></span>
                        </div>
                        <?php
                    } ?>
                </section>
            <?php } else {
                // No rows found
            } ?>

            <section class="entry-eoy-submission stripe-submission">
                <h2>Submit Your Portfolio</h2>

                <?php
                $profile_uri = $profile_email = '';

                if (is_user_logged_in()) {
                    $logged_in_user = wp_get_current_user();
                    $uid = $logged_in_user->ID;
                    $profile_email = $logged_in_user->user_email;
                    $profile_uri = get_author_posts_url($uid);
                }

                if (isset($_GET['contact-form-id']) && isset($_GET['contact-form-sent'])) {
                    echo '<p><i class="fas fa-check" aria-hidden="true"></i> Thank you for your submission!</p>';
                }

                echo do_shortcode('[contact-form to="portfolioreview@posterspy.com" subject="New End of Year Portfolio Review Submission" submit_button_text="Submit"][contact-field label="Full Name" type="name" required="1"][contact-field label="Email Address" type="email" required="1"][contact-field label="PosterSpy Profile URL" type="text" default="' . $profile_uri . '" required="1"][/contact-form]');
                ?>

                <script>
                jQuery(document).ready(function($) {
                    $('#g58219-fullname').attr('placeholder', 'Full Name');
                    $('#g58219-emailaddress').attr('placeholder', 'Email Address');
                    $('#g58219-posterspyprofileurl').attr('placeholder', 'PosterSpy Profile URL');
                });
                </script>
            </section>
        </article>
    <?php endwhile; endif; ?>
</section>

<?php get_footer();
