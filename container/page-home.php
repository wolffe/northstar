<?php  
/* 
Template Name: Homepage
*/
get_header(); ?>

<!--// HOME /-->
<div style="margin: 0 0 32px 0;">
    <section id="content-wide" role="main">
        <script>
        (adsbygoogle = window.adsbygoogle || []).push({
            google_ad_client: "ca-pub-0173397358531894",
            enable_page_level_ads: true
        });
        </script>

        <?php
        //if (isset($_GET['beta'])) {
        //}

        if (is_user_logged_in()) { // comment this line to remove feed
            get_template_part('template', 'feed'); // comment this line to remove feed
        } else { // comment this line to remove feed
            get_template_part('template', 'frontpage'); // keep this
        } // comment this line to remove feed
        ?>
    </section>

<?php get_footer();
