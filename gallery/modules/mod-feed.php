<?php
function imagepress_feed() {
    global $wpdb;

    $user_ID = get_current_user_id();

    $myFollowing = [pwuf_get_following($user_ID)];
    $myFollowing = array_unique($myFollowing);

    $followers = implode(',', $myFollowing[0]);

    $authorName = get_the_author_meta('first_name', $user_ID);
    if (empty($authorName)) {
        $authorName = get_the_author_meta('display_name', $user_ID);
    }
    ?>

    <h3 class="feed-welcome">Welcome back, <?php echo $authorName; ?></h3>

    <div class="feed-actions" style="display:none;">
        <div class="feed-action-following">Following</div>
        <div class="feed-action-discover feed-action-inactive hint hint--right" data-hint="Coming soon">Discover</div>
    </div>

    <h4 class="feed-title">Your Feed</h4>

    <div class="feed-container">
        <div id="feed-data">
            <?php
            // Sticky feed ads
            $stickyFeedAdArgs = [
                'post_type' => ['feed-ad'],
                'posts_per_page' => 1,
                'meta_key' => 'ad_type',
                'meta_query' => [
                    'key' => 'ad_type',
                    'value' => 'sticky',
                ],
            ];
            $stickyFeedAdQuery = get_posts($stickyFeedAdArgs);
            foreach ($stickyFeedAdQuery as $feedAd) {
                // Check if post exists and is published
                if ('publish' === get_post_status($feedAd->ID) && has_post_thumbnail($feedAd->ID)) {
                    $posterUri = get_the_post_thumbnail_url($feedAd->ID, 'imagepress_feed');
                    $ad_avatar = get_field('ad_avatar', $feedAd->ID);
                    $ad_type = get_field('ad_type', $feedAd->ID);
                    $ad_custom_link = get_field('ad_custom_link', $feedAd->ID);
                    $permalink = get_permalink($feedAd->ID);
                    if (!empty($ad_custom_link)) {
                        $permalink = $ad_custom_link;
                    }

                    echo '<div class="feed-item n' . $line->ID . '" data-id="' . $line->ID . '" data-sticky="' . $ad_type . '" id="item' . $line->ID . '">
                        <div class="feed-meta-primary">
                            <div class="feed-avatar"><img src="' . $ad_avatar . '" width="48" height="48" alt="" class="avatar"></div>
                            <a href="' . $permalink . '" class="regular-link">' . get_post_field('post_content', $feedAd->ID) . '</a>
                        </div>
                        <div class="feed-meta-secondary">
                            <a href="' . $permalink . '"><img src="' . $posterUri . '" alt="' . get_the_title($feedAd->ID) . '"></a>
                            <p>
                                <a href="' . $permalink . '" class="imagepress-button" style="width: 100%; border-radius: 3px;">Learn More</a>
                            </p>
                        </div>
                    </div>';
                }
            }
            //

            // Feed loop
            $sql = "SELECT ID, userID, postID, postKeyID, actionType, actionTime, actionIcon, status FROM " . $wpdb->prefix . "notifications WHERE actionType = 'added' AND userID IN (" . $followers . ") UNION ALL SELECT ID, userID, postID, postKeyID, actionType, actionTime, actionIcon, status FROM " . $wpdb->prefix . "notifications WHERE actionType = 'ad' ORDER BY ID DESC LIMIT 20";

            $res = $wpdb->get_results($sql);

            include 'feed-data.php';
            ?>
        </div>
        <div class="ajax-load feed-loading" style="display: none;"></div>

        <script>
        jQuery(window).scroll(function() {
            if (jQuery(window).scrollTop() + jQuery(window).height() >= (jQuery(document).height()) - 128) {
                var last_id = jQuery(".feed-item:last-child").attr("data-id");

                loadMoreData(last_id);
            }
        });

        function loadMoreData(last_id) {
            jQuery.ajax({
                url: 'https://posterspy.com/wp-content/plugins/imagepress/modules/loadMoreData.php?last_id=' + last_id,
                type: "get",
                beforeSend: function() {
                    jQuery('.ajax-load').show();
                }
            }).done(function(data) {
                jQuery('.ajax-load').hide();
                jQuery("#feed-data").append(data);

                var duplicateChk = {};

                jQuery('.feed-item').each(function() {
                    if (duplicateChk.hasOwnProperty(this.id)) {
                        jQuery(this).remove();
                    } else {
                        duplicateChk[this.id] = 'true';
                    }
                });
            }).fail(function(jqXHR, ajaxOptions, thrownError) {
                // console.log('Feed not responding...');
            });
        }
        </script>
    </div>
    <?php
}



add_action( "pre_user_query", function( $query ) {
    if( "rand" == $query->query_vars["orderby"] ) {
        $query->query_orderby = str_replace( "user_login", "RAND()", $query->query_orderby );
    }
});
function get_random_feed_authors($number, $currentUserId) {
    $myFollowers = [pwuf_get_following($currentUserId)];
    $myFollowers = array_unique($myFollowers);
    $myFollowersToString = implode(',', $myFollowers[0]);

    $users = get_users([
        'fields' => ['ID', 'display_name'],
        'orderby' => 'rand',
        'number' => $number,
        'who' => 'authors',
        'exclude' => $myFollowers[0],
        'has_published_posts' => get_post_types(['public' => true]),
        // 'query_id' => 'authors_with_posts',
    ]);
    //shuffle($users);


    return $users;
}

function feed_most_viewed($count) {
    // Get transient
    $is = get_transient('popular-posters');

    if (false === ($the_query = get_transient('popular-posters'))) {
        $args = [
            'post_type' => 'poster',
            'posts_per_page' => $count,
            'orderby' => 'meta_value_num',
            'meta_key' => 'post_views_count',
            'meta_query' => [
                [
                    'key' => 'post_views_count',
                    'type' => 'numeric'
                ]
            ],
            'date_query' => [
                [
                    'after' => '1 week ago'
                ]
            ],
        ];

        $is = get_posts($args);

        // Set transient, and expire after 24 hours
        set_transient('popular-posters', $is, 1 * DAY_IN_SECONDS);
    }

    if ($is) {
        $display = '<ul>';
            foreach ($is as $i) {
                $post_thumbnail_id = get_post_thumbnail_id($i->ID);   
                $postAuthor = $i->post_author;

                if (has_post_thumbnail($i->ID)) {
                    $display .= '<li>';
                        $display .= do_shortcode('[follow_links follow_id="' . $postAuthor . '"]');
                        $display .= '<a href="' . get_permalink($i->ID) . '" class="regular-link"><b>' . get_the_title($i->ID) . '</b></a><br><small>by <a href="' . get_author_posts_url($postAuthor) . '" class="regular-link">' . get_the_author_meta('display_name', $postAuthor) . '</a></small><br>';
                        $display .= '<br>
                        <a href="' . get_permalink($i->ID) . '">' . wp_get_attachment_image($post_thumbnail_id, 'imagepress_feed') . '</a>
                    </li>';
                }
            }
        $display .= '</ul>';
    }

    return $display;
}



function get_creative_briefs() {
    $args = [
        'post_type' => 'creative-brief',
        'posts_per_page' => 2,
    ];
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
}



add_filter('avatar_defaults', 'ps_default_gravatar');

function ps_default_gravatar($avatar_defaults) {
    $myavatar = 'https://posterspy.com/wp-content/themes/moon-ui-theme/img/avatar.jpg';
    $avatar_defaults[$myavatar] = 'Default PosterSpy Avatar';

    return $avatar_defaults;
}
