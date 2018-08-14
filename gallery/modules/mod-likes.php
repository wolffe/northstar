<?php
add_action('wp_ajax_nopriv_imagepress-like', 'imagepress_like');
add_action('wp_ajax_imagepress-like', 'imagepress_like');

function imagepress_like() {
    $nonce = $_POST['nonce'];

    if (isset($_POST['imagepress_like'])) {
        $post_id = $_POST['post_id']; // post id
		$like_count = get_post_meta($post_id, 'votes_count', true); // post like count

		if (function_exists('wp_cache_post_change')) { // invalidate WP Super Cache if exists
			$GLOBALS['super_cache_enabled'] = 1;
			wp_cache_post_change($post_id);
		}

		if (is_user_logged_in()) { // user is logged in
			$user_id = get_current_user_id(); // current user
			$meta_POSTS = get_user_option('_liked_posts', $user_id); // post ids from user meta
			$meta_USERS = get_post_meta($post_id, '_user_liked'); // user ids from post meta
			$liked_POSTS = NULL; // setup array variable
			$liked_USERS = NULL; // setup array variable

			if (count($meta_POSTS) != 0) { // meta exists, set up values
				$liked_POSTS = $meta_POSTS;
			}

			if (!is_array($liked_POSTS)) // make array just in case
				$liked_POSTS = [];

			if (count($meta_USERS) != 0) { // meta exists, set up values
				$liked_USERS = $meta_USERS[0];
			}

			if (!is_array($liked_USERS)) // make array just in case
				$liked_USERS = [];

			$liked_POSTS['post-' . $post_id] = $post_id; // add post id to user meta array
			$liked_USERS['user-' . $user_id] = $user_id; // add user id to post meta array
			$user_likes = count($liked_POSTS); // count user likes

			if (!AlreadyLiked($post_id)) { // like the post
				update_post_meta($post_id, '_user_liked', $liked_USERS); // add user ID to post meta
				update_post_meta($post_id, 'votes_count', ++$like_count); // +1 count post meta
				update_user_option($user_id, '_liked_posts', $liked_POSTS); // add post ID to user meta
				update_user_option($user_id, '_user_like_count', $user_likes); // +1 count user meta
				echo $like_count; // update count on front end

                // notification
                global $wpdb;
                $act_time = current_time('mysql', true);
                $wpdb->query("INSERT INTO " . $wpdb->prefix . "notifications (ID, userID, postID, actionType, actionTime) VALUES (null, $user_id, $post_id, 'loved', '$act_time')");
                //
			} else { // unlike the post
				$pid_key = array_search($post_id, $liked_POSTS); // find the key
				$uid_key = array_search($user_id, $liked_USERS); // find the key
				unset($liked_POSTS[$pid_key]); // remove from array
				unset($liked_USERS[$uid_key]); // remove from array
				$user_likes = count($liked_POSTS); // recount user likes
				update_post_meta($post_id, '_user_liked', $liked_USERS); // remove user ID from post meta
				update_post_meta($post_id, 'votes_count', --$like_count); // -1 count post meta
				update_user_option($user_id, '_liked_posts', $liked_POSTS); // remove post ID from user meta			
				update_user_option($user_id, '_user_like_count', $user_likes); // -1 count user meta
				echo 'already' . $like_count; // update count on front end
			}
		} else { // user is not logged in (anonymous)
			$ip = $_SERVER['REMOTE_ADDR']; // user IP address
			$meta_IPS = get_post_meta($post_id, '_user_IP'); // stored IP addresses
			$liked_IPS = NULL; // set up array variable

			if (count($meta_IPS) != 0) { // meta exists, set up values
				$liked_IPS = $meta_IPS[0];
			}

			if (!is_array($liked_IPS)) // make array just in case
				$liked_IPS = [];

			if (!in_array($ip, $liked_IPS)) // if IP not in array
				$liked_IPS['ip-' . $ip] = $ip; // add IP to array

			if (!AlreadyLiked($post_id)) { // like the post
				update_post_meta($post_id, '_user_IP', $liked_IPS); // add user IP to post meta
				update_post_meta($post_id, 'votes_count', ++$like_count); // +1 count post meta
				echo $like_count; // update count on front end
			} else { // unlike the post
				$ip_key = array_search( $ip, $liked_IPS ); // find the key
				unset($liked_IPS[$ip_key]); // remove from array
				update_post_meta($post_id, '_user_IP', $liked_IPS); // remove user IP from post meta
				update_post_meta($post_id, 'votes_count', --$like_count); // -1 count post meta
				echo "already".$like_count; // update count on front end
			}
		}
	}
	exit;
}

/**
 * Test if user already liked post
 */
function AlreadyLiked($post_id) { // test if user liked before
	if(is_user_logged_in()) { // user is logged in
		$user_id = get_current_user_id(); // current user
		$meta_USERS = get_post_meta($post_id, '_user_liked'); // user ids from post meta
		$liked_USERS = ''; // set up array variable

		if(count($meta_USERS) != 0) { // meta exists, set up values
			$liked_USERS = $meta_USERS[0];
		}

		if(!is_array($liked_USERS)) // make array just in case
			$liked_USERS = [];

		if(in_array($user_id, $liked_USERS)) { // true if user ID in array
			return true;
		}
		return false;
	}
	else { // user is anonymous, use IP address for voting
		$meta_IPS = get_post_meta($post_id, '_user_IP'); // get previously voted IP address
		$ip = $_SERVER['REMOTE_ADDR']; // retrieve current user IP
		$liked_IPS = ''; // set up array variable

		if(count($meta_IPS) != 0) { // meta exists, set up values
			$liked_IPS = $meta_IPS[0];
		}

		if(!is_array($liked_IPS)) // make array just in case
			$liked_IPS = [];

		if(in_array($ip, $liked_IPS)) { // true if IP in array
			return true;
		}
		return false;
	}
}

/**
 * Front end button
 */
function ipGetPostLikeLink($post_id) {
	$ip_vote_like = imagepress_get_like_count($post_id);
	$ip_vote_unlike = imagepress_get_like_count($post_id);
	$ip_vote_login = get_option('ip_vote_login');

	if (is_user_logged_in()) {
		$like_count = get_post_meta($post_id, 'votes_count', true); // get post likes
		if (AlreadyLiked($post_id)) {
			$class = esc_attr(' liked');
			$like = '<i class="fas fa-fw fa-heart"></i> <span class="ip-count-value">' . $ip_vote_unlike . '</span>';
		} else {
			$class = esc_attr('');
			$like = '<i class="far fa-fw fa-heart"></i> <span class="ip-count-value">' . $ip_vote_like . '</span>';
		}
		$output = '<a href="#" class="btn btn-primary imagepress-like' . $class . '" data-post_id="' . $post_id . '">' . $like . '</a>';
	}
	else {
		$output .= $ip_vote_login;
    }
    return $output;
}

/**
 * Front end button
 */
function ipGetFeedLikeLink($post_id) {
	$ip_vote_like = imagepress_get_like_count($post_id);
	$hidden = '';

	if ((int) $ip_vote_like == 0) {
		$hidden = 'display: none;';
	}
	$ip_vote_unlike = imagepress_get_like_count($post_id);
	$ip_vote_login = get_option('ip_vote_login');

	if(is_user_logged_in()) {
		$like_count = get_post_meta($post_id, 'votes_count', true); // get post likes
		if (AlreadyLiked($post_id)) {
			$class = esc_attr(' liked');
			$like = '<i class="fas fa-fw fa-heart"></i> <span class="ip-count-value" style="' . $hidden . '">' . $ip_vote_unlike . '</span>';
		} else {
			$class = esc_attr('');
			$like = '<i class="far fa-fw fa-heart"></i> <span class="ip-count-value" style="' . $hidden . '">' . $ip_vote_like . '</span>';
		}
		$output = '<a href="#" class="feed-like' . $class . '" data-post_id="' . $post_id . '">' . $like . '</a>';
	}
	else {
		$output .= $ip_vote_login;
    }
    return $output;
}

/**
 * If the user is logged in, output a list of posts that the user likes
 */
function frontEndUserLikes($author) {
    $user_likes = get_user_option('_liked_posts', $author);
    $the_likes = '';
    $ip_ipp = get_option('ip_ipp');

    if (!empty($user_likes) && count($user_likes) > 0) {
        $the_likes = $user_likes;
    }

    if (!is_array($the_likes)) {
        $the_likes = [];
    }
    $the_likes = array_reverse($the_likes);
    $count = count($the_likes);

    $like_list = '<div class="cinnamon-likes" id="cinnamon-love">';
        if ($count > 0) {
            $the_likes = implode(',', $the_likes);
            $like_list .= do_shortcode('[imagepress-show meta="love" metaids="' . $the_likes . '" count="' . $ip_ipp . '" size="imagepress_pt_lrg"]');
        }
    $like_list .= '</div>';

    return $like_list;
}
function frontEndUserLikesCount($author) {
    $user_likes = get_user_option('_liked_posts', $author);
    if (!empty($user_likes) && count($user_likes) > 0) {
        $the_likes = $user_likes;
    } else {
        $the_likes = '';
    }

    if (!is_array($the_likes)) {
        $the_likes = [];
    }

    $count = count($the_likes);

    return $count;
}



function imagepress_get_like_users($id) {
    $meta_USERS = get_post_meta($id, '_user_liked');
    $totalUsers = array_sum(array_map('count', $meta_USERS));
    echo '<div style="position: relative;" id="ip-who-value">';
        if($totalUsers == 0)
            echo '<div class="slide" style="cursor: pointer; font-size: 80%; text-align: center;"><span class="imagepress-update-count">' . get_option('ip_vote_nobody') . '</div>';
        if($totalUsers == 1)
            echo '<div class="slide" style="cursor: pointer; font-size: 80%; text-align: center;"><span class="imagepress-update-count">' . $totalUsers . '</span> ' . get_option('ip_vote_who_singular') . '<span class="teal"> (' . get_option('ip_vote_who_link') . ')</span></div>';
        if($totalUsers > 1)
            echo '<div class="slide" style="cursor: pointer; font-size: 80%; text-align: center;"><span class="imagepress-update-count">' . $totalUsers . '</span> ' . get_option('ip_vote_who_plural') . '<span class="teal"> (' . get_option('ip_vote_who_link') . ')</span></div>';
        echo '<div class="view" style="position: absolute; background-color: #2c3e50; padding: 16px; max-height: 300px; overflow: auto; z-index: 100;">';
            if($totalUsers == 0) {
                echo '<div>' . get_option('ip_vote_nobody') . '</div>';
            }
            else {
                echo '<div><small>' . get_option('ip_vote_who') . '<br></small></div>';
                foreach($meta_USERS as $users) {
                    foreach($users as $user) {
                        echo '<small><i class="fas fa-user"></i></small> <a href="' . get_author_posts_url($user) . '">' . get_the_author_meta('nickname', $user) . '</a><br>';
                    }
                }
            }
        echo '</div>';
    echo '</div>';
}


function imagepress_get_users_love($id) {
    $user_id = get_current_user_id(); // current user

    $meta_USERS = get_post_meta($id, '_user_liked');
    $totalUsers = array_sum(array_map('count', $meta_USERS));

    if(!empty($meta_USERS[0])) {
        if(array_key_exists('user-' . $user_id, $meta_USERS[0])) {
            $who = 'You';
            //$totalUsers--;
        } else {
            $who = get_the_author_meta('nickname', end($meta_USERS[0]));
        }
    }

    echo '<div style="position: relative;" id="ip-who-value">';
        if($totalUsers == 1 && $who == 'You') {
            echo '<div class="slide" style="cursor: pointer; font-size: 80%; text-align: center; margin: 10px 0 0 0;"><i class="fas fa-heart"></i> You love this poster</div>';
        } else if($totalUsers == 0) {
            echo '<div class="slide" style="cursor: pointer; font-size: 80%; text-align: center; margin: 10px 0 0 0;"><i class="fas fa-heart"></i> Be the first to love this poster</div>';
        } else if($totalUsers == 1) {
            echo '<div class="slide" style="cursor: pointer; font-size: 80%; text-align: center; margin: 10px 0 0 0;"><i class="fas fa-heart"></i> ' . $who . ' loves this</div>';
        } else if($totalUsers > 1 && $who == 'You') {
            $totalUsers--;
            echo '<div class="slide" style="cursor: pointer; font-size: 80%; text-align: center; margin: 10px 0 0 0;"><i class="fas fa-heart"></i> ' . $who . ' and <span class="teal"><span class="imagepress-update-count">' . $totalUsers . '</span> others</span></div>';
        } else if($totalUsers > 1 && $who != 'You') {
            $totalUsers--;
            echo '<div class="slide" style="cursor: pointer; font-size: 80%; text-align: center; margin: 10px 0 0 0;"><i class="fas fa-heart"></i> ' . $who . ' and <span class="teal"><span class="imagepress-update-count">' . $totalUsers . '</span> others</span></div>';
        }

        echo '<div class="view" style="display: none; position: absolute; background-color: #111111; padding: 16px; max-height: 300px; overflow: auto; z-index: 100; text-align: left; width: 100%; border-radius: 3px;">';

        if($totalUsers > 0) {
            echo '<div><small>' . get_option('ip_vote_who') . '<br></small></div>';
            foreach($meta_USERS as $users) {
                foreach($users as $user) {
                    if(!empty(get_the_author_meta('nickname', $user))) {
                        $u = get_the_author_meta('nickname', $user);
                    } else {
                        $u = get_the_author_meta('username', $user);
                    }
                    echo '<small><i class="fas fa-user"></i></small> <a href="' . get_author_posts_url($user) . '">' . $u . '</a><br>';
                }
            }
        }
        echo '</div>';
    echo '</div>';
}

function imagepress_get_like_count($id) {
    $meta_USERS = get_post_meta($id, '_user_liked');
    $totalUsers = array_sum(array_map('count', $meta_USERS));

    return $totalUsers;
}
?>
