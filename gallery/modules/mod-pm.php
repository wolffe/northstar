<?php
add_shortcode('pm', 'pm_display');

function pm_display() {
    global $wpdb;

    $myFollowing = [pwuf_get_following($user_ID)];
    $myFollowing = array_unique($myFollowing);
    $followers = implode(',', $myFollowing[0]);

    // Get distinct messages per user
    $pmUsers = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "pm WHERE pm_receiver_id = '" . get_current_user_id() . "' AND pm_sender_id IN (" . $followers . ") ORDER BY pm_timestamp DESC LIMIT 1", ARRAY_A);

    // get messages
    //$pms = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "pm WHERE pm_receiver_id = '" . get_current_user_id() . "' AND pm_sender_id IN (" . $followers . ") ORDER BY pm_timestamp DESC", ARRAY_A);
    //$pmsCount = $wpdb->num_rows;

    // get messages
    $pmsOther = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "pm WHERE pm_receiver_id = '" . get_current_user_id() . "' AND pm_sender_id NOT IN (" . $followers . ") ORDER BY pm_timestamp DESC", ARRAY_A);
    $pmsOtherCount = $wpdb->num_rows;

    $out = '<div class="pm-container">
        <div id="pm-message"></div>
        <div class="pm-left">
            <span id="pm-settings"><i class="fas fa-cog" aria-hidden="true"></i></span>

            <div class="pm-sidebar" id="pm-direct">';

                foreach ($pmUsers as $pm) {
                    $out .= '<div class="pm-message-single" data-sender="' . $pm['pm_sender_id'] . '" data-message="' . $pm['pm_message'] . '" data-caption="Messaging with ' . get_the_author_meta('display_name', $pm['pm_sender_id']) . '">' . get_avatar($pm['pm_sender_id'], 40) . get_the_author_meta('display_name', $pm['pm_sender_id']) . '<br><em>' . substr($pm['pm_message'], 0, 16) . '...<span title="' . date(get_option('date_format'), strtotime($pm['pm_timestamp'])) . '">' . date(get_option('time_format'), strtotime($pm['pm_timestamp'])) . '</span></em></div>';
                }

            $out .= '</div>
            <div class="pm-sidebar" id="pm-requests">
                <h4>Other messages</h4>';

                foreach ($pmsOther as $pmOther) {
                    $out .= '<div><a href="' . get_author_posts_url($pm['pm_sender_id']) . '">' . get_the_author_meta('display_name', $pm['pm_sender_id']) . '</a> on ' . $pmOther['pm_timestamp'] . '</div>
                    <div>' . $pmOther['pm_message'] . '</div>';
                }

                $pm_enabled = get_user_meta(get_current_user_id(), '_enable_pm', true);

            $out .= '</div>
        </div>
        <div class="pm-right">
            <div id="pm-load-limit" data-limit="5" data-receiver="' . get_current_user_id() . '">Load more...</div>
            <div class="pm-right-inner"><div class="pm-right-inner--centered">Select a user from the left or start a new conversation.</div></div>
            <div class="pm-new">
                <form method="post" id="pm_send_form" onsubmit="return false;">
                    <input type="hidden" name="pm_to" id="pm_to" value="' . $pm['pm_sender_id'] . '">
                    <p>
                        <input type="text" id="pm_message" name="pm_message" placeholder="Type your message..." autocomplete="off">
                        <br><small>Press enter to send</small>
                    </p>
                </form>
            </div>
        </div>
        <div style="clear:both"></div>

    <section class="whiskey-tab-contenta" id="all" style="display:none">
        <h4>New message</h4>
        <form method="post">
            <div class="frmSearch">
                To:<br>
                <input type="text" name="pm_to" id="pm_to">
                <input type="text" id="search-box" placeholder="Type and select a user..." autocomplete="off">
                <div id="suggesstion-box"></div>
            </div>
            <p>
                Message:<br><textarea id="pm_message" name="pm_message" rows="6" cols="64"></textarea>
            </p>
            <p>
                <input type="submit" id="pm_send" name="pm_send" value="Send">
            </p>
        </form>
    </section>
    <section class="whiskey-tab-contenta" id="settings">
        <h4>General Settings</h4>
        <p>
            <input type="checkbox" name="pm_enable" id="pm-enable" data-user-id="' . get_current_user_id() . '" value="' . $pm_enabled . '"> <label for="pm-enable">Enable message requests</label>
        </p>
    </section>
    </div>';

    return $out;
}

add_action('wp_ajax_ip_user_select', 'ip_user_select');
add_action('wp_ajax_ip_user_pm_enable', 'ip_user_pm_enable');

add_action('wp_ajax_ip_get_pm_thread', 'ip_get_pm_thread');
add_action('wp_ajax_ip_post_pm_thread', 'ip_post_pm_thread');



//$users = get_users( ['fields' => ['ID'] ] );
//foreach ( $users as $user ) {
//    $user_update = update_user_meta($user->ID, '_enable_pm', 1);
//}
//add_action( 'user_register', 'posterspy_registration_save', 10, 1 );

function posterspy_registration_save( $user_id ) {
    update_user_meta($user_id, '_enable_pm', 1);
}



function ip_user_select() {
    global $wpdb;

    $username = $_POST['username'];

    $userquery = "SELECT u.ID, u.user_login, u.user_nicename, u.user_email, u.display_name
        FROM $wpdb->users u
        INNER JOIN $wpdb->usermeta m ON m.user_id = u.ID
    WHERE (user_nicename LIKE '" . $username . "%' OR display_name LIKE '" . $username . "%')
        AND m.meta_key = '_enable_pm'
        AND m.meta_value = 1
    ORDER BY u.user_nicename LIMIT 0, 8";

	//$result = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "users WHERE user_nicename LIKE '" . $username . "%' OR display_name LIKE '" . $username . "%' ORDER BY user_nicename LIMIT 0, 8", ARRAY_A);
	$result = $wpdb->get_results($userquery, ARRAY_A);

    echo '<ul id="userlist">';
        foreach($result as $user) {
            $name = (empty($user['display_name']) ? $user['user_nicename'] : $user['display_name']);
            echo '<li onclick="selectUser(\'' . $user['ID'] . '\', \'' . $name . '\');">' . get_avatar($user['ID'], 24) . $name . '</li>';
        }
    echo '</ul>';

	die();
}

function ip_user_pm_enable() {
    global $wpdb;

    $pm_user_id = $_POST['pm_user_id'];
    $pm_value = $_POST['pm_value'];

    update_user_meta($pm_user_id, '_enable_pm', $pm_value);

	die();
}

function ip_get_pm_thread() {
    global $wpdb;

    $pm_user_id = $_POST['user_id'];
    $pm_limit = is_numeric($_POST['pm_message_limit']) ? $_POST['pm_message_limit'] : 5;
    $pm_limit = (int) $_POST['pm_message_limit'];

    $myFollowing = [pwuf_get_following($pm_user_id)];
    $myFollowing = array_unique($myFollowing);
    $followers = implode(',', $myFollowing[0]);

    // get messages
    /**
    $pmsFrom = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "pm WHERE pm_receiver_id = " . get_current_user_id() . " AND pm_sender_id = $pm_user_id ORDER BY pm_timestamp DESC", ARRAY_A);

    $pmsTo = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "pm WHERE pm_receiver_id = $pm_user_id AND pm_sender_id = " . get_current_user_id() . " ORDER BY pm_timestamp DESC", ARRAY_A);
    /**/

    $pms = $wpdb->get_results("SELECT pm_id FROM " . $wpdb->prefix . "pm WHERE (pm_receiver_id = " . get_current_user_id() . " AND pm_sender_id = $pm_user_id) OR (pm_receiver_id = $pm_user_id AND pm_sender_id = " . get_current_user_id() . ") ORDER BY pm_timestamp DESC", ARRAY_A);
    $pmsCount = $wpdb->num_rows;

    $pms = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "pm WHERE (pm_receiver_id = " . get_current_user_id() . " AND pm_sender_id = $pm_user_id) OR (pm_receiver_id = $pm_user_id AND pm_sender_id = " . get_current_user_id() . ") ORDER BY pm_timestamp DESC LIMIT $pm_limit", ARRAY_A);

    $out = '';
    $messageArray = [];

    /**
    foreach ($pmsFrom as $pm) {
        $messageArray[] = [
            'sender' => $pm['pm_sender_id'],
            'timestamp' => $pm['pm_timestamp'],
            'message' => $pm['pm_message'],
            'type' => 'sender',
        ];
    }
    foreach ($pmsTo as $pm) {
        $messageArray[] = [
            'sender' => $pm['pm_sender_id'],
            'timestamp' => $pm['pm_timestamp'],
            'message' => $pm['pm_message'],
            'type' => 'receiver',
        ];
    }
    /**/

    foreach ($pms as $pm) {
        $messageArray[] = [
            'sender' => $pm['pm_sender_id'],
            'timestamp' => $pm['pm_timestamp'],
            'message' => $pm['pm_message'],
            'type' => '',
        ];
    }

    usort($messageArray, function($a, $b) {
        return $a['timestamp'] <=> $b['timestamp'];
    });

    foreach ($messageArray as $message) {
        if ((int) $message[sender] === get_current_user_id()) {
            $message['type'] = 'receiver';
        } else {
            $message['type'] = 'sender';
        }

        $out .= '<div class="pm-row ' . $message['type'] . '" data-timestamp="' . $message['timestamp'] . '" data-sender="' . get_the_author_meta('display_name', $message['sender']) . '">' . get_avatar($message['sender'], 32) . '<span>' . $message['message'] . '</span></div>';
    }

    $out .= '<div style="clear:both"></div>';

    echo $out;

	die();
}

function ip_post_pm_thread() {
    global $wpdb;

    $pm_sender_id = get_current_user_id();
    $pm_receiver_id = $_POST['receiver'];
    $pm_message = stripslashes($_POST['message']);

    //$pm_timestamp = date(get_option('date_format') . ' ' . get_option('time_format'), strtotime($pm['pm_timestamp']));

    $wpdb->query($wpdb->prepare("INSERT INTO " . $wpdb->prefix . "pm (pm_sender_id, pm_receiver_id, pm_message) VALUES (%d, %d, '%s')", $pm_sender_id, $pm_receiver_id, $pm_message));
}
