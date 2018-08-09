<?php
foreach ($res as $line) {
    $action = $line->actionType;
    $nickname = get_the_author_meta('nickname', $line->userID);
    $time = human_time_diff(strtotime($line->actionTime), current_time('timestamp')) . ' ago';

    $postdata = get_post($line->postID, ARRAY_A);
    $authorID = $postdata['post_author'];

    /**
    if($action == 'loved' && $user_ID == $authorID)
        $display .= '<div class="notification-item n' . $line->ID . '" data-id="' . $line->ID . '"><div class="navatar">' . get_avatar($line->userID, 48) . '</div><i class="fas fa-fw fa-heart"></i> <a href="' . get_author_posts_url($line->userID) . '">' . $nickname . '</a> ' . $action . ' your poster <a href="' . get_permalink($line->postID) . '">' . get_the_title($line->postID) . '</a><time>' . $time . '</time></div>';

    if($action == 'collected' && $user_ID == $authorID)
        $display .= '<div class="notification-item n' . $line->ID . '" data-id="' . $line->ID . '"><div class="navatar">' . get_avatar($line->userID, 48) . '</div><i class="fas fa-fw fa-folder"></i> <a href="' . get_author_posts_url($line->userID) . '">' . $nickname . '</a> ' . $action . ' your poster <a href="' . get_permalink($line->postID) . '">' . get_the_title($line->postID) . '</a><time>' . $time . '</time></div>';
    /**/



    // New upload
    $posterUri = get_the_post_thumbnail_url($line->postID, 'imagepress_feed');

	if ((string) $action === 'added' && pwuf_is_following($user_ID, $authorID)) {
        // Check if post exists and is published
        if ('publish' === get_post_status($line->postID) && has_post_thumbnail($line->postID)) {
            $verified = '';
            if (get_the_author_meta('user_title', $line->userID) == 'Verified') {
                $verified = ' <span class="teal hint hint--right" data-hint="' . get_option('cms_verified_profile') . '"><i class="fas fa-check-circle"></i></span>';
            }

            echo '<div class="feed-item n' . $line->ID . '" data-id="' . $line->ID . '" id="item' . $line->ID . '">
                <div class="feed-meta-primary">
                    <div class="feed-avatar">' . get_avatar($line->userID, 48) . '</div>
                    <a href="' . get_author_posts_url($line->userID) . '" class="regular-link">' . $nickname . '</a> ' . $verified . ' uploaded <a href="' . get_permalink($line->postID) . '" class="regular-link">' . get_the_title($line->postID) . '</a>
                </div>
                <div class="feed-meta-secondary">
                    <a href="' . get_permalink($line->postID) . '"><img src="' . $posterUri . '" alt="' . get_the_title($line->postID) . '" width="700"></a>
                </div>
                <div class="feed-meta-tertiary">
                    <time>' . $time . '</time>
                    <a href="' . get_permalink($line->postID) . '"><i class="fas fa-comment" aria-hidden="true"></i> ' . get_comments_number($line->postID) . '</a> <span class="feed-pipe">|</span> ' . ipGetFeedLikeLink($line->postID) . '
                </div>
            </div>';
        }
    }

    if ((string) $action === 'ad') {
        // Check if post exists and is published
        if ('publish' === get_post_status($line->postID) && has_post_thumbnail($line->postID)) {
            $ad_type = get_field('ad_type', $line->postID);

            if ($ad_type === 'nonsticky') {
                $ad_avatar = get_field('ad_avatar', $line->postID);
                $ad_custom_link = get_field('ad_custom_link', $line->postID);
                $permalink = get_permalink($line->postID);
                if (!empty($ad_custom_link)) {
                    $permalink = $ad_custom_link;
                }

                echo '<div class="feed-item n' . $line->ID . '" data-id="' . $line->ID . '" data-sticky="' . $ad_type . '" id="item' . $line->ID . '">
                    <div class="feed-meta-primary">
                        <div class="feed-avatar"><img src="' . $ad_avatar . '" width="48" height="48" alt="" class="avatar"></div>
                        <a href="' . $permalink . '" class="regular-link">' . get_post_field('post_content', $line->postID) . '</a>
                    </div>
                    <div class="feed-meta-secondary">
                        <a href="' . $permalink . '"><img src="' . $posterUri . '" alt="' . get_the_title($line->postID) . '" width="700"></a>
                        <p>
                            <a href="' . $permalink . '" class="imagepress-button" style="width: 100%; border-radius: 3px;">Learn More</a>
                        </p>
                    </div>
                </div>';
            }
        }
    }
}
