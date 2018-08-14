<?php
/*
Plugin Name: ImagePress
Plugin URI: https://getbutterfly.com/wordpress-plugins/imagepress/
Description: Create a user-powered image gallery or an image upload site, using nothing but WordPress custom posts. Moderate image submissions and integrate the plugin into any theme.
Version: 6.4.0
License: GPLv3
Author: Ciprian Popescu
Author URI: https://getbutterfly.com/
Text Domain: imagepress

Copyright 2013-2018 Ciprian Popescu (email: getbutterfly@gmail.com)

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program. If not, see <http://www.gnu.org/licenses/>.
*/

define('IP_PLUGIN_URL', WP_PLUGIN_URL . '/' . dirname(plugin_basename(__FILE__)));
define('IP_PLUGIN_PATH', WP_PLUGIN_DIR . '/' . dirname(plugin_basename(__FILE__)));
define('IP_PLUGIN_FILE_PATH', WP_PLUGIN_DIR . '/' . plugin_basename(__FILE__));
define('IP_PLUGIN_VERSION', '6.4.0');

include IP_PLUGIN_PATH . '/includes/functions.php';
include IP_PLUGIN_PATH . '/includes/page-settings.php';
include IP_PLUGIN_PATH . '/includes/cinnamon-users.php';

include IP_PLUGIN_PATH . '/classes/class-frontend.php';

// user modules
include IP_PLUGIN_PATH . '/modules/mod-awards.php';
include IP_PLUGIN_PATH . '/modules/mod-user-following.php';
include IP_PLUGIN_PATH . '/modules/mod-likes.php';
include IP_PLUGIN_PATH . '/modules/mod-notifications.php';
include IP_PLUGIN_PATH . '/modules/mod-collections.php';
include IP_PLUGIN_PATH . '/modules/mod-feed.php';

include IP_PLUGIN_PATH . '/modules/mod-pm.php';
//

add_action('init', 'imagepress_registration');

add_action('wp_ajax_nopriv_post-like', 'post_like');
add_action('wp_ajax_post-like', 'post_like');

add_action('admin_menu', 'imagepress_menu'); // settings menu
add_action('admin_menu', 'imagepress_menu_bubble');

add_filter('transition_post_status', 'imagepress_notify_status', 10, 3); // email notifications
add_filter('widget_text', 'do_shortcode');

function imagepress_menu() {
    add_submenu_page('edit.php?post_type=poster', 'ImagePress Settings', 'ImagePress Settings', 'manage_options', 'imagepress_admin_page', 'imagepress_admin_page');
}

add_shortcode('imagepress-add', 'imagepress_add');
add_shortcode('imagepress-add-beta', 'imagepress_add_beta');
add_shortcode('imagepress-show', 'imagepress_show');
add_shortcode('imagepress-search', 'imagepress_search');
add_shortcode('imagepress-top', 'imagepress_top');

add_shortcode('imagepress', 'imagepress_widget');

add_shortcode('imagepress-collections', 'ip_collections_display_custom');

add_image_size('imagepress_feed', 800); // PosterSpy feed image size

/*
 * New image size (4 posters per row)
 *
 * @since 6.2.0
 */
add_image_size('imagepress_pt_lrg', 480);



// show admin bar only for admins
add_action('after_setup_theme', 'cinnamon_remove_admin_bar');
function cinnamon_remove_admin_bar() {
	if (!current_user_can('administrator') && !is_admin()) {
		show_admin_bar(false);
	}
}
//

/* CINNAMON ACTIONS */
add_action('init', 'cinnamon_author_base');

add_action('personal_options_update', 'save_cinnamon_profile_fields');
add_action('edit_user_profile_update', 'save_cinnamon_profile_fields');

/* CINNAMON SHORTCODES */
add_shortcode('cinnamon-card', 'cinnamon_card');
add_shortcode('cinnamon-profile', 'cinnamon_profile');
add_shortcode('cinnamon-profile-blank', 'cinnamon_profile_blank');
add_shortcode('cinnamon-profile-edit', 'cinnamon_profile_edit');
add_shortcode('cinnamon-awards', 'cinnamon_awards');

/* CINNAMON FILTERS */
add_filter('get_avatar', 'hub_gravatar_filter', 10, 5);
add_filter('user_contactmethods', 'cinnamon_extra_contact_info');

add_shortcode('cinnamon-settings', 'cinnamon_settings');






// custom thumbnail column
add_filter('manage_edit-poster_columns', 'ip_columns_filter', 10, 1);
function ip_columns_filter($columns) {
	$column_thumbnail = [
		'thumbnail' => 'Thumbnail'
	];
	$columns = array_slice($columns, 0, 1, true) + $column_thumbnail + array_slice($columns, 1, NULL, true);

	return $columns;
}
add_action('manage_posts_custom_column', 'ip_column_action', 10, 1);
function ip_column_action($column) {
	global $post;
	switch ($column) {
		case 'thumbnail':
			echo get_the_post_thumbnail($post->ID, 'thumbnail');
		break;
	}
}
//

function ip_manage_users_custom_column($output = '', $column_name, $user_id) {
	global $wpdb;

	if ((string) $column_name !== 'post_type_count')
		return;

	$where = get_posts_by_author_sql('poster', true, $user_id);
	$result = $wpdb->get_var("SELECT COUNT(*) FROM $wpdb->posts $where");

	return '<a href="' . admin_url("edit.php?post_type=poster&author=$user_id") . '">' . $result . '</a>';
}
add_filter('manage_users_custom_column', 'ip_manage_users_custom_column', 10, 3);

function ip_manage_users_columns($columns) {
	$columns['post_type_count'] = 'Images';

	return $columns;
}
add_filter('manage_users_columns', 'ip_manage_users_columns');

// Main upload function
function imagepress_add($atts, $content = null) {
	extract(shortcode_atts([
		'category' => ''
	], $atts));

	global $current_user;
	$out = '';

	if (isset($_POST['imagepress_upload_image_form_submitted']) && wp_verify_nonce($_POST['imagepress_upload_image_form_submitted'], 'imagepress_upload_image_form')) {
		$ip_status = 'publish';

		if ((int) get_option('ip_moderate') === 0) {
			$ip_status = 'pending';
		}

		$ip_image_author = $current_user->ID;

		if (!empty($_POST['imagepress_image_caption'])) {
			$imagepress_image_caption = sanitize_text_field($_POST['imagepress_image_caption']);
		} else {
			$imagepress_image_caption = 'ImagePress Image' . uniqid();
		}

		$user_image_data = [
			'post_title' => $imagepress_image_caption,
			'post_content' => sanitize_text_field($_POST['imagepress_image_description']),
			'post_status' => $ip_status,
			'post_author' => $ip_image_author,
			'post_type' => 'poster'
		];

		// send notification email to administrator
		$ip_notification_email = get_option('ip_notification_email');
		$ip_notification_subject = 'New image uploaded! | ' . get_bloginfo('name');
		$ip_notification_message = 'New image uploaded! | ' . get_bloginfo('name');

		if ($post_id = wp_insert_post($user_image_data)) {
            if (!empty($_POST['imagepress_dropbox_file'])) {
                require_once ABSPATH . 'wp-admin' . '/includes/image.php';
                require_once ABSPATH . 'wp-admin' . '/includes/file.php';
                require_once ABSPATH . 'wp-admin' . '/includes/media.php';

                $element = media_sideload_image($_POST['imagepress_dropbox_file'], $post_id);
                $attachments = get_posts(
                    [
                        'post_type' => 'attachment',
                        'numberposts' => 1,
                        'order' => 'ASC',
                        'post_parent' => $post_id
                    ]
                );
                $attachment = $attachments[0];
                set_post_thumbnail($post_id, $attachment->ID);
            } else {
                imagepress_process_image('imagepress_image_file', $post_id, $imagepress_image_caption);
            }

			// multiple images
			if ((int) get_option('ip_upload_secondary') === 1) {
				$files = $_FILES['imagepress_image_additional'];
				if ($files) { 
					foreach ($files['name'] as $key => $value) {
						if ($files['name'][$key]) {
							$file = [
								'name' => $files['name'][$key],
								'type' => $files['type'][$key],
								'tmp_name' => $files['tmp_name'][$key],
								'error' => $files['error'][$key],
								'size' => $files['size'][$key]
							];
						}
						$_FILES = ['attachment' => $file];
						foreach ($_FILES as $file => $array) {
							$attach_id = media_handle_upload($file, $post_id);
							if ($attach_id < 0) {
								$post_error = true;
							}
						}
					}
				}
			}
			// end multiple images

            if (!empty($_POST['imagepress_image_category'])) {
                wp_set_object_terms($post_id, (int)$_POST['imagepress_image_category'], 'imagepress_image_category');
            }

			// always moderate this category
			$ip_cat_moderation_include = get_option('ip_cat_moderation_include');
			if (!empty($ip_cat_moderation_include)) {
				if ($_POST['imagepress_image_category'] == $ip_cat_moderation_include) {
					$ip_post = [];
					$ip_post['ID'] = $post_id;
					$ip_post['post_status'] = 'pending';
					wp_update_post($ip_post);
				}
			}
			//

            if (!empty($_POST['imagepress_image_keywords'])) {
                $keywords = explode(',', $_POST['imagepress_image_keywords']);
                wp_set_post_terms($post_id, $keywords, 'imagepress_image_keyword', false);
            }

			if (isset($_POST['imagepress_purchase']))
				add_post_meta($post_id, 'imagepress_purchase', $_POST['imagepress_purchase'], true);
			else
				add_post_meta($post_id, 'imagepress_purchase', '', true);

			if (isset($_POST['imagepress_video']))
				add_post_meta($post_id, 'imagepress_video', $_POST['imagepress_video'], true);
			else
				add_post_meta($post_id, 'imagepress_video', '', true);

			if (isset($_POST['imagepress_sticky']))
				add_post_meta($post_id, 'imagepress_sticky', 1, true);
			else
				add_post_meta($post_id, 'imagepress_sticky', 0, true);

            /**
			if (isset($_POST['imagepress_image_wrb']))
				add_post_meta($post_id, 'imagepress_image_wrb', $_POST['imagepress_image_wrb'], true);
			else
				add_post_meta($post_id, 'imagepress_image_wrb', '', true);
            /**/

			imagepress_post_add_custom($post_id, $ip_image_author);

			$headers[] = "MIME-Version: 1.0\r\n";
			$headers[] = "Content-Type: text/html; charset=\"" . get_option('blog_charset') . "\"\r\n";
			wp_mail($ip_notification_email, $ip_notification_subject, $ip_notification_message, $headers);
		}

		$out .= '<p class="message noir-success">' . get_option('ip_upload_success_title') . '</p>';
		$out .= '<p class="message noir-default"><a href="' . get_permalink($post_id) . '">' . get_option('ip_upload_success') . '</a></p>';

		// hook_upload_success
		$hook = get_option('hook_upload_success');
		$hook = str_replace('#url#', get_permalink($post_id), $hook);
		$out .= do_shortcode($hook);
	}  

	if ((int) get_option('ip_registration') === 0 && !is_user_logged_in()) {
		$out .= '<p>You need to be logged in to upload an image.</p>';
	}
	if (((int) get_option('ip_registration') === 0 && is_user_logged_in()) || (int) get_option('ip_registration') === 1) {
		if (isset($_POST['imagepress_image_caption']) && isset($_POST['imagepress_image_category']))
			$out .= imagepress_get_upload_image_form($imagepress_image_caption = $_POST['imagepress_image_caption'], $imagepress_image_category = $_POST['imagepress_image_category'], $imagepress_image_description = $_POST['imagepress_image_description'], $category);
		else
			$out .= imagepress_get_upload_image_form($imagepress_image_caption = '', $imagepress_image_category = '', $imagepress_image_description = '', $category);
	}

	return $out;
}






// Main upload function
function imagepress_add_beta($atts, $content = null) {
	extract(shortcode_atts([
		'category' => ''
	], $atts));

	global $current_user;
	$out = '';

	if ((int) get_option('ip_registration') === 0 && !is_user_logged_in()) {
		$out .= '<p>You need to be logged in to upload an image.</p>';
	}

    $out .= '<div class="ip-block-uploader">
        <div class="ip-block-uploader-column">
            <div class="ip-block-uploader-element">
                <label for="">Poster Title</label>
                <input type="text" name="ip_block_title" id="ip-block-title">
            </div>
            <div class="ip-block-uploader-element">
                <label for="">Poster Description</label>
                <textarea name="ip_block_description" id="ip-block-description" rows="8"></textarea>
            </div>
            <div class="ip-block-uploader-element">
                <label for="">Keywords</label>
                <input type="text" name="ip_block_keywords" id="ip-block-keywords" placeholder="Poster keywords (optional, separate with a comma)">
            </div>
            <div class="ip-block-uploader-element">
                <label for="">Category</label>
                ' . imagepress_get_image_categories_dropdown('imagepress_image_category', '') . '
            </div>
            <div class="ip-block-uploader-element">
                <label for="">Video URL</label>
                <input type="url" name="ip_block_video_url" id="ip-block-video-url" placeholder="Video/GIF link (YouTube/Vimeo/Giphy) (optional)">
            </div>
            <div class="ip-block-uploader-element">
                <label for="">Purchase Link</label>
                <input type="url" name="ip_block_purchase_link" id="ip-block-purchase-link" placeholder="Purchase link for this project (optional)">
            </div>
        </div>
        <div class="ip-block-uploader-column">
            <div class="ip-block-uploader-element">
                <label>Click to add elements to your project.<br>Drag and drop to reorder them.</label>
            </div>
            <div class="ip-block-uploader-types">
                <a href="#" id="ip-block-image"><i class="fas fa-fw fa-file-image"></i> Add Image</a>
                <a href="#" id="ip-block-heading"><i class="fas fa-fw fa-heading"></i> Add Heading</a>
                <a href="#" id="ip-block-paragraph"><i class="fas fa-fw fa-paragraph"></i> Add Paragraph</a>
                <a href="#" id="ip-block-caption"><i class="far fa-fw fa-file-alt"></i> Add Caption</a>
            </div>
            <form method="post" name="form-blocks" id="form-blocks">
                <ul id="ip-blocks" class="ip-block-container">
                </ul>

                <p style="text-align: right;"><a href="#" name="form_block_submit" id="yay" class="btn btn-primary">Create Project</a></p>
                <div id="block-status"></div>
            </form>
        </div>
    </div>';
    
	return $out;
}

add_action('wp_ajax_ip_project_save', 'ip_project_save');
add_action('wp_ajax_ip_project_edit', 'ip_project_edit');
add_action('wp_ajax_ip_project_delete', 'ip_project_delete');
add_action('wp_ajax_ip_project_save_image', 'ip_project_save_image');
add_action('wp_ajax_ip_project_show_image', 'ip_project_show_image');
add_action('wp_ajax_ip_project_delete_image', 'ip_project_delete_image');

function ip_project_save() {
    global $current_user;

    // Initialise variables
    $blockTitle = trim($_POST['blockTitle']);
    $blockDescription = $_POST['blockDescription'];
    $blockCategory = (int) $_POST['blockCategory'];
    $blockKeywords = $_POST['blockKeywords'];
    $blockVideoUri = $_POST['blockVideoUri'];
    $blockPurchaseUri = $_POST['blockPurchaseUri'];
    $blockContent = $_POST['blockContent'];
    $blockThumbnail = $_POST['blockThumbnail'];

    $ip_status = 'publish';

    if ((int) get_option('ip_moderate') === 0) {
        $ip_status = 'pending';
    }
    $ip_status = 'pending';

    $ip_image_author = $current_user->ID;

    $user_image_data = [
        'post_title' => $blockTitle,
        'post_content' => $blockDescription,
        'post_status' => $ip_status,
        'post_author' => $ip_image_author,
        'post_type' => 'poster'
    ];

    // send notification email to administrator
    $ip_notification_email = get_option('ip_notification_email');
    $ip_notification_subject = 'New image uploaded! | ' . get_bloginfo('name');
    $ip_notification_message = 'New image uploaded! | ' . get_bloginfo('name');

    if ($post_id = wp_insert_post($user_image_data)) {
        wp_set_object_terms($post_id, $blockCategory, 'imagepress_image_category');

        // always moderate this category
        $ip_cat_moderation_include = get_option('ip_cat_moderation_include');
        if (!empty($ip_cat_moderation_include)) {
            if ($_POST['imagepress_image_category'] == $ip_cat_moderation_include) {
                $ip_post = [];
                $ip_post['ID'] = $post_id;
                $ip_post['post_status'] = 'pending';
                wp_update_post($ip_post);
            }
        }
        //

        if (!empty($blockKeywords)) {
            $keywords = explode(',', $blockKeywords);
            wp_set_post_terms($post_id, $keywords, 'imagepress_image_keyword', false);
        }

        if (isset($blockPurchaseUri)) {
            add_post_meta($post_id, 'imagepress_purchase', $blockPurchaseUri, true);
        }

        if (isset($blockVideoUri)) {
            add_post_meta($post_id, 'imagepress_video', $blockVideoUri, true);
        }

        if (isset($blockContent)) {
            add_post_meta($post_id, 'imagepress_project', $blockContent, true);
        }

        if (isset($_POST['imagepress_sticky'])) {
            add_post_meta($post_id, 'imagepress_sticky', 1, true);
        } else {
            add_post_meta($post_id, 'imagepress_sticky', 0, true);
        }

        /**
        if (isset($_POST['imagepress_image_wrb']))
            add_post_meta($post_id, 'imagepress_image_wrb', $_POST['imagepress_image_wrb'], true);
        else
            add_post_meta($post_id, 'imagepress_image_wrb', '', true);
        /**/

        set_post_thumbnail($post_id, $blockThumbnail);

        imagepress_post_add_custom($post_id, $ip_image_author);

        $headers[] = "MIME-Version: 1.0\r\n";
        $headers[] = "Content-Type: text/html; charset=\"" . get_option('blog_charset') . "\"\r\n";
        wp_mail($ip_notification_email, $ip_notification_subject, $ip_notification_message, $headers);

        //echo get_permalink($post_id);
    }

    exit;
}

function ip_project_edit() {
    global $current_user;

    // Initialise variables
    $blockTitle = trim($_POST['blockTitle']);
    $blockDescription = $_POST['blockDescription'];
    $blockCategory = (int) $_POST['blockCategory'];
    $blockKeywords = $_POST['blockKeywords'];
    $blockVideoUri = $_POST['blockVideoUri'];
    $blockPurchaseUri = $_POST['blockPurchaseUri'];
    $blockContent = $_POST['blockContent'];
    $blockThumbnail = (int) $_POST['blockThumbnail'];
    $projectId = (int) $_POST['projectId'];

    /**
    $ip_status = 'publish';

    if ((int) get_option('ip_moderate') === 0) {
        $ip_status = 'pending';
    }
    $ip_status = 'pending';

    $ip_image_author = $current_user->ID;

    $user_image_data = [
        'post_title' => $blockTitle,
        'post_content' => $blockDescription,
        'post_status' => $ip_status,
        'post_author' => $ip_image_author,
        'post_type' => 'poster'
    ];
    /**/

    $post = [
        'ID' => (int) $projectId,
        'post_content' => $blockDescription,
        'post_title' => $blockTitle
    ];
    wp_update_post($post);

    wp_set_object_terms($projectId, $blockCategory, 'imagepress_image_category');

    update_post_meta($projectId, 'imagepress_purchase', $blockPurchaseUri);
    update_post_meta($projectId, 'imagepress_video', $blockVideoUri);

    if (!empty($blockKeywords)) {
        $keywords = explode(',', $blockKeywords);
        wp_set_post_terms($projectId, $keywords, 'imagepress_image_keyword', false);
    }

    if (isset($blockContent)) {
        update_post_meta($projectId, 'imagepress_project', $blockContent);
    }

    if ($blockThumbnail > 0) {
        set_post_thumbnail($projectId, $blockThumbnail);
    }

    exit;
}

function ip_project_delete() {
    global $current_user;

    // Initialise variables
    $thumbnailId = (int) $_POST['$thumbnailId'];
    $projectId = (int) $_POST['projectId'];

    wp_delete_post($projectId);

    $sizes = array_merge(array('full'), get_intermediate_image_sizes());
    $uploads = wp_upload_dir();
    foreach ($sizes as $imageSize) {
        $image_object = wp_get_attachment_image_src($thumbnailId, $imageSize);
        $image_url = $image_object[0];
        $image_path = str_replace($uploads['baseurl'], $uploads['basedir'], $image_url);

        wp_delete_attachment($imageId, true);
    }

    exit;
}

function ip_project_save_image() {
    $files = $_FILES['async-upload'];

    if ($files) {
        if ($files['name']) {
            $file = [
                'name' => $files['name'],
                'type' => $files['type'],
                'tmp_name' => $files['tmp_name'],
                'error' => $files['error'],
                'size' => $files['size']
            ];
        }
        $_FILES = ['attachment' => $file];
        foreach ($_FILES as $file => $array) {
            $attach_id = media_handle_upload($file, 0); // not attached yet
            if ($attach_id < 0) {
                $post_error = true;
            }
        }
    }
    echo $attach_id;

    exit;
}

function ip_project_show_image() {
    $imageId= $_POST['imageId'];

    $image_attributes = wp_get_attachment_image_src($imageId, 'imagepress_feed');

    //$image_attributes = wp_get_attachment_image($imageId, 'imagepress_feed');
    echo '<img src="' . $image_attributes[0] . '" width="' . $image_attributes[1] . '" height="' . $image_attributes[2] . '" data-attachment-id="' . $imageId . '" alt="">';

    exit;
}

function ip_project_delete_image() {
    $imageId = $_POST['imageId'];

    $sizes = array_merge(array('full'), get_intermediate_image_sizes());
    $uploads = wp_upload_dir();
    foreach ($sizes as $imageSize) {
        $image_object = wp_get_attachment_image_src($imageId, $imageSize);
        $image_url = $image_object[0];
        $image_path = str_replace($uploads['baseurl'], $uploads['basedir'], $image_url);

        //unlink($image_path);
        wp_delete_attachment($imageId, true);
    }

    exit;
}




function imagepress_resize_default_images($data) {
	$ip_width = get_option('ip_max_width');
	$ip_quality = get_option('ip_max_quality');

	// Return an implementation that extends WP_Image_Editor
	$arguments = [
		'mime_type' => 'image/jpeg',
		'methods' => [
			'resize',
			'save'
		]
	];
	$image = wp_get_image_editor($data['file'], $arguments);
	if (is_wp_error($image)) {
		return false;
	}
	$image->set_quality($ip_quality);
	// max full size width: 960, unlimited height, no cropping
	$image->resize($ip_width, 99999, false);
	$image->save($data['file']);

	return $data;
}

if ((int) get_option('ip_resize') === 1)
	add_action('wp_handle_upload', 'imagepress_resize_default_images');

function imagepress_process_image($file, $post_id, $caption, $feature = 1) {
	require_once ABSPATH . 'wp-admin' . '/includes/image.php';
	require_once ABSPATH . 'wp-admin' . '/includes/file.php';
	require_once ABSPATH . 'wp-admin' . '/includes/media.php';

	$attachment_id = media_handle_upload($file, $post_id);

	if ((int) $feature === 1) {
		set_post_thumbnail($post_id, $attachment_id);
	}

	return $attachment_id;
}

function imagepress_get_upload_image_form($imagepress_image_caption = '', $imagepress_image_category = 0, $imagepress_image_description = '', $imagepress_hardcoded_category) {
    global $current_user;
    get_currentuserinfo();
    // upload form // customize

	$out = '<div class="ip-uploader">';
		$out .= '<form id="imagepress_upload_image_form" method="post" action="" enctype="multipart/form-data" class="imagepress-form">';
			$out .= wp_nonce_field('imagepress_upload_image_form', 'imagepress_upload_image_form_submitted');
            // name and email
            $out .= '<input type="hidden" name="imagepress_author" value="' . $current_user->display_name . '">';
            $out .= '<input type="hidden" name="imagepress_email" value="' . $current_user->user_email . '">';

			$ip_caption_label = get_option('ip_caption_label');
			if (!empty($ip_caption_label))
				$out .= '<p><input type="text" id="imagepress_image_caption" name="imagepress_image_caption" placeholder="' . get_option('ip_caption_label') . '" required></p>';

			$ip_description_label = get_option('ip_description_label');
			if (!empty($ip_description_label)) {
				$out .= '<p><textarea id="imagepress_image_description" name="imagepress_image_description" placeholder="' . get_option('ip_description_label') . '" rows="6" required></textarea></p>';
			}

			$out .= '<p>';
				if ('' != $imagepress_hardcoded_category) {
					$iphcc = get_term_by('slug', $imagepress_hardcoded_category, 'imagepress_image_category'); // ImagePress hard-coded category
					$out .= '<input type="hidden" id="imagepress_image_category" name="imagepress_image_category" value="' . $iphcc->term_id . '">';
				} else {
					$out .= imagepress_get_image_categories_dropdown('imagepress_image_category', '') . '';
				}
			$out .= '</p>';

			// sticky image
			if ('' != get_option('ip_sticky_label'))
				$out .= '<p><input type="checkbox" id="imagepress_sticky" name="imagepress_sticky" value="1"> <label for="imagepress_sticky">' . get_option('ip_sticky_label') . '</label></p>';

            $out .= '<h3>External Links</h3>';

			if ('' != get_option('ip_purchase_label'))
				$out .= '<p><input type="url" id="imagepress_purchase" name="imagepress_purchase" placeholder="' . get_option('ip_purchase_label') . '"></p>';
			if ('' != get_option('ip_video_label'))
				$out .= '<p><input type="url" id="imagepress_video" name="imagepress_video" placeholder="' . get_option('ip_video_label') . '"></p>';
			if ('' != get_option('ip_keywords_label'))
				$out .= '<p><input type="text" id="imagepress_image_keywords" name="imagepress_image_keywords" placeholder="' . get_option('ip_keywords_label') . '"></p>';
			//if ('' != get_option('ip_wrb_link_label'))
				//$out .= '<p><input type="url" id="imagepress_image_wrb" name="imagepress_image_wrb" placeholder="' . get_option('ip_wrb_link_label') . '"></p>';

			//$out .= ip_custom_field('url', 'ip_wrb_link_label', 'imagepress_image_wrb', 'upload', '');

            $ip_upload_size = get_option('ip_upload_size');
			$uploadsize = number_format((($ip_upload_size * 1024)/1024000), 0, '.', '');
			$datauploadsize = $uploadsize * 1024000;

            $out .= '<hr>';
            $out .= '<div id="imagepress-errors"></div>';
            $out .= '<h3>Primary Image</h3>';
            $out .= '<p style="display: inline-block;"><label for="imagepress_image_file"><i class="fas fa-cloud-upload-alt"></i> Select a file (' . $uploadsize . 'MB maximum)...</label><br><input type="file" accept="image/*" data-max-size="' . $datauploadsize . '" data-max-width="' . $ip_width . '" name="imagepress_image_file" id="imagepress_image_file"></p>';

            $out .= '<p style="display: inline-block; margin: 0 64px 0 8px;"><b><small>OR</small></b></p>';

            if ((int) get_option('ip_dropbox_enable') === 1) {
                $out .= '<script src="https://www.dropbox.com/static/api/2/dropins.js" id="dropboxjs" data-app-key="' . get_option('ip_dropbox_key') . '"></script>';
                $out .= '<p id="droptarget" style="display: inline-block;"></p>';
                $out .= '<script>
                options = {
                    success: function(files) {
                        document.getElementById("imagepress_dropbox_file").value = files[0].link;
                    },
                    linkType: "direct",
                    extensions: ["images"]
                };
                var button = Dropbox.createChooseButton(options); document.getElementById("droptarget").appendChild(button);</script>';
                $out .= '<input type="hidden" id="imagepress_dropbox_file" name="imagepress_dropbox_file">';
            }

			$out .= '<hr>';

            $out .= '<h3>Secondary Image(s)<br><small>Additional images (variants, making of, progress shots)</small></h3>';
            $out .= '<p><label for="imagepress_image_additional"><i class="fas fa-cloud-upload-alt"></i> Select file(s) (' . $uploadsize . 'MB maximum)...</label><br><input type="file" accept="image/*" name="imagepress_image_additional[]" id="imagepress_image_additional" multiple></p><hr>';

            $out .= '<p style="padding: 16px 0; font-size: 13px;"><input type="checkbox" id="ip-agree"> <label for="ip-agree">By uploading my artwork to PosterSpy, I agree that the work is my own and complies with the website\'s <a href="https://posterspy.com/about/terms-of-use/" target="_blank">Terms of Service</a>.</label></p>';
			$out .= '<p class="center">';
				$out .= '<input type="submit" id="imagepress_submit" name="imagepress_submit" value="Upload Poster" class="button noir-secondary">';
				$out .= ' <span id="ipload"></span>';
			$out .= '</p>';
		$out .= '</form>';
	$out .= '</div>';

	return $out;
}








function imagepress_get_image_categories_dropdown($taxonomy, $selected) {
	return wp_dropdown_categories([
		'taxonomy' => $taxonomy,
		'name' => 'imagepress_image_category',
		'selected' => $selected,
        'exclude' => get_option('ip_cat_exclude'),
		'hide_empty' => 0,
		'echo' => 0,
		'show_option_all' => get_option('ip_category_label')
	]);
}


function imagepress_activate() {
	add_option('ip_ipp', 20);
	add_option('ip_app', 10);
	add_option('ip_padding', 1);

	add_option('ip_upload_size', 2048);
	add_option('ip_moderate', 0);
	add_option('ip_registration', 1);

	add_option('ip_order', 'DESC');
	add_option('ip_orderby', 'date');

	add_option('approvednotification', 'yes');
	add_option('declinednotification', 'yes');

	add_option('ip_name_label', 'Name');
	add_option('ip_email_label', 'Email Address');

	add_option('ip_caption_label', 'Image Caption');
	add_option('ip_category_label', 'Image Category');
	add_option('ip_image_label', 'Select Image');
	add_option('ip_description_label', 'Image Description');
	add_option('ip_upload_label', 'Upload');
	add_option('ip_keywords_label', 'Image Keywords (optional, separate with comma, backspace or x to remove)');
	add_option('ip_video_label', 'Youtube/Vimeo link');
	add_option('ip_purchase_label', 'Purchase link for this project (optional)');
	add_option('ip_sticky_label', 'Sticky (display this image with higher priority)');

    // configurator options
    add_option('ip_image_size', 'large');
    add_option('ip_title_optional', 1);
    add_option('ip_meta_optional', 1);
    add_option('ip_views_optional', 1);
    add_option('ip_likes_optional', 1);
    add_option('ip_comments', 1);
    add_option('ip_author_optional', 1);

    add_option('ip_cat_exclude', '');
    add_option('ip_cat_moderation_include', '');

    // users
    add_option('cinnamon_label_index', 'View all');
    add_option('cinnamon_label_portfolio', 'My Hub');
    add_option('cinnamon_label_about', 'About/Biography');
    add_option('cinnamon_label_hub', 'My Hub');
    add_option('cinnamon_hide', '');
    add_option('cinnamon_image_size', 150);

    add_option('cinnamon_show_awards', 0);
    add_option('cinnamon_show_followers', 0);
    add_option('cinnamon_show_following', 0);
    add_option('ip_cards_per_author', 9);
    add_option('ip_cards_image_size', 'thumbnail');

    add_option('cinnamon_edit_page', '');

    add_option('cinnamon_mod_hub', 0);

    add_option('cms_title', 'Your Site Title');
    add_option('cms_featured_tooltip', 'Staff Favourite');
    add_option('cms_verified_profile', 'Verified Profile');

    add_option('cinnamon_account_page', 'http://yourdomain.com/login/');

    //
    add_option('ip_upload_secondary', 0);
    add_option('ip_override_email_notification', 1);

    //
    add_option('cinnamon_show_likes', 1);

	add_option('ip_resize', 0);
	add_option('ip_max_width', 1920);
	add_option('ip_max_quality', 100);

	add_option('ip_vote_like', "I like this image");
	add_option('ip_vote_unlike', "Oops! I don't like this");
	add_option('ip_vote_nobody', "Nobody likes this yet");
	add_option('ip_vote_who', "Users that like this image:");
	add_option('ip_vote_who_singular', "user likes this");
	add_option('ip_vote_who_plural', "users like this");
	add_option('ip_vote_who_link', "who?");

    add_option('ip_likes', 'likes');
    add_option('ip_vote_login', 'You need to be logged in to like this');

	add_option('ip_author_find_title', 'Find by name or location');
	add_option('ip_author_find_placeholder', 'Search by name or location...');
	add_option('ip_image_find_title', 'Find by author or title');
	add_option('ip_image_find_placeholder', 'Search by author or title...');

	add_option('ip_notifications_mark', 'Mark all as read');
	add_option('ip_notifications_all', 'View all notifications');

	add_option('ip_upload_success_title', 'Image uploaded!');
	add_option('ip_upload_success', 'Click here to view your image.');

	add_option('hook_upload_success', '');

    add_option('ip_dropbox_enable', 0);
    add_option('ip_dropbox_key', '');

	delete_option('notification_limit');
	delete_option('notification_thumbnail_custom');

	// New update
	delete_option('ip_mod_collections');
	delete_option('ip_mod_login');
	delete_option('ip_click_behaviour');
	delete_option('hook_share_single');
	delete_option('cinnamon_author_slug');
	delete_option('cinnamon_hide_admin');
	delete_option('ip_slug');
	delete_option('ip_show_single_image');
	delete_option('ip_collections_read_more');
	delete_option('ip_collections_read_more_link');
	delete_option('cinnamon_pt_collections');
	delete_option('cinnamon_pt_portfolio');
	delete_option('cinnamon_pt_profile');
	delete_option('cinnamon_pt_author');
	delete_option('cinnamon_pt_account');
	delete_option('cinnamon_edit_label');
	delete_option('ip_vote_meta');
	delete_option('ip_request_user_details');
	delete_option('ip_require_description');

    global $wpdb;

	// notifications table
	$table_name = $wpdb->prefix . 'notifications';
	if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
		$sql = "CREATE TABLE IF NOT EXISTS `$table_name` (
            `ID` int(11) NOT NULL AUTO_INCREMENT,
            `userID` int(11) NOT NULL,
            `postID` int(11) NOT NULL,
            `actionType` text COLLATE utf8_unicode_ci NOT NULL,
            `actionIcon` text COLLATE utf8_unicode_ci NOT NULL,
            `actionTime` datetime NOT NULL,
            `status` tinyint(1) NOT NULL DEFAULT '0',

            PRIMARY KEY (`ID`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		dbDelta($sql);

		// this column holds the collection IDs
		$wpdb->query("ALTER TABLE `" . $wpdb->prefix . "notifications` ADD `postKeyID` INT NOT NULL AFTER `postID`;");
	}

	// collections table
	$table_name = $wpdb->prefix . 'ip_collections';
	if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
		$sql = "CREATE TABLE IF NOT EXISTS `$table_name` (
			`collection_ID` int(11) NOT NULL,
			`collection_title` text COLLATE utf8_unicode_ci NOT NULL,
			`collection_title_slug` text COLLATE utf8_unicode_ci NOT NULL,
			`collection_status` tinyint(4) NOT NULL DEFAULT '1',
			`collection_views` int(11) NOT NULL,
			`collection_author_ID` int(11) NOT NULL
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		dbDelta($sql);

		$wpdb->query("ALTER TABLE `" . $wpdb->prefix . "ip_collections` ADD PRIMARY KEY (`collection_ID`);");
		$wpdb->query("ALTER TABLE `" . $wpdb->prefix . "ip_collections` MODIFY `collection_ID` int(11) NOT NULL AUTO_INCREMENT;");
	}
	$table_name = $wpdb->prefix . 'ip_collectionmeta';
	if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
		$sql = "CREATE TABLE IF NOT EXISTS `$table_name` (
			`image_meta_ID` int(11) NOT NULL,
			`image_ID` int(11) NOT NULL,
			`image_collection_ID` int(11) NOT NULL,
			`image_collection_author_ID` int(11) NOT NULL
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		dbDelta($sql);

		$wpdb->query("ALTER TABLE `" . $wpdb->prefix . "ip_collectionmeta` ADD UNIQUE KEY `image_meta_ID` (`image_meta_ID`);");
		$wpdb->query("ALTER TABLE `" . $wpdb->prefix . "ip_collectionmeta` MODIFY `image_meta_ID` int(11) NOT NULL AUTO_INCREMENT;");
	}
}

function imagepress_deactivate() {
    flush_rewrite_rules();
}

register_activation_hook(__FILE__, 'imagepress_activate');
register_deactivation_hook(__FILE__, 'imagepress_deactivate');


add_action('wp_enqueue_scripts', 'ip_enqueue_scripts');
function ip_enqueue_scripts() {
	wp_enqueue_script('fa5', 'https://use.fontawesome.com/releases/v5.2.0/js/all.js', [], '5.2.0', true);
	wp_enqueue_script('sweetalert2', 'https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.26.11/sweetalert2.min.js', [], '7.26.11', true);

	wp_enqueue_style('sweetalert2', 'https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.26.11/sweetalert2.min.css');
	wp_enqueue_style('ip-bootstrap', plugins_url('css/ip.bootstrap.css', __FILE__));

	wp_enqueue_script('sortablejs', plugins_url('js/Sortable.min.js', __FILE__), [], '1.7.0', true);
	wp_enqueue_script('uploader', plugins_url('js/uploader.js', __FILE__), ['jquery', 'sortablejs', 'sweetalert2'], '6.4.0', true);

    wp_enqueue_script('ipjs-main', plugins_url('js/jquery.main.js', __FILE__), ['jquery', 'masonry'], '6.4.0', true);
	wp_localize_script('ipjs-main', 'ip_ajax_var', [
		'imagesperpage'               => get_option('ip_ipp'),
		'authorsperpage'              => get_option('ip_app'),
		'likelabel'                   => get_option('ip_vote_like'),
		'unlikelabel'                 => get_option('ip_vote_unlike'),
		'processing_error'            => 'There was a problem processing your request.',
		'login_required'              => 'Oops, you must be logged-in to follow users.',
		'logged_in'                   => is_user_logged_in() ? 'true' : 'false',
		'ajaxurl'                     => admin_url('admin-ajax.php'),
		'nonce'                       => wp_create_nonce('ajax-nonce'),

        'redirecturl'                 => apply_filters('fum_redirect_to', $_SERVER['REQUEST_URI']),
        'loadingmessage'              => 'Checking credentials...',
        'registrationloadingmessage'  => 'Processing registration...',

        'ajaxreloadurl'               => plugins_url('ajax/reload-like.php', __FILE__),
        'ajaxcollecturl'               => plugins_url('ajax/reload-collect.php', __FILE__),
	]);
	wp_localize_script('uploader', 'ip_ajax_var', [
		'ajaxurl' => admin_url('admin-ajax.php')
	]);
}
// end

function imagepress_search($atts, $content = null) {
	extract(shortcode_atts([
		'type' => '',
	], $atts));

	$display = '<form role="search" method="get" action="' . home_url() . '" class="imagepress-form">
			<div>
				<input type="search" name="s" id="s" placeholder="Search images..."> 
				<input type="submit" id="searchsubmit" value="Search">
				<input type="hidden" name="post_type" value="poster">
			</div>
		</form>';

	return $display;
}

/*
 * Main shortcode function [imagepress_show]
 *
 */
function imagepress_show($atts, $content = null) {
	extract(shortcode_atts([
		'category' 		=> '',
		'count' 		=> 0,
		'limit' 		=> 999999,
		'user' 			=> 0,
		'size' 			=> '',
		'columns' 		=> '',
		'sort' 			=> 'no',
        'filters' 		=> 'no',
		'type' 			=> '', // 'random'
		'collection' 	=> '', // new parameter (will extract all images from a certain collection)
		'collection_id'	=> '', // new parameter (will extract all images from a certain collection)
		'meta' 			=> '',
		'metaids' 		=> '',
	], $atts));

	global $wpdb, $current_user;
    $ip_unique_id = uniqid();

	$ip_order = 'rand';
	if (empty($type))
		$ip_order = get_option('ip_orderby');

	$ip_ipp = -1;
	if ((int) $count !== 0)
		$ip_ipp = $count;

	if ((int) $user > 0)
		$author = $user;
	if (isset($_POST['user']))
		$author = $_POST['user'];

    // defaults
    $ip_order_asc_desc = get_option('ip_order');

    // main images query
	$out = '';
	$cs = [];

	if (!empty($collection) && is_numeric($collection) && $collection > 0) {
		global $wp_query;

        $ip_parameters = $wp_query->query_vars;

        if (!empty($ip_parameters['page'])) {
			$collection_page = $ip_parameters['page'];
		} else {
			$collection_page = $collection_id;
		}

        $collectionables = $wpdb->get_results("SELECT image_ID, image_collection_ID FROM " . $wpdb->prefix . "ip_collectionmeta WHERE image_collection_ID = '" . $collection_page . "'", ARRAY_A);

		foreach ($collectionables as $collectable) {
			$cs[] = $collectable['image_ID'];
			$cm = $collectable['image_collection_ID'];
		}

		$wpdb->query($wpdb->prepare("UPDATE " . $wpdb->prefix . "ip_collections SET collection_views = collection_views + 1 WHERE collection_ID = %d", $collection_page));

		$collection_row = $wpdb->get_row("SELECT * FROM " . $wpdb->prefix . "ip_collections WHERE collection_ID = '" . $collection_page . "'", ARRAY_A);

        $out .= '<div class="ip-template-collection-meta">
            <h3>' . $collection_row['collection_title'] . '</h3>
			<div>Created by <a href="' . get_author_posts_url($collection_row['collection_author_ID']) . '">' . get_the_author_meta('nickname', $collection_row['collection_author_ID']) . '</a></div>
            <div class="ip-template-collection-meta-stat">' . $collection_row['collection_views'] . '<br><span>views</span></div><div class="ip-template-collection-meta-stat">' . count($collectionables) . '<br><span>images</span></div>
        </div>';

		$hmc = count($collectionables);
		if ((int) $hmc === 0 or empty($hmc)) {
			$out .= '<p>This collection is empty.</p>';
			return $out;
			get_footer();
			die();
		}
	}

	// all filters should be applied here
	if ((string) $meta == 'love') {
		$metaids = explode(',', $metaids);
		$args = [
			'imagepress_image_category' => $category,
			'post_type' 				=> 'poster',
			'posts_per_page' 			=> $limit,//$ip_ipp,
			'orderby' 					=> $ip_order,
			'order' 					=> $ip_order_asc_desc,
			'author' 					=> $author,
			'post__in' 					=> $metaids,
            'cache_results'             => false,
            'no_found_rows'             => true,
		];
	}
	else {
		$args = [
			'imagepress_image_category' => $category,
			'post_type' 				=> 'poster',
			'posts_per_page' 			=> $limit,//$ip_ipp,
			'orderby' 					=> $ip_order,
			'order' 					=> $ip_order_asc_desc,
			'author' 					=> $author,
			'post__in' 					=> $cs,
            'cache_results'             => false,
            'no_found_rows'             => true,
		];
	}

	$posts = get_posts($args);
    //

	if ($posts) {
		//$out .= '<div class="ip_clear"></div>';

        if (!empty($columns) && is_numeric($columns)) {
            $custom_column_size = 99.96 / $columns;
            $out .= '<style>#ip_container_' . $ip_unique_id . ' .ip_box { width: ' . $custom_column_size . '%; height: ' . $custom_column_size . '%; }</style>';
        }
        $out .= '<style>.ip_box { padding: ' . get_option('ip_padding') . 'px; }</style>';

        $out .= '<div id="cinnamon-cards">';
		if ((string) $sort === 'yes') {
			$out .= '<div class="cinnamon-sortable">
				<div class="innersort">
					<h4>Sort</h4>
					<span class="sort initial" data-sort="imageviews" data-order="desc"><i class="fas fa-circle fa-fw"></i> Most views</span>
					<span class="sort" data-sort="imagecomments" data-order="desc"><i class="fas fa-circle fa-fw"></i> Most comments</span>
					<span class="sort" data-sort="imagelikes" data-order="desc"><i class="fas fa-circle fa-fw"></i> Most ' . get_option('ip_likes') . '</span>
				</div>
				<div class="innersort">
					<h4>' . get_option('ip_image_find_title') . '</h4>
					<input type="text" class="search" id="ipsearch" placeholder="' . get_option('ip_image_find_placeholder') . '">
				</div>
				<div style="clear: both;"></div>
			</div>';
		}

        if (current_user_can('manage_options')) {
            if ((string) $filters === 'yes') {
                $out .= '<div class="cinnamon-filters"><a href="#" class="sortByTaxonomyList" id="ip-taxonomy-filter-none"><i class="fas fa-times"></i> All</a>';
                $terms = get_terms('imagepress_image_category');
                if (!empty($terms) && !is_wp_error($terms)) {
                    foreach ($terms as $term) {
                        if (strtolower($term->name) !== 'featured') {
                            $out .= '<a href="#" class="sortByTaxonomyList">' . $term->name . '</a>';
                        }
                    }
                }
                $out .= '</div>';
            }
        }
		
		$out .= '<div id="ip-boxes" class="ip-box-container" data-imagepress-count="' . get_option('ip_ipp') . '">';

        // the configurator
        $ip_comments = '';

        if (empty($size)) {
            $size = (string) 'imagepress_pt_lrg';
        }

        foreach ($posts as $user_image) {
            $i = $user_image->ID;

            $user_info = get_userdata($user_image->post_author);
            $post_thumbnail_id = get_post_thumbnail_id($i);   

            $image_attributes = wp_get_attachment_image_src($post_thumbnail_id, $size);

            $out .= '<div class="ip_box ip_box_' . $i . '"><a href="' . get_permalink($i) . '" class="ip_box_img"><img src="' . $image_attributes[0] . '" alt="' . get_the_title($i) . '"></a><div class="ip_box_top"><a href="' . get_permalink($i) . '" class="imagetitle">' . get_the_title($i) . '</a><span class="imagecategory" data-tag="' . strip_tags(get_the_term_list($i, 'imagepress_image_category', '', ', ', '')) . '">' . strip_tags(get_the_term_list($i, 'imagepress_image_category', '', ', ', '')) . '</span><span class="name">' . get_avatar($user_info->ID, 16) . ' <a href="' . get_author_posts_url($user_info->ID) . '">' . $user_info->display_name . '</a></span></div>';

            if (!empty($collection) && is_numeric($collection) && (int) $collection > 0) {
                $logged_in_user = wp_get_current_user();
                if ((int) $collection_row['collection_author_ID'] === (int) $logged_in_user->ID) {
                    $out .= '<div class="ip_box_bottom"><a href="#" class="deleteCollectionImage" data-image-id="' . $i . '"><i class="fas fa-times"></i> Remove</a></div>';
                }
            }

            $out .= '</div>';
		}

		$out .= '</div>';

        if ((int) $limit === 999999 or (int) $count === 0) {
			$out .= '<ul class="pagination"></ul>';
        }

        $out .= '</div><div class="ip_clear"></div>';
	} else {
		$out .= 'No images found!';
	}

	return $out;
}


function imagepress_menu_bubble() {
	global $menu, $submenu;

	$args = [
		'post_type' => 'poster',
		'post_status' => 'pending',
		'showposts' => -1,
	];
	$draft_ip_links = count(get_posts($args));

	if ($draft_ip_links) {
		foreach ($menu as $key => $value) {
			if ($menu[$key][2] == 'edit.php?post_type=poster') {
				$menu[$key][0] .= ' <span class="update-plugins count-' . $draft_ip_links . '"><span class="plugin-count">' . $draft_ip_links . '</span></span>';
				return;
			}
		}
	}
	if ($draft_ip_links) {
		foreach ($submenu as $key => $value) {
			if ($submenu[$key][2] == 'edit.php?post_type=poster') {
				$submenu[$key][0] .= ' <span class="update-plugins count-' . $draft_ip_links . '"><span class="plugin-count">' . $draft_ip_links . '</span></span>';
				return;
			}
		}
	}
}

function imagepress_notify_status($new_status, $old_status, $post) {
	global $current_user;

	$contributor = get_userdata($post->post_author);

	$headers[] = "MIME-Version: 1.0\r\n";
	$headers[] = "Content-Type: text/html; charset=\"" . get_option('blog_charset') . "\"\r\n";

	if ($old_status != 'pending' && $new_status == 'pending') {
		$emails = get_option('ip_notification_email');
		if (strlen($emails)) {
			$subject = '[' . get_option('blogname') . '] "' . $post->post_title . '" pending review';
			$message = "<p>A new post by {$contributor->display_name} is pending review.</p>";
			$message .= "<p>Author: {$contributor->user_login} <{$contributor->user_email}> (IP: {$_SERVER['REMOTE_ADDR']})</p>";
			$message .= "<p>Title: {$post->post_title}</p>";
			$category = get_the_category($post->ID);
			if (isset($category[0])) 
				$message .= "<p>Category: {$category[0]->name}</p>";
			wp_mail($emails, $subject, $message, $headers);
		}
	} else if ((string) $old_status === 'pending' && (string) $new_status === 'publish') {
		if ((string) get_option('approvednotification') === 'yes') {
			$subject = '[' . get_option('blogname') . '] "' . $post->post_title . '" approved';
			$message = "<p>{$contributor->display_name}, your post has been approved and published at " . get_permalink($post->ID) . ".</p>";
			wp_mail($contributor->user_email, $subject, $message, $headers);
		}
	} else if ((string) $old_status === 'pending' && (string) $new_status === 'draft' && (int) $current_user->ID !== (int) $contributor->ID) {
		if ((string) get_option('declinednotification') === 'yes') {
			$subject = '[' . get_option('blogname') . '] "' . $post->post_title . '" declined';
			$message = "<p>{$contributor->display_name}, your post has not been approved.</p>";
			wp_mail($contributor->user_email, $subject, $message, $headers);
		}
	}
}

/*
 * Main shortcode function [imagepress]
 *
 */
function imagepress_widget($atts, $content = null) {
	extract(shortcode_atts([
        'type' => 'list', // list, top
		'mode' => 'views', // views, likes
        'count' => 5
	], $atts));

    $imagepress_meta_key = 'post_views_count';
    if ((string) $mode === 'likes')
        $imagepress_meta_key = 'votes_count';

	if ((string) $type === 'top')
		$count = 1;

    $args = [
        'post_type' 				=> 'poster',
        'posts_per_page' 			=> $count,
        'orderby' 					=> 'meta_value_num',
        'meta_key'                  => $imagepress_meta_key,
        'meta_query'                => [
            [
                'key'       => $imagepress_meta_key,
                'type'      => 'numeric'
            ]
        ],
        'cache_results'             => false,
        'update_post_term_cache'    => false,
        'update_post_meta_cache'    => false,
        'no_found_rows'             => true,
    ];

    $is = get_posts($args);

    if ($is && ((string) $type === 'list')) {
        $display = '<ul>';
            foreach ($is as $i) {
                if ((string) $mode === 'likes')
                    $ip_link_value = getPostLikeLink($i->ID, false);
                if ((string) $mode === 'views')
                    $ip_link_value = ip_getPostViews($i->ID);

                $display .= '<li><a href="' . get_permalink($i->ID) . '">' . get_the_title($i->ID) . '</a> <small>(' . $ip_link_value . ')</small></li>';
            }
        $display .= '</ul>';
    }

    if($is && ($type == 'top')) {
        foreach($is as $i) {
			if(get_option('ip_comments') == 1)
				$ip_comments = '<i class="fas fa-comments"></i> ' . get_comments_number($user_image->ID) . '';
			if(get_option('ip_comments') == 0)
				$ip_comments = '';

			$post_thumbnail_id = get_post_thumbnail_id($i->ID);   
			$image_attributes = wp_get_attachment_image_src($post_thumbnail_id, 'full');

			$ip_image_link = get_permalink($i->ID);

			$display .= '<div id="ip_container_2"><div class="ip_icon_hover">' . 
                    '<div><strong>' . get_the_title($i->ID) . '</strong></div>' . 
					'<div><small><i class="fas fa-eye"></i> ' . ip_getPostViews($i->ID) . ' ' . $ip_comments . ' <i class="fas fa-heart"></i> ' . imagepress_get_like_count($i->ID) . '</small></div>
				</div><a href="' . $ip_image_link . '" class="ip-link">' . wp_get_attachment_image($post_thumbnail_id, 'full') . '</a></div>';
		}
    }

    return $display;
}









function ps_find_shortcode($atts, $content = null) { 
	ob_start();
	extract(shortcode_atts([
		'find' => '',
	], $atts));

	$string = $atts['find'];

	$args = [
		's' => $string,
    ];

	$the_query = new WP_Query($args);

	if ($the_query->have_posts()) {
		echo '<ul>';
			while ($the_query->have_posts()) {
				$the_query->the_post(); ?>
				<li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
			<?php }
		echo '</ul>';
	} else {
		echo 'No posts found containing your selected shortcode.';
	}

	wp_reset_postdata();

	return ob_get_clean();
}
add_shortcode('shortcodefinder', 'wpb_find_shortcode');
