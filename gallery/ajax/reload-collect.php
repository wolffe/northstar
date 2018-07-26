<?php
$parse_uri = explode( 'wp-content', $_SERVER['SCRIPT_FILENAME'] );
require_once( $parse_uri[0] . 'wp-load.php' );

// poster ID
$ip_id = (int) $_POST['pid'];

// collection ID (if existing)
$ip_collections = (int) $_POST['cid'];

// collection name and status (if new)
$ip_collections_new = sanitize_text_field($_POST['cnew']);
$ip_collection_status = (int) $_POST['cstatus'];

$user_id = get_current_user_id(); // current user

global $wpdb, $current_user;

//$ip_collections = intval($_POST['ip_collections']);

$current_user = wp_get_current_user();
$ip_collection_author_id = $current_user->ID;

if(!empty($ip_collections_new)) {
	//$ip_collections_new = sanitize_text_field($_POST['ip_collections_new']);
	//$ip_collection_status = intval($_POST['collection_status']);

	$wpdb->query("INSERT INTO " . $wpdb->prefix . "ip_collections (collection_title, collection_status, collection_author_ID) VALUES ('$ip_collections_new', $ip_collection_status, $ip_collection_author_id);");
	$wpdb->query("INSERT INTO " . $wpdb->prefix . "ip_collectionmeta (image_ID, image_collection_ID, image_collection_author_ID) VALUES ($ip_id, $wpdb->insert_id, $ip_collection_author_id);");
	$ipc = $wpdb->insert_id;
} else {
	$wpdb->query("INSERT INTO " . $wpdb->prefix . "ip_collectionmeta (image_ID, image_collection_ID, image_collection_author_ID) VALUES ($ip_id, $ip_collections, $ip_collection_author_id);");
	$ipc = $ip_collections;
}

// add notification
$collection_time = current_time('mysql', true);
$wpdb->query("INSERT INTO " . $wpdb->prefix . "notifications (ID, userID, postID, postKeyID, actionType, actionIcon, actionTime) VALUES (null, $ip_collection_author_id, " . $ip_id . ", " . $ipc . ", 'collected', 'fa-folder', '" . $collection_time . "')");
?>
