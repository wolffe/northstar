<?php
$parse_uri = explode( 'wp-content', $_SERVER['SCRIPT_FILENAME'] );
require_once( $parse_uri[0] . 'wp-load.php' );

$id = $_POST['id'];

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

$out = '<div style="position: relative;" id="ip-who-value">';
    if($totalUsers == 1 && $who == 'You') {
        $out .= '<div class="slide" style="cursor: pointer; font-size: 80%; text-align: center; margin: 10px 0 0 0;"><i class="fas fa-heart"></i> You love this poster</div>';
    } else if($totalUsers == 0) {
        $out .= '<div class="slide" style="cursor: pointer; font-size: 80%; text-align: center; margin: 10px 0 0 0;"><i class="fas fa-heart"></i> Be the first to love this poster</div>';
    } else if($totalUsers == 1) {
        $out .= '<div class="slide" style="cursor: pointer; font-size: 80%; text-align: center; margin: 10px 0 0 0;"><i class="fas fa-heart"></i> ' . $who . ' loves this</div>';
    } else if($totalUsers > 1 && $who == 'You') {
        $totalUsers--;
        $out .= '<div class="slide" style="cursor: pointer; font-size: 80%; text-align: center; margin: 10px 0 0 0;"><i class="fas fa-heart"></i> ' . $who . ' and <span class="teal"><span class="imagepress-update-count">' . $totalUsers . '</span> others</span></div>';
    } else if($totalUsers > 1 && $who != 'You') {
        $totalUsers--;
        $out .= '<div class="slide" style="cursor: pointer; font-size: 80%; text-align: center; margin: 10px 0 0 0;"><i class="fas fa-heart"></i> ' . $who . ' and <span class="teal"><span class="imagepress-update-count">' . $totalUsers . '</span> others</span></div>';
    }

    $out .= '<div class="view" style="display: none; position: absolute; background-color: #111111; padding: 16px; max-height: 300px; overflow: auto; z-index: 100; text-align: left; width: 100%; border-radius: 2px;">';

    if($totalUsers > 0) {
        $out .= '<div><small>' . get_option('ip_vote_who') . '<br></small></div>';
        foreach($meta_USERS as $users) {
            foreach($users as $user) {
                if(!empty(get_the_author_meta('nickname', $user))) {
                    $u = get_the_author_meta('nickname', $user);
                } else {
                    $u = get_the_author_meta('username', $user);
                }
                $out .= '<small><i class="fas fa-user"></i></small> <a href="' . get_author_posts_url($user) . '">' . $u . '</a><br>';
            }
        }
    }
    $out .= '</div>';
$out .= '</div>';

echo $out;
?>
