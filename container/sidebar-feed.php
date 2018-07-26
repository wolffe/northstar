<aside id="sidebar" class="sidebar-feed" role="complementary">
    <div class="sidebar__inner">
        <div class="widget-container widget_text">
            <h2>Latest Creative Briefs</h2>
            <?php get_creative_briefs(); ?>
        </div>

        <div class="widget-container widget_text">
            <h2>Artists to Follow</h2>
            <?php
            $authorsToFollow = get_random_feed_authors(3, get_current_user_id());
            foreach ($authorsToFollow as $authorToFollow) {
                $authorAvatar = get_user_meta($authorToFollow->ID, 'hub_custom_avatar', true);
                $authorAvatarUri = wp_get_attachment_thumb_url($authorAvatar);

                echo '<div class="feed-author-to-follow">
                    <div class="feed-author-inner">';
                        echo do_shortcode('[follow_links follow_id="' . $authorToFollow->ID . '"]');
                        echo '<a href="' . get_author_posts_url($authorToFollow->ID) . '" class="regular-link">';
                            if (!empty($authorAvatarUri)) {
                                echo '<img src="' . $authorAvatarUri . '" class="avatar" width="48" height="48" alt="">';
                            } else {
                                echo get_avatar($authorToFollow->ID, 48);
                            }
                            echo $authorToFollow->display_name;
                        echo '</a>
                    </div>
                </div>';
            }
            ?>
        </div>

        <div class="widget-container widget_text widget-most-liked">
            <h2>Popular Posters</h2>
            <?php echo feed_most_viewed(2); ?>
        </div>

        <?php if (is_active_sidebar('hub-widget-area')) { ?>
            <div id="primary" class="widget-area">
                <?php dynamic_sidebar('hub-widget-area'); ?>
            </div>
        <?php } ?>
    </div>
</aside>
