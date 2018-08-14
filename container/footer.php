    <div id="single-post-container">
        
    </div>
    <div class="clearfix"></div>
</div>

<?php /** ?>
<footer id="colophon" role="contentinfo">
	<div class="attribution">
        <div class="copyright">
			<?php wp_nav_menu(['theme_location' => 'footer-menu', 'container' => false]); ?>

			<br><small>&copy;<?php echo date('Y'); ?> <a href="https://posterspy.com/">PosterSpy.com</a> &amp; <a href="https://getbutterfly.com/" target="_blank">getButterfly.com</a>. Some rights reserved. <a href="https://posterspy.com/about/terms-of-use/">Terms of use</a>.</small>
            <br><small>Powered by <a href="https://getbutterfly.com/wordpress-plugins/imagepress/" title="ImagePress" target="_blank">ImagePress</a>.</small>
        </div>
    </div>
</footer>
<?php /**/ ?>

<input type="hidden" id="lightbox-original-url">



<?php if (isset($_GET['beta'])) { ?>

<h4 style="text-align: center;">Recently viewed</h4>
<?php
// put this in a transient
if (is_single() && get_post_type() === 'poster') {
    update_post_meta($post->ID, '_last_viewed', current_time('mysql'));
}

$args = array(
    'post_type' => 'poster',
    'posts_per_page' => 10,
    'meta_key' => '_last_viewed',
    'orderby' => 'meta_value',
    'order' => 'DESC'
);
$recent_query = new WP_Query($args);

if ($recent_query->have_posts()) {
    echo '<div id="ip-boxes" style="display: inline-flex; align-items: center;">';
        while ($recent_query->have_posts()) {
            $recent_query->the_post();

            $i = get_the_ID();

            $user_info = get_userdata(get_the_author_id());
            $post_thumbnail_id = get_post_thumbnail_id($i);
            $image_attributes = wp_get_attachment_image_src($post_thumbnail_id, 'imagepress_pt_lrg');

            echo '<div class="ip_box" style="width: 10%;">
                <a href="' . get_permalink($i) . '" class="ip_box_img">
                    <img src="' . $image_attributes[0] . '" alt="' . get_the_title($i) . '">
                </a>
                <div class="ip_box_top">
                    <a href="' . get_permalink($i) . '" class="imagetitle">' . get_the_title($i) . '</a>
                    <span class="name">' . get_avatar(get_the_author_id(), 16) . ' <a href="' . get_author_posts_url(get_the_author_id()) . '">' . get_the_author() . '</a></span>
                </div>
            </div>';
        }
    echo '</div>';
} ?>

<?php } ?>

<footer id="colophon" role="contentinfo">
	<div class="attribution shiny-footer">
        <div class="footer-column">
            <div style="max-width: 200px;">
                <img src="https://posterspy.com/wp-content/uploads/2018/03/Logo-no-icon.png" alt="" height="40">
                <br>The showcase platform for poster artists worldwide.
            </div>
        </div>
        <div class="footer-column">
            <div style="margin: 0 auto; width: 320px">
            <h4>Subscribe to our newsletter</h4>

            <div id="mailerlite-form_2" data-temp-id="5b6eddf96a4b3">
                <div class="mailerlite-form">
                    <form action="" method="post" novalidate="novalidate">
                        <div class="mailerlite-form-inputs">
                            <div class="mailerlite-form-field">
                                <input id="mailerlite-2-field-email" type="email" name="form_fields[email]" placeholder="Your email address" required>
                                <input class="btn mailerlite-subscribe-submit" type="submit" value="Subscribe">
                            </div>
                            <div class="mailerlite-form-loader">Please wait...</div>
                            <input type="hidden" name="form_id" value="2">
                            <input type="hidden" name="action" value="mailerlite_subscribe_form">
                        </div>
                        <div class="mailerlite-form-response">Thank you for sign up!</div>
                    </form>
                </div>
            </div>

            <script>
            (function() {
                var jQuery = window.jQueryWP || window.jQuery;

                jQuery(document).ready(function () {
                    var form_container = jQuery("#mailerlite-form_2[data-temp-id=5b6eddf96a4b3] form");
                    form_container.submit(function (e) {
                        e.preventDefault();
                    }).validate({
                        submitHandler: function (form) {
                            jQuery(this.submitButton).prop('disabled', true);

                            form_container.find('.mailerlite-subscribe-button-container').fadeOut(function () {
                                form_container.find('.mailerlite-form-loader').fadeIn()
                            });

                            var data = jQuery(form).serialize();

                            jQuery.post('https://posterspy.com/wp-admin/admin-ajax.php', data, function (response) {
                                form_container.find('.mailerlite-form-inputs').fadeOut(function () {
                                    form_container.find('.mailerlite-form-response').fadeIn()
                                });
                            });
                        }
                    });
                });
            })();

            (function() {
                var jQuery = window.jQueryWP || window.jQuery;

                jQuery(document).ready(function () {
                    var form_container = jQuery("#mailerlite-form_2[data-temp-id=5b6eddf96a4b3] form");
                    form_container.submit(function (e) {
                        e.preventDefault();
                    }).validate({
                        submitHandler: function (form) {
                            jQuery(this.submitButton).prop('disabled', true);

                            form_container.find('.mailerlite-subscribe-button-container').fadeOut(function () {
                                form_container.find('.mailerlite-form-loader').fadeIn()
                            });

                            var data = jQuery(form).serialize();

                            jQuery.post('https://posterspy.com/wp-admin/admin-ajax.php', data, function (response) {
                                form_container.find('.mailerlite-form-inputs').fadeOut(function () {
                                    form_container.find('.mailerlite-form-response').fadeIn()
                                });
                            });
                        }
                    });
                });
            })();
            </script>
            </div>
        </div>
        <div class="footer-column last">
            <div style="display: inline-block; text-align: left;">
            <h4>Follow PosterSpy</h4>
            <div class="footer-column-social">
                <a href="https://www.facebook.com/PosterSpy" target="_blank" class="social-fb">Facebook</a>
                <a href="https://twitter.com/posterspy" target="_blank" class="social-tw">Twitter</a>
                <a href="https://instagram.com/posterspy/" target="_blank" class="social-ig">Instagram</a>
            </div>
            </div>
        </div>
    </div>

	<div class="shiny-footer secondary">
        <div class="footer-column muted">
            <i class="fas fa-heart"></i> PosterSpy is crafted with love in London, U.K.
        </div>
        <div class="footer-column" style="flex: 2; text-align: center;">
            <?php wp_nav_menu(['theme_location' => 'footer-menu', 'container' => false]); ?>
        </div>
        <div class="footer-column last">
            <a href="https://getbutterfly.com"><img src="https://posterspy.com/wp-content/themes/moon-ui-theme/img/gb-logo-light.png" alt="" height="30"></a>
        </div>
    </div>
</footer>






</div>
</div>
<?php wp_footer(); ?>
</div><!-- // .overlay -->
<div id="mobile-spy"></div>
</body>
</html>
