<?php get_header(); ?>

<section id="content-wide" role="main">
    <article <?php post_class(); ?>>
        <section class="entry-content">
            <?php
            // check for external portfolio
            // if page call is made from subdomain (e.g. username.domain.ext)
            // display external page
            $url = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
            $parseUrl = parse_url($url);
            $ext_detect = trim($parseUrl['path']);
            if($ext_detect == '/') {
                echo '<div id="hub-loading"></div>';
                echo do_shortcode('[cinnamon-profile-blank]');
            }
            else {
                echo do_shortcode('[cinnamon-profile]');
            }
            ?>
        </section>
    </article>
</section>

<?php get_footer(); ?>
