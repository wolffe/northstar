<?php
/*
 * ImagePress Module: Collections
 */

function addCollection() {
	global $wpdb;

	$collection_author_ID = intval($_POST['collection_author_id']);
	$collection_title = sanitize_text_field($_POST['collection_title']);
	$collection_title_slug = sanitize_title($_POST['collection_title']);
	$collection_status = intval($_POST['collection_status']);

	$wpdb->query($wpdb->prepare("INSERT INTO " . $wpdb->prefix . "ip_collections (collection_title, collection_title_slug, collection_status, collection_author_ID) VALUES ('%s', '%s', %d, %d)", $collection_title, $collection_title_slug, $collection_status, $collection_author_ID));
	die();
}
function editCollectionTitle() {
	global $wpdb;

	$collection_ID = intval($_POST['collection_id']);
	$collection_title = sanitize_text_field($_POST['collection_title']);

	$wpdb->query($wpdb->prepare("UPDATE " . $wpdb->prefix . "ip_collections SET collection_title = '%s' WHERE collection_ID = %d", $collection_title, $collection_ID));

	die();
}
function editCollectionStatus() {
	global $wpdb;

	$collection_ID = intval($_POST['collection_id']);
	$collection_status = intval($_POST['collection_status']);

	$wpdb->query($wpdb->prepare("UPDATE " . $wpdb->prefix . "ip_collections SET collection_status = '%s' WHERE collection_ID = %d", $collection_status, $collection_ID));
	die();
}
function deleteCollection() {
	global $wpdb;

	$collection_ID = intval($_POST['collection_id']);

	$wpdb->query($wpdb->prepare("DELETE FROM " . $wpdb->prefix . "ip_collections WHERE collection_ID = %d", $collection_ID));
	$wpdb->query($wpdb->prepare("DELETE FROM " . $wpdb->prefix . "ip_collectionmeta WHERE image_collection_ID = %d", $collection_ID));
	die();
}
function deleteCollectionImage() {
	global $wpdb;

	$image_ID = intval($_POST['image_id']);

	$wpdb->query($wpdb->prepare("DELETE FROM " . $wpdb->prefix . "ip_collectionmeta WHERE image_ID = %d", $image_ID));
	die();
}

add_action('wp_ajax_addCollection', 'addCollection');
add_action('wp_ajax_editCollectionTitle', 'editCollectionTitle');
add_action('wp_ajax_editCollectionStatus', 'editCollectionStatus');
add_action('wp_ajax_deleteCollection', 'deleteCollection');
add_action('wp_ajax_deleteCollectionImage', 'deleteCollectionImage');

add_action('wp_ajax_ip_collection_display', 'ip_collection_display');
add_action('wp_ajax_ip_collections_display', 'ip_collections_display');

function ip_collection_display() {
	$collection_ID = intval($_POST['collection_id']);

	$result = do_shortcode('[imagepress-show collection="1" collection_id="' . $collection_ID . '"]');

	echo $result;

	die();
}

function ip_collections_display() {
	global $wpdb;

	$result = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "ip_collections WHERE collection_author_ID = '" . get_current_user_id() . "'", ARRAY_A);

	echo '<div class="the">';
	foreach($result as $collection) {
		echo '<div class="ip_collections_edit ipc' . $collection['collection_ID'] . '" data-collection-edit="' . $collection['collection_ID'] . '">';
			$postslist = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "ip_collectionmeta WHERE image_collection_ID = '" . $collection['collection_ID'] . "' AND image_collection_author_ID = '" . get_current_user_id() . "' LIMIT 4", ARRAY_A);
            $postslistcount = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "ip_collectionmeta WHERE image_collection_ID = '" . $collection['collection_ID'] . "' AND image_collection_author_ID = '" . get_current_user_id() . "'", ARRAY_A);
			echo '<div class="ip_collection_box">';
				foreach($postslist as $collectable) {
					echo get_the_post_thumbnail($collectable['image_ID'], 'thumbnail');
				}
			echo '</div>';

			echo '<div class="ip_collections_overlay">' . (($collection['collection_status'] == 0) ? '<i class="fa fa-fw fa-eye-slash"></i> ' : '') . '<i class="fa fa-fw fa-file"></i> ' . count($postslistcount) . '</div>';

			echo '<div class="collection_details">';
    			echo '<h3 class="collection-title" data-collection-id="' . $collection['collection_ID'] . '"><a href="#" class="editCollection" data-collection-id="' . $collection['collection_ID'] . '">' . $collection['collection_title'] . '</a></h3>';

				echo '<div><a href="#" class="changeCollection btn btn-primary" data-collection-id="' . $collection['collection_ID'] . '"><i class="fa fa-pencil"></i> ' . __('Edit', 'imagepress') . '</a></div>';
            echo '</div>';

			echo '<div class="collection_details_edit cde' . $collection['collection_ID'] . '">
				<h3>' . __('Edit collection', 'imagepress') . '</h3>
				<p><label>' . __('Title', 'imagepress') . '</label><br><input class="collection-title ct' . $collection['collection_ID'] . '" type="text" data-collection-id="' . $collection['collection_ID'] . '" value="' . $collection['collection_title'] . '"><p>
				<p><label>' . __('Visibility', 'imagepress') . '</label><br><select class="collection-status cs' . $collection['collection_ID'] . '" data-collection-id="' . $collection['collection_ID'] . '">';
                    $selected = ($collection['collection_status'] == 0) ? 'selected' : '';
                    echo '<option value="1" ' . $selected . '>' . __('Public', 'imagepress') . '</option>';
                    echo '<option value="0" ' . $selected . '>' . __('Private', 'imagepress') . '</option>';
                echo '</select></p>';

				$ip_collections_page_id = get_option('ip_collections_page');
    			echo '<p><label>' . __('Share your collection', 'imagepress') . '</label><br><input type="url" value="' . home_url('/') . get_the_ip_slug($ip_collections_page_id) . '/' . $collection['collection_ID'] . '" readonly></p>';

				echo '<a href="#" class="saveCollection btn btn-primary" data-collection-id="' . $collection['collection_ID'] . '"><i class="fa fa-check"></i> ' . __('Save', 'imagepress') . '</a>';
				echo '<a href="#" class="closeCollectionEdit btn btn-primary" data-collection-id="' . $collection['collection_ID'] . '"><i class="fa fa-times"></i> ' . __('Cancel', 'imagepress') . '</a>';
				echo '<a href="#" class="deleteCollection button" data-collection-id="' . $collection['collection_ID'] . '"><i class="fa fa-trash"></i></a>';
			echo '</div>';
		echo '</div>';
	}
	echo '</div><div style="clear:both;"></div>';

	die();
}
function ip_collections_display_public($author_ID) {
	global $wpdb;

	$result = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "ip_collections WHERE collection_status = 1 AND collection_author_ID = '" . $author_ID . "'", ARRAY_A);

	$out = '<div class="the">';
	foreach($result as $collection) {
		$out .= '<div class="ip_collections_edit ipc' . $collection['collection_ID'] . '" data-collection-edit="' . $collection['collection_ID'] . '">';
			$postslist = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "ip_collectionmeta WHERE image_collection_ID = '" . $collection['collection_ID'] . "' AND image_collection_author_ID = '" . $author_ID . "' LIMIT 4", ARRAY_A);
            $postslistcount = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "ip_collectionmeta WHERE image_collection_ID = '" . $collection['collection_ID'] . "' AND image_collection_author_ID = '" . $author_ID . "'", ARRAY_A);
			$out .= '<div class="ip_collection_box">';
				foreach($postslist as $collectable) {
					$out .= get_the_post_thumbnail($collectable['image_ID'], 'imagepress_pt_std');
				}
			$out .= '</div>';

			$out .= '<div class="ip_collections_overlay"><i class="fa fa-file"></i> ' . count($postslistcount) . '</div>';

			$out .= '<div class="collection_details">';
				$ip_collections_page_id = get_option('ip_collections_page');
    			$out .= '<h3><a href="' . home_url('/') . get_the_ip_slug($ip_collections_page_id) . '/' . $collection['collection_ID'] . '/">' . $collection['collection_title'] . '</a></h3>';
                $out .= '<div>' . __('By', 'imagepress') . ' <a href="' . get_author_posts_url($collection['collection_author_ID']) . '">' . get_the_author_meta('nickname', $collection['collection_author_ID']) . '</a></div>';
            $out .= '</div>';
		$out .= '</div>';
	}
	$out .= '</div><div style="clear:both;"></div>';

	return $out;
}

function ipCollectionCount($author_ID) {
	global $wpdb;

	$result = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "ip_collections WHERE collection_status = 1 AND collection_author_ID = '" . $author_ID . "'", ARRAY_A);

    if (!is_array($result)) {
        $result = [];
    }

    $count = count($result);

    return $count;
}

function ip_collections_display_custom($atts) {
	extract(shortcode_atts([
		'mode' => 'random', // random, latest
        'count' => 4
	], $atts));

	global $wpdb;
	$i = 0;

	if($mode == 'random')
		$mode = 'RAND()';

	$result = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "ip_collections WHERE collection_status = 1 ORDER BY $mode", ARRAY_A);

	$out = '<div class="the">';
	foreach($result as $collection) {
		$postslistcount = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "ip_collectionmeta WHERE image_collection_ID = '" . $collection['collection_ID'] . "'", ARRAY_A);

		if(count($postslistcount) >= 4) {
			if($i < $count) {
				$out .= '<div class="ip_collections_edit ipc' . $collection['collection_ID'] . '" data-collection-edit="' . $collection['collection_ID'] . '">';
					$postslist = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "ip_collectionmeta WHERE image_collection_ID = '" . $collection['collection_ID'] . "' LIMIT 4", ARRAY_A);

					$out .= '<div class="ip_collection_box">';
						foreach($postslist as $collectable) {
							$out .= get_the_post_thumbnail($collectable['image_ID'], 'imagepress_pt_std');
						}
					$out .= '</div>';

					$out .= '<div class="ip_collections_overlay"><i class="fa fa-file"></i> ' . count($postslistcount) . '</div>';

					$out .= '<div class="collection_details">';
						$ip_collections_page_id = get_option('ip_collections_page');
						$out .= '<h3><a href="' . home_url('/') . get_the_ip_slug($ip_collections_page_id) . '/' . $collection['collection_ID'] . '/">' . $collection['collection_title'] . '</a></h3>';
						$out .= '<div>' . __('By', 'imagepress') . ' <a href="' . get_author_posts_url($collection['collection_author_ID']) . '">' . get_the_author_meta('nickname', $collection['collection_author_ID']) . '</a></div>';
					$out .= '</div>';
				$out .= '</div>';
			}
			++$i;
		}
	}
	$out .= '</div><div style="clear:both;"></div>';

	return $out;
}



// FRONT END BUTTON
function ip_frontend_add_collection($ip_id) {
	if(isset($_POST['collectme'])) {
		global $wpdb, $current_user;

		$ip_collections = intval($_POST['ip_collections']);

		$current_user = wp_get_current_user();
		$ip_collection_author_id = $current_user->ID;

		if(!empty($_POST['ip_collections_new'])) {
			$ip_collections_new = sanitize_text_field($_POST['ip_collections_new']);
			$ip_collection_status = intval($_POST['collection_status']);

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
	}
	if(is_user_logged_in()) {
		?>
		<a href="#" class="toggleFrontEndModal toggleFrontEndModalButton btn btn-primary"><i class="fa fa-plus"></i> <?php echo __('Collect', 'imagepress'); ?></a> <?php if(isset($_POST['collectme'])) { echo ' <i class="fa fa-check"></i>'; } ?>

		<div class="frontEndModal">
            <h2><?php echo __('Add to collection', 'imagepress'); ?></h2>
			<a href="#" class="close toggleFrontEndModal"><i class="fa fa-times"></i> <?php echo __('Close', 'imagepress'); ?></a>

			<form method="post" class="imagepress-form">
				<input type="hidden" id="collection_author_id" name="collection_author_id" value="<?php echo $current_user->ID; ?>">

				<p>
					<?php
					global $wpdb;

					$result = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "ip_collections WHERE collection_author_ID = '" . get_current_user_id() . "'", ARRAY_A);

					echo '<select name="ip_collections" id="ip_collections">
						<option value="">' . __('Choose a collection...', 'imagepress') . '</option>';
						foreach($result as $collection) {
							$disabled = $wpdb->get_row("SELECT * FROM " . $wpdb->prefix . "ip_collectionmeta WHERE image_ID = '" . get_the_ID() . "' AND image_collection_ID = '" . $collection['collection_ID'] . "'", ARRAY_A);

							echo '<option value="' . $collection['collection_ID'] . '"';
							if(count($disabled) > 0)
								echo ' disabled';
							echo '>' . $collection['collection_title'];
							echo '</option>';
						}
					echo '</select>';
					?>
				</p>
				<p>or</p>
				<p><input type="text" name="ip_collections_new" id="ip_collections_new" placeholder="<?php echo __('Create new collection...', 'imagepress'); ?>"></p>
				<p><label><?php echo __('Make this collection', 'imagepress'); ?></label> <select id="collection_status" name="collection_status"><option value="1"><?php echo __('Public', 'imagepress'); ?></option><option value="0"><?php echo __('Private', 'imagepress'); ?></option></select> <label></label></p>
				<p>
					<input type="submit" name="collectme" class="imagepress-collect" value="Add" data-post-id="<?php echo $ip_id; ?>">
					<label class="collection-progress"><i class="fa fa-cog fa-spin"></i></label>
					<label class="showme"> <i class="fa fa-check"></i> <?php echo __('Poster added to collection!', 'imagepress'); ?></label>
				</p>
			</form>
		</div>
		<?php
	}
}

function ip_frontend_view_image_collection($ip_id) {
	?>
	<div class="textwidget">
		<?php
		global $wpdb;
		$ip_collections_page_id = get_option('ip_collections_page');

		$result = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "ip_collectionmeta WHERE image_ID = '" . $ip_id . "'", ARRAY_A);

		foreach($result as $collection) {
			$which = $wpdb->get_row("SELECT * FROM " . $wpdb->prefix . "ip_collections WHERE collection_status = 1 AND collection_ID = '" . $collection['image_collection_ID'] . "'", ARRAY_A);
			if(!empty($which['collection_title'])) {
				$featured = $wpdb->get_row("SELECT image_ID FROM " . $wpdb->prefix . "ip_collectionmeta WHERE image_collection_ID = '" . $collection['image_collection_ID'] . "' ORDER BY RAND()", ARRAY_A);
				echo '<div class="ip-featured-collection">';
					echo get_the_post_thumbnail($featured['image_ID'], 'thumbnail');
					echo '<div class="ip-featured-collection-meta"><a href="' . home_url('/') . get_the_ip_slug($ip_collections_page_id) . '/' . $which['collection_ID'] . '/">' . $which['collection_title'] . '</a><br><small>' . __('by', 'imagepress') . ' <a href="' . get_author_posts_url($which['collection_author_ID']) . '">' . get_the_author_meta('nickname', $which['collection_author_ID']) . '</a></small></div>';
					echo '<div class="ip_clear"></div>';
				echo '</div>';
			}
		}
		?>

	</div>
	<?php
}
