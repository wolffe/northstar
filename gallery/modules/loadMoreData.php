<?php
require '../../../../wp-load.php';

$user_ID = get_current_user_id();

$myFollowing = [pwuf_get_following($user_ID)];
$followers = implode(',', $myFollowing[0]);

$sql = "SELECT ID, userID, postID, postKeyID, actionType, actionTime, actionIcon, status FROM " . $wpdb->prefix . "notifications WHERE actionType = 'added' AND userID IN (" . $followers . ") AND ID < '" . $_GET['last_id'] . "' UNION ALL SELECT ID, userID, postID, postKeyID, actionType, actionTime, actionIcon, status FROM " . $wpdb->prefix . "notifications WHERE actionType = 'ad' ORDER BY ID DESC LIMIT 10";

$res = $wpdb->get_results($sql);

$json = include 'feed-data.php';
