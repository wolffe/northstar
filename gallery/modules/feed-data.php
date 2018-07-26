<?php
foreach ($res as $line) {
    $action = $line->actionType;
    $nickname = get_the_author_meta('nickname', $line->userID);
    $time = human_time_diff(strtotime($line->actionTime), current_time('timestamp')) . ' ago';

    $postdata = get_post($line->postID, ARRAY_A);
    $authorID = $postdata['post_author'];

    /**
    if($action == 'loved' && $user_ID == $authorID)
        $display .= '<div class="notification-item n' . $line->ID . '" data-id="' . $line->ID . '"><div class="navatar">' . get_avatar($line->userID, 48) . '</div><i class="fa fa-fw fa-heart"></i> <a href="' . get_author_posts_url($line->userID) . '">' . $nickname . '</a> ' . $action . ' your ' . $ip_slug . ' <a href="' . get_permalink($line->postID) . '">' . get_the_title($line->postID) . '</a><time>' . $time . '</time></div>';

    if($action == 'collected' && $user_ID == $authorID)
        $display .= '<div class="notification-item n' . $line->ID . '" data-id="' . $line->ID . '"><div class="navatar">' . get_avatar($line->userID, 48) . '</div><i class="fa fa-fw fa-folder"></i> <a href="' . get_author_posts_url($line->userID) . '">' . $nickname . '</a> ' . $action . ' your ' . $ip_slug . ' <a href="' . get_permalink($line->postID) . '">' . get_the_title($line->postID) . '</a><time>' . $time . '</time></div>';
    /**/



    // New upload
    $posterUri = get_the_post_thumbnail_url($line->postID, 'imagepress_feed');

	if ((string) $action === 'added' && pwuf_is_following($user_ID, $authorID)) {
        // Check if post exists and is published
        if ('publish' === get_post_status($line->postID) && has_post_thumbnail($line->postID)) {
            $verified = '';
            if (get_the_author_meta('user_title', $line->userID) == 'Verified') {
                $verified = ' <span class="teal hint hint--right" data-hint="' . get_option('cms_verified_profile') . '"><i class="fa fa-check-circle"></i></span>';
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
                    <a href="' . get_permalink($line->postID) . '"><i class="fa fa-comment" aria-hidden="true"></i> ' . get_comments_number($line->postID) . '</a> <span class="feed-pipe">|</span> ' . ipGetFeedLikeLink($line->postID) . '
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


    /**
    if($action == 'followed' && $user_ID == $line->postID)
        $display .= '<div class="notification-item n' . $line->ID . '" data-id="' . $line->ID . '"><div class="navatar">' . get_avatar($line->userID, 48) . '</div><i class="fa fa-fw fa-plus-circle"></i> <a href="' . get_author_posts_url($line->userID) . '">' . $nickname . '</a> ' . $line->actionType . ' you<time>' . $time . '</time></div>';

    if($action == 'commented on' && $user_ID == $authorID && $user_ID != $line->userID)
        $display .= '<div class="notification-item n' . $line->ID . '" data-id="' . $line->ID . '"><div class="navatar">' . get_avatar($line->userID, 48) . '</div><i class="fa fa-fw fa-comment"></i> <a href="' . get_author_posts_url($line->userID) . '">' . $nickname . '</a> ' . $action . ' your ' . $ip_slug . ' <a href="' . get_permalink($line->postID) . '">' . get_the_title($line->postID) . '</a><time>' . $time . '</time></div>';

    if($action == 'replied to a comment on') {
        $comment_id = get_comment($line->postID);
        $comment_post_ID = $comment_id->comment_post_ID;
        $b = $comment_id->user_id;

        if($user_ID == $b)
            $display .= '<div class="notification-item n' . $line->ID . '" data-id="' . $line->ID . '"><div class="navatar">' . get_avatar($line->userID, 48) . '</div><i class="fa fa-fw fa-comment"></i> <a href="' . get_author_posts_url($line->userID) . '">' . $nickname . '</a> replied to your comment on <a href="' . get_permalink($comment_post_ID) . '">' . get_the_title($comment_post_ID) . '</a><time>' . $time . '</time></div>';
    }

    if($action == 'featured' && $user_ID == $authorID && $user_ID != $line->userID)
        $display .= '<div class="notification-item n' . $line->ID . '" data-id="' . $line->ID . '"><div class="navatar">' . get_the_post_thumbnail($line->postID, array(48,48)) . '</div><i class="fa fa-fw fa-star"></i> Your <a href="' . get_permalink($line->postID) . '">' . get_the_title($line->postID) . '</a> ' . $ip_slug . ' was ' . $action . '<time>' . $time . '</time></div>';
    /**/

    // custom
    /**
    if(0 == $line->postID || '-1' == $line->postID) {
        $attachment_id = get_option('notification_thumbnail_custom');
        $image_attributes = wp_get_attachment_image_src($attachment_id, array(48,48));

        $display .= '<div class="notification-item n' . $line->ID . '" data-id="' . $line->ID . '"><div class="navatar"><img src="' .  $image_attributes[0] . '" width="' . $image_attributes[1] . '" height="' . $image_attributes[2] . '"></div><i class="fa fa-fw ' . $line->actionIcon . '"></i> ' . $line->actionType . '<time>' . $time . '</time></div>';
    }
    /**/
}
