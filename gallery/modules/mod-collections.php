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

// Collection Manager view
function ip_collections_display() {
    global $wpdb;

    $result = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "ip_collections WHERE collection_author_ID = '" . get_current_user_id() . "'", ARRAY_A);

    echo '<div class="the ip-collections-container">';
    foreach ($result as $collection) {
        echo '<div class="ip_collections_edit ipc' . $collection['collection_ID'] . '" data-collection-edit="' . $collection['collection_ID'] . '" style="height: auto;">';
            $postslist = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "ip_collectionmeta WHERE image_collection_ID = '" . $collection['collection_ID'] . "' AND image_collection_author_ID = '" . get_current_user_id() . "' LIMIT 1", ARRAY_A);
            $postslistcount = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "ip_collectionmeta WHERE image_collection_ID = '" . $collection['collection_ID'] . "' AND image_collection_author_ID = '" . get_current_user_id() . "'", ARRAY_A);
            echo '<div class="ip_collection_box">';
                foreach ($postslist as $collectable) {
                    echo get_the_post_thumbnail($collectable['image_ID'], 'thumbnail');
                }
            echo '</div>

            <div class="collection_details">
                <h3 class="collection-title" data-collection-id="' . $collection['collection_ID'] . '"><a href="#" class="editCollection" data-collection-id="' . $collection['collection_ID'] . '">' . $collection['collection_title'] . '</a><br><small>(' . count($postslistcount) . ' posters, ' . (($collection['collection_status'] == 0) ? 'private' : 'public') . ' collection)</small></h3>
                <a href="#" class="changeCollection btn btn-primary" data-collection-id="' . $collection['collection_ID'] . '"><i class="fas fa-fw fa-pencil-alt"></i> Edit</a>
            </div>

            <div class="collection_details_edit cde' . $collection['collection_ID'] . '">
                <p><small>Title</small><br><input class="collection-title ct' . $collection['collection_ID'] . '" type="text" data-collection-id="' . $collection['collection_ID'] . '" value="' . $collection['collection_title'] . '"><p>
				<p><small>Visibility</small><br><select class="collection-status cs' . $collection['collection_ID'] . '" data-collection-id="' . $collection['collection_ID'] . '">';
                    $selected = ($collection['collection_status'] == 0) ? 'selected' : '';
                    echo '<option value="1" ' . $selected . '>Public</option>';
                    echo '<option value="0" ' . $selected . '>Private</option>';
                echo '</select></p>';

				$ip_collections_page_id = get_option('ip_collections_page');
    			echo '<p class="ip-collection-clipboard"><small>Share your collection</small><br><input class="collection-details-url" type="url" value="' . home_url('/') . get_the_ip_slug($ip_collections_page_id) . '/' . $collection['collection_ID'] . '" readonly></p>';

				echo '<div style="text-align: center;">
                    <a href="#" class="saveCollection btn btn-primary" data-collection-id="' . $collection['collection_ID'] . '">Save</a>
                    <a href="#" class="closeCollectionEdit btn btn-secondary" data-collection-id="' . $collection['collection_ID'] . '">Cancel</a>
                    <a href="#" class="deleteCollection btn btn-danger" data-collection-id="' . $collection['collection_ID'] . '"><i class="far fa-trash-alt"></i></a>
                </div>
            </div>
        </div>';
	}
	echo '</div><div style="clear:both;"></div>';

	die();
}
function ip_collections_display_public($author_ID) {
    global $wpdb;

    $ip_collections_page_id = get_option('ip_collections_page');

    $result = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "ip_collections WHERE collection_status = 1 AND collection_author_ID = '" . $author_ID . "'", ARRAY_A);

    $out = '<div class="the ip-collections-container">';
        foreach ($result as $collection) {
            $postslist = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "ip_collectionmeta WHERE image_collection_ID = '" . $collection['collection_ID'] . "' AND image_collection_author_ID = '" . $author_ID . "' LIMIT 1", ARRAY_A);
            $postslistcount = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "ip_collectionmeta WHERE image_collection_ID = '" . $collection['collection_ID'] . "' AND image_collection_author_ID = '" . $author_ID . "'", ARRAY_A);

            $collectionTitle = strlen($collection['collection_title']) > 24 ? substr($collection['collection_title'], 0, 24) . '&hellip;' : $collection['collection_title'];

            $out .= '<div class="ip_collections_edit ipc' . $collection['collection_ID'] . '" data-collection-edit="' . $collection['collection_ID'] . '">
                <div class="ip_collection_box">';
                    foreach ($postslist as $collectable) {
                        $out .= '<a href="' . home_url('/') . get_the_ip_slug($ip_collections_page_id) . '/' . $collection['collection_ID'] . '/">' . get_the_post_thumbnail($collectable['image_ID'], 'thumbnail') . '</a>';
                    }
                    $out .= '<div class="ip_collections_overlay"><i class="fas fa-file"></i> ' . count($postslistcount) . '</div>
                </div>

                <div class="collection_details">
                    <h3><a href="' . home_url('/') . get_the_ip_slug($ip_collections_page_id) . '/' . $collection['collection_ID'] . '/">' . $collectionTitle . '</a></h3>
                    <div>By <a href="' . get_author_posts_url($collection['collection_author_ID']) . '">' . get_the_author_meta('nickname', $collection['collection_author_ID']) . '</a></div>
                </div>
            </div>';
        }
    $out .= '</div>
    <div style="clear:both;"></div>';

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

function ip_collections_display_custom() {
	global $wpdb, $current_user;

	$i = 0;

	$result = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "ip_collections WHERE collection_status = 1 ORDER BY RAND()", ARRAY_A);

	$out = '<div class="the ip-collections-container">
        <p style="text-align: center;"><a href="#" class="toggleFrontEndModal btn btn-primary">Create Collection</a></p>

        <div class="frontEndModal">
            <h2>Create new collection</h2>
            <a href="#" class="close toggleFrontEndModal"><i class="fas fa-times"></i></a>

            <input type="hidden" id="collection_author_id" name="collection_author_id" value="' . $current_user->ID . '">
            <p><input type="text" id="collection_title" name="collection_title" placeholder="Collection title"></p>
            <p>
                <label for="collection_status">Make this collection</label> 
                <select id="collection_status" name="id="collection_status"">
                    <option value="1">Public</option>
                    <option value="0">Private</option>
                </select>
            </p>
            <p style=" padding-top: 32px;">
                <input type="submit" value="Create" class="addCollection btn btn-primary">
                <a href="https://posterspy.com/settings/collections-manager/" class="btn btn-secondary">Collections Manager</a>
                <label class="collection-progress"><i class="fas fa-cog fa-spin"></i></label>
                <div class="showme"> <i class="fas fa-check"></i> Collection created. You can now add posters or edit via Collections Manager.</div>
            </p>
        </div>';

        foreach ($result as $collection) {
            $postslistcount = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "ip_collectionmeta WHERE image_collection_ID = '" . $collection['collection_ID'] . "'", ARRAY_A);

            if (count($postslistcount) >= 1 && (int) $i < (int) 400) {
                $postslist = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "ip_collectionmeta WHERE image_collection_ID = '" . $collection['collection_ID'] . "' LIMIT 1", ARRAY_A);
                $ip_collections_page_id = get_option('ip_collections_page');

                $collectionTitle = strlen($collection['collection_title']) > 24 ? substr($collection['collection_title'], 0, 24) . '&hellip;' : $collection['collection_title'];

                $out .= '<div class="ip_collections_edit ipc' . $collection['collection_ID'] . '" data-collection-edit="' . $collection['collection_ID'] . '">
                    <div class="ip_collection_box">';
                        foreach ($postslist as $collectable) {
                            $out .= '<a href="' . home_url('/') . get_the_ip_slug($ip_collections_page_id) . '/' . $collection['collection_ID'] . '/">' . get_the_post_thumbnail($collectable['image_ID'], 'thumbnail') . '</a>';
                        }
                        $out .= '<div class="ip_collections_overlay"><i class="fas fa-file"></i> ' . count($postslistcount) . '</div>
                    </div>
                    <div class="collection_details">
                        <h3><a href="' . home_url('/') . get_the_ip_slug($ip_collections_page_id) . '/' . $collection['collection_ID'] . '/">' . $collectionTitle . '</a></h3>
                        <div>By <a href="' . get_author_posts_url($collection['collection_author_ID']) . '">' . get_the_author_meta('nickname', $collection['collection_author_ID']) . '</a></div>
                    </div>
                </div>';
            }
            ++$i;
        }
    $out .= '</div>
    <div style="clear:both;"></div>';

    return $out;
}



// FRONT END BUTTON
function ip_frontend_add_collection($ip_id) {
    global $wpdb, $current_user;

    if (isset($_POST['collectme'])) {
        $ip_collections = (int) $_POST['ip_collections'];

        $current_user = wp_get_current_user();
        $ip_collection_author_id = $current_user->ID;

        if (!empty($_POST['ip_collections_new'])) {
            $ip_collections_new = sanitize_text_field($_POST['ip_collections_new']);
            $ip_collection_status = (int) $_POST['collection_status'];

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

    if (is_user_logged_in()) { ?>
        <a href="#" class="toggleFrontEndModal toggleFrontEndModalButton btn btn-primary"><i class="fas fa-plus"></i> Collect</a> <?php if (isset($_POST['collectme'])) { echo ' <i class="fas fa-check"></i>'; } ?>

        <div class="frontEndModal">
            <h2>Add to collection</h2>
            <a href="#" class="close toggleFrontEndModal"><i class="fas fa-times"></i></a>

            <form method="post" class="imagepress-form">
                <input type="hidden" id="collection_author_id" name="collection_author_id" value="<?php echo $current_user->ID; ?>">

                <p>
                    <?php
                    $result = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "ip_collections WHERE collection_author_ID = '" . get_current_user_id() . "'", ARRAY_A);

                    echo '<select name="ip_collections" id="ip_collections">
						<option value="">Choose a collection...</option>';
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
				<p><input type="text" name="ip_collections_new" id="ip_collections_new" placeholder="Create new collection..."></p>
				<p><label>Make this collection</label> <select id="collection_status" name="collection_status"><option value="1">Public</option><option value="0">Private</option></select> <label></label></p>
				<p>
					<input type="submit" name="collectme" class="imagepress-collect" value="Add" data-post-id="<?php echo $ip_id; ?>">
					<label class="collection-progress"><i class="fas fa-cog fa-spin"></i></label>
					<label class="showme"> <i class="fas fa-check"></i> Poster added to collection!</label>
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
					echo '<div class="ip-featured-collection-meta"><a href="' . home_url('/') . get_the_ip_slug($ip_collections_page_id) . '/' . $which['collection_ID'] . '/">' . $which['collection_title'] . '</a><br><small>by <a href="' . get_author_posts_url($which['collection_author_ID']) . '">' . get_the_author_meta('nickname', $which['collection_author_ID']) . '</a></small></div>';
					echo '<div class="ip_clear"></div>';
				echo '</div>';
			}
		}
		?>

	</div>
	<?php
}
