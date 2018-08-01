<?php
function imagepress_registration() {
    /**
    add_user_to_blog(1, 7339, 'author');
    add_user_to_blog(1, 7340, 'author');
    add_user_to_blog(1, 7341, 'author');
    add_user_to_blog(1, 7342, 'author');
    add_user_to_blog(1, 7343, 'author');
    add_user_to_blog(1, 7344, 'author');
    add_user_to_blog(1, 7345, 'author');
    add_user_to_blog(1, 7347, 'author');
    add_user_to_blog(1, 7348, 'author');
    add_user_to_blog(1, 7349, 'author');
    add_user_to_blog(1, 7351, 'author');
    add_user_to_blog(1, 7352, 'author');
    add_user_to_blog(1, 7354, 'author');
    add_user_to_blog(1, 7355, 'author');
    add_user_to_blog(1, 7358, 'author');
    /**/

    $ip_slug = get_option('ip_slug');

	$image_type_labels = array(
		'name' 					=> _x('Images', 'post type general name'),
		'singular_name' 		=> _x('Image', 'post type singular name'),
		'add_new' 				=> _x('Add New Image', 'image'),
		'add_new_item' 			=> __('Add New Image'),
		'edit_item' 			=> __('Edit Image'),
		'new_item' 				=> __('Add New Image'),
		'all_items' 			=> __('View Images'),
		'view_item' 			=> __('View Image'),
		'search_items' 			=> __('Search Images'),
		'not_found' 			=> __('No images found'),
		'not_found_in_trash' 	=> __('No images found in trash'), 
		'parent_item_colon' 	=> '',
		'menu_name' 			=> __('ImagePress', 'imagepress')
	);

	$image_type_args = array(
		'labels' 				=> $image_type_labels,
		'public' 				=> true,
		'query_var' 			=> true,
        'rewrite'               => array('slug' => 'posters', 'with_front' => true),
		'capability_type' 		=> 'post',
		'has_archive' 			=> true,
		'hierarchical' 			=> false,
		'map_meta_cap' 			=> true,
		'show_ui' 				=> true,
		'show_in_menu' 			=> true,
		'menu_position' 		=> 15,
		'show_in_admin_bar' 	=> true,
		'show_in_nav_menus' 	=> true,
		'supports' 				=> array('title', 'editor', 'author', 'thumbnail', 'comments', 'custom-fields', 'jetpack_sitemap_post_types'),
		'menu_icon' 			=> 'dashicons-format-gallery',
	);

	register_post_type($ip_slug, $image_type_args);

	$image_category_labels = array(
		'name' 					=> _x('Poster Categories', 'taxonomy general name'),
		'singular_name' 		=> _x('Poster', 'taxonomy singular name'),
		'search_items' 			=> __('Search Poster Categories'),
		'all_items' 			=> __('All Poster Categories'),
		'parent_item' 			=> __('Parent Poster Category'),
		'parent_item_colon' 	=> __('Parent Poster Category:'),
		'edit_item' 			=> __('Edit Poster Category'), 
		'update_item' 			=> __('Update Poster Category'),
		'add_new_item' 			=> __('Add New Poster Category'),
		'new_item_name' 		=> __('New Poster Name'),
		'menu_name' 			=> __('Poster Categories'),
	);

	$image_category_args = array(
		'hierarchical' 			=> true,
		'labels' 				=> $image_category_labels,
		'show_ui' 				=> true,
		'query_var' 			=> true,
		'rewrite' 				=> array('slug' => 'genre'),
	);

	register_taxonomy('imagepress_image_category', array($ip_slug), $image_category_args);

    // image keywords
    $labels = array(
		'name'                       => _x('Image Keywords', 'Taxonomy General Name', 'imagepress'),
		'singular_name'              => _x('Image Keyword', 'Taxonomy Singular Name', 'imagepress'),
		'menu_name'                  => __('Image Keywords', 'imagepress'),
		'all_items'                  => __('All Keywords', 'imagepress'),
		'parent_item'                => __('Parent Keyword', 'imagepress'),
		'parent_item_colon'          => __('Parent Keyword:', 'imagepress'),
		'new_item_name'              => __('New Keyword Name', 'imagepress'),
		'add_new_item'               => __('Add New Keyword', 'imagepress'),
		'edit_item'                  => __('Edit Keyword', 'imagepress'),
		'update_item'                => __('Update Keyword', 'imagepress'),
		'separate_items_with_commas' => __('Separate keywords with commas', 'imagepress'),
		'search_items'               => __('Search Keywords', 'imagepress'),
		'add_or_remove_items'        => __('Add or remove keywords', 'imagepress'),
		'choose_from_most_used'      => __('Choose from the most used keywords', 'imagepress'),
		'not_found'                  => __('Not Found', 'imagepress'),
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => false,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => false,
	);

    register_taxonomy('imagepress_image_keyword', array($ip_slug), $args);
}



function ip_getPostViews($postID){
	$count_key = 'post_views_count';
	$count = get_post_meta($postID, $count_key, true);
	if($count == '') {
		delete_post_meta($postID, $count_key);
		add_post_meta($postID, $count_key, '0');
		return '0';
	}
	return $count;
}
function ip_setPostViews($postID) {
	$count_key = 'post_views_count';
	$count = get_post_meta($postID, $count_key, true);
	if($count == '') {
		$count = 0;
		delete_post_meta($postID, $count_key);
		add_post_meta($postID, $count_key, 0);
	}
    else {
		$count++;
		update_post_meta($postID, $count_key, $count);
	}
}



// front-end image editor
function ip_get_object_terms_exclude_filter($terms, $object_ids, $taxonomies, $args) {
    if(isset($args['exclude']) && $args['fields'] == 'all') {
        foreach($terms as $key => $term) {
            foreach($args['exclude'] as $exclude_term) {
                if($term->term_id == $exclude_term) {
                    unset($terms[$key]);
                }
            }
        }
    }
    $terms = array_values($terms);
    return $terms;
}
add_filter('wp_get_object_terms', 'ip_get_object_terms_exclude_filter', 10, 4);

// frontend image editor
function ip_editor() {
    global $post, $current_user;

    get_currentuserinfo();

    // check if user is author // show author tools
    if($post->post_author == $current_user->ID) { ?>
        <a href="#" class="ip-editor-display btn btn-primary" id="ip-editor-open"><i class="fa fa-wrench"></i> <?php _e('Author tools', 'imagepress'); ?></a>
        <?php
        $edit_id = get_the_ID();

        if(isset($_GET['d'])) {
            $post_id = $_GET['d'];
            wp_delete_post($post_id);

            echo '<script>window.location.href="' . home_url() . '?deleted"</script>';
        }
        if('POST' == $_SERVER['REQUEST_METHOD'] && !empty($_POST['post_id']) && !empty($_POST['post_title']) && isset($_POST['update_post_nonce']) && isset($_POST['postcontent'])) {
            $post_id = $_POST['post_id'];
            $post_type = get_post_type($post_id);
            $capability = ('page' == $post_type) ? 'edit_page' : 'edit_post';
            if(current_user_can($capability, $post_id) && wp_verify_nonce($_POST['update_post_nonce'], 'update_post_'. $post_id)) {
                $post = array(
                    'ID'             => esc_sql($post_id),
                    'post_content'   => (stripslashes($_POST['postcontent'])),
                    'post_title'     => esc_sql($_POST['post_title'])
                );
                wp_update_post($post);

				imagepress_process_image('imagepress_image_file', $post_id, $_FILES['imagepress_image_file'], 1);

				// multiple images
                if(1 == get_option('ip_upload_secondary')) {
                    $files = $_FILES['imagepress_image_additional'];
                    if($files) {
                        foreach($files['name'] as $key => $value) {
                            if($files['name'][$key]) {
                                $file = array(
                                    'name' => $files['name'][$key],
                                    'type' => $files['type'][$key],
                                    'tmp_name' => $files['tmp_name'][$key],
                                    'error' => $files['error'][$key],
                                    'size' => $files['size'][$key]
                                );  
                            }
                            $_FILES = array("imagepress_image_additional" => $file);
                            foreach($_FILES as $file => $array) {
                                imagepress_process_image('imagepress_image_additional', $post_id, '');
                            }
                        }
                    }
                }
                // end multiple images

				$images = get_children(array('post_parent' => $post_id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => 'ASC', 'orderby' => 'menu_order ID'));
				$count = count($images);
				if($count == 1 || !has_post_thumbnail($post_id)) {
					foreach($images as $attachment_id => $image) {
						set_post_thumbnail($post_id, $image->ID);
					}
				}

                wp_set_object_terms($post_id, (int)$_POST['imagepress_image_category'], 'imagepress_image_category');

                if('' != get_option('ip_purchase_label'))
                    update_post_meta((int)$post_id, 'imagepress_purchase', (string)$_POST['imagepress_purchase']);
                if('' != get_option('ip_video_label'))
                    update_post_meta((int)$post_id, 'imagepress_video', (string)$_POST['imagepress_video']);
                /**
                if('' != get_option('ip_wrb_link_label'))
                    update_post_meta((int)$post_id, 'imagepress_image_wrb', (string)$_POST['imagepress_image_wrb']);
                /**/
                if('' != get_option('ip_sticky_label'))
                    update_post_meta((int)$post_id, 'imagepress_sticky', (string)$_POST['imagepress_sticky']);

                //echo '<script>window.location.href="' . $_SERVER['REQUEST_URI'] . '"</script>';
            }
            else {
                wp_die("You can't do that");
            }
        }
        ?>
        <div id="info" class="ip-editor">
            <form id="post" class="post-edit front-end-form imagepress-form" method="post" enctype="multipart/form-data">
                <input type="hidden" name="post_id" value="<?php echo $edit_id; ?>">
                <?php wp_nonce_field('update_post_' . $edit_id, 'update_post_nonce'); ?>

                <p><input type="text" id="post_title" name="post_title" value="<?php echo get_the_title($edit_id); ?>"></p>
                <p><textarea name="postcontent" rows="3"><?php echo strip_tags(get_post_field('post_content', $edit_id)); ?></textarea></p>
                <hr>
                <?php if('' != get_option('ip_purchase_label')) { ?>
                    <p><input type="url" name="imagepress_purchase" value="<?php echo get_post_meta($edit_id, 'imagepress_purchase', true); ?>" placeholder="<?php echo get_option('ip_purchase_label'); ?>"></p>
                <?php } ?>
                <?php if('' != get_option('ip_video_label')) { ?>
                    <p><input type="url" name="imagepress_video" value="<?php echo get_post_meta($edit_id, 'imagepress_video', true); ?>" placeholder="<?php echo get_option('ip_video_label'); ?>"></p>
                <?php } ?>
                <?php /** ?>
                <?php if('' != get_option('ip_wrb_link_label')) { ?>
                    <p><input type="url" name="imagepress_image_wrb" value="<?php echo get_post_meta($edit_id, 'imagepress_image_wrb', true); ?>" placeholder="<?php echo get_option('ip_wrb_link_label'); ?>"></p>
                <?php } ?>
                <?php /**/ ?>
                <hr>

                <?php if('' != get_option('ip_sticky_label')) { ?>
                    <p><input type="checkbox" id="imagepress_sticky" name="imagepress_sticky" value="1"<?php if(get_post_meta($edit_id, 'imagepress_sticky', true) == 1) echo ' checked'; ?>> <label for="imagepress_sticky"><?php echo get_option('ip_sticky_label'); ?></label></p>
                <?php } ?>

                <?php $ip_category = wp_get_object_terms($edit_id, 'imagepress_image_category', array('exclude' => array(4))); ?>

                <p>
                    <?php echo imagepress_get_image_categories_dropdown('imagepress_image_category', $ip_category[0]->term_id); ?> 
                </p>

				<?php
                $ip_upload_size = get_option('ip_upload_size');
                $uploadsize = number_format((($ip_upload_size * 1024)/1024000), 0, '.', '');
                $datauploadsize = $uploadsize * 1024000;
				$ip_width = get_option('ip_max_width');
				?>
				<p><label for="imagepress_image_file"><i class="fa fa-cloud-upload"></i> Replace main image (<?php echo $uploadsize . 'MB ' . __('maximum', 'imagepress'); ?>)...</label><br><input type="file" accept="image/*" data-max-size="<?php echo $datauploadsize; ?>" data-max-width="<?php echo $ip_width; ?>" name="imagepress_image_file" id="imagepress_image_file"></p>

                <?php if(1 == get_option('ip_upload_secondary')) { ?>
                    <hr>
                    <p>
                        <?php _e('Select', 'imagepress'); ?> <i class="fa fa-check-circle"></i> <?php _e('main image or', 'imagepress'); ?> <i class="fa fa-times-circle"></i> <?php _e('delete additional images', 'imagepress'); ?>
                        <br><small><?php _e('Main image will appear first in single image listing and as a thumbnail in gallery view', 'imagepress'); ?></small>
                    </p>
                    <?php
                    $thumbnail_ID = get_post_thumbnail_id();
                    $images = get_children(array('post_parent' => $edit_id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => 'ASC', 'orderby' => 'menu_order ID'));
					$count = count($images);

					if(get_option('ip_show_single_image') == 1) {
						if($count > 1) {
					}

					echo '<div>';
						foreach($images as $attachment_id => $image) {
							$small_array = image_downsize($image->ID, 'thumbnail');
							$big_array = image_downsize($image->ID, 'full');

							if($image->ID == $thumbnail_ID)
								echo '<div class="ip-additional-active">';
							if($image->ID != $thumbnail_ID)
								echo '<div class="ip-additional">';
								echo '<div class="ip-toolbar">';
									echo '<a href="#" data-id="' . $image->ID . '" data-nonce="' . wp_create_nonce('ip_delete_post_nonce') . '" class="delete-post ip-action-icon ip-floatright"><i class="fa fa-times-circle"></i></a>';
									echo '<a href="#" data-pid="' . $edit_id . '" data-id="' . $image->ID . '" data-nonce="' . wp_create_nonce('ip_featured_post_nonce') . '" class="featured-post ip-action-icon ip-floatleft"><i class="fa fa-check-circle"></i></a>';
								echo '</div>';
							echo '<img src="' . $small_array[0] . '" alt=""></div>';
						}
					echo '</div>';

					if(get_option('ip_show_single_image') == 1) {
						}
					}
                    ?>

                    <p><label for="imagepress_image_additional"><i class="fa fa-cloud-upload"></i> <?php _e('Add more images', 'imagepress'); ?> (<?php echo MAX_UPLOAD_SIZE/1024; ?>KB <?php _e('maximum', 'imagepress'); ?>)...</label><br><input type="file" accept="image/*" capture="camera" name="imagepress_image_additional[]" id="imagepress_image_additional" multiple></p>
                <?php } ?>

                <hr>
                <p>
                    <input type="submit" id="submit" value="<?php _e('Update image', 'imagepress'); ?>">
                    <a href="?d=<?php echo get_the_ID(); ?>" class="ask button ip-floatright"><i class="fa fa-trash-o"></i></a>
                </p>
            </form>
        </div>
        <?php wp_reset_query(); ?>
    <?php }
}

// ip_editor() related actions
add_action('wp_ajax_ip_delete_post', 'ip_delete_post');
function ip_delete_post() {
    $permission = check_ajax_referer('ip_delete_post_nonce', 'nonce', false);
    if($permission == false) {
        echo 'error';
    }
    else {
        wp_delete_post($_REQUEST['id']);
        echo 'success';
    }
    die();
}
add_action('wp_ajax_ip_featured_post', 'ip_featured_post');
function ip_featured_post() {
    $permission = check_ajax_referer('ip_featured_post_nonce', 'nonce', false);
    if($permission == false) {
        echo 'error';
    }
    else {
        update_post_meta($_REQUEST['pid'], '_thumbnail_id', $_REQUEST['id']);
        echo 'success';
    }
    die();
}



// main ImagePress image function
function ip_main($i) {
	global $post;

	// show image editor
    ip_editor();

	$post_thumbnail_id = get_post_thumbnail_id($i);
    $image_attributes = wp_get_attachment_image_src($post_thumbnail_id, 'full');
    $post_thumbnail_url = $image_attributes[0];

	if(get_option('ip_comments') == 1)
        $ip_comments = '<em> | </em><a href="' . get_permalink($i) . '"><i class="fa fa-comments"></i> ' . get_comments_number($i) . '</a> ';
    if(get_option('ip_comments') == 0)
        $ip_comments = '';
    ?>

    <div class="imagepress-container">
        <?php the_post_thumbnail('full'); ?>
        <?php ip_setPostViews($i); ?>
    </div>
    <?php imagepress_get_images($i); ?>

    <?php
    $imagepress_video = get_post_meta($i, 'imagepress_video', true);
    if(!empty($imagepress_video)) {
        echo '<br>';
        $embed_code = wp_oembed_get($imagepress_video);
        echo $embed_code;
        echo '<br>';
    }
    ?>

    <section role="navigation">
        <?php previous_post_link('%link', '<i class="fa fa-fw fa-chevron-left"></i> Previous'); ?>
        <?php next_post_link('%link', 'Next <i class="fa fa-fw fa-chevron-right"></i>'); ?>
    </section>

    <h1 class="ip-title">
        <?php
        if(has_term('featured', 'imagepress_image_category'))
            echo '<span class="hint hint--right" data-hint="' . get_option('cms_featured_tooltip') . '"><i class="fa fa-star"></i></span> ';

        echo get_the_title($i);
        ?>
    </h1>

    <div class="ip-bar">
        <div class="right">
            <a href="<?php echo $post_thumbnail_url; ?>"><i class="fa fa-fw fa-arrows-alt"></i></a>
        </div>

        <?php echo ipGetPostLikeLink($i); ?><em> | </em><i class="fa fa-eye"></i> <?php echo ip_getPostViews($i); ?><?php echo $ip_comments; ?>
    </div>

    <p>
        <div style="float: left; margin: 0 8px 0 0;">
            <?php echo get_avatar($post->post_author, 40); ?>
        </div>
        <?php
        if(get_the_author_meta('user_title', $post->post_author) == 'Verified')
            $verified = ' <span class="teal hint hint--right" data-hint="' . get_option('cms_verified_profile') . '"><i class="fa fa-check-circle"></i></span>';
        else
            $verified = '';
        ?>
		<?php _e('by', 'imagepress'); ?> <b><a href="<?php echo get_author_posts_url($post->post_author); ?>"><?php echo get_the_author_meta('user_nicename', $post->post_author); ?></a></b> <?php echo $verified; ?>
        <br><small>Uploaded <time title="<?php the_time(get_option('date_format')); ?>"><?php echo human_time_diff(get_the_time('U'), current_time('timestamp')) . ' ago'; ?></time> in <?php echo get_the_term_list(get_the_ID(), 'imagepress_image_category', '', ', ', ''); ?></small>
    </p>

    <section>
        <?php echo wpautop(make_clickable($post->post_content)); ?>
    </section>
    <?php
}

function ip_get_the_term_list( $id = 0, $taxonomy, $before = '', $sep = '', $after = '', $exclude = array() ) {
	$terms = get_the_terms( $id, $taxonomy );

	if ( is_wp_error( $terms ) )
		return $terms;

	if ( empty( $terms ) )
		return false;

	foreach ( $terms as $term ) {

		if(!in_array($term->term_id,$exclude)) {
			$link = get_term_link( $term, $taxonomy );
			if ( is_wp_error( $link ) )
				return $link;
			$term_links[] = '<a href="' . $link . '" rel="tag">' . $term->name . '</a>';
		}
	}

	$term_links = apply_filters( "term_links-$taxonomy", $term_links );

	return $before . join( $sep, $term_links ) . $after;
}

function imagepress_get_images($post_id) {
    global $post;

    $thumbnail_ID = get_post_thumbnail_id();
    $images = get_children(array('post_parent' => $post_id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => 'ASC', 'orderby' => 'menu_order ID'));

    if($images && count($images) > 1) {
        echo '<div class="ip-more">';
            foreach($images as $attachment_id => $image) {
                if($image->ID != $thumbnail_ID) {
                    $big_array = image_downsize($image->ID, 'full');

                    echo '<img src="' . $big_array[0] . '" alt="">';
                }
            }
        echo '</div>';
    }
}

function kformat($number) {
	$prefixes = 'KMGTPEZY';
	if($number >= 1000) {
		$log1000 = floor(log10($number)/3);
		return floor($number/pow(1000, $log1000)).$prefixes[$log1000-1];
	}
	return $number;
}

function ip_related($i) {
	global $post;
	$post_thumbnail_id = get_post_thumbnail_id($i);
	$author_id = $post->post_author;
	$filesize = filesize(get_attached_file($post_thumbnail_id)) / 1024;
	$filesize = number_format($filesize, 2, '.', ' ');
	$filesize .= ' KB';
	?>

	<p><?php previous_post_link('%link', '<i class="fa fa-fw fa-chevron-left"></i>'); ?> <?php next_post_link('%link', '<i class="fa fa-fw fa-chevron-right"></i>'); ?></p>

	<h3 class="widget-title"><i class="fa fa-file-text-o"></i> <?php echo __('Image Details', 'imagepress'); ?></h3>
	<div class="textwidget">
		<p><small>
			&copy;<?php echo date('Y'); ?> <a href="<?php echo get_author_posts_url($post->post_author); ?>"><?php echo get_the_author_meta('user_nicename', $post->post_author); ?></a> | <b>Image size:</b> <?php echo $filesize; ?> | <b>Date uploaded:</b> <?php echo human_time_diff(get_the_time('U'), current_time('timestamp')) . ' ago'; ?> (<?php the_time(get_option('date_format')); ?>) | <b>Category:</b> <?php echo ip_get_the_term_list($i, 'imagepress_image_category', '', ', ', '', array()); ?>
			<br>
			<b><?php echo ip_getPostViews($i); ?></b> <?php echo __('views', 'imagepress'); ?>, <b><?php echo get_comments_number($i); ?></b> <?php echo __('comments', 'imagepress'); ?>, <b><?php echo imagepress_get_like_count($i); ?></b> <?php echo __('likes', 'imagepress'); ?>
		</small></p>
	</div>
	<div class="textwidget">
		<?php
		$hub_user_info = get_userdata($author_id);

		if(get_post_meta($i, 'imagepress_purchase', true) != '') {
			echo '<h3 class="widget-title"><i class="fa fa-external-link-square"></i> ' . __('External Links', 'imagepress') . '</h3>';
			echo '<p>';
		}

		if(get_post_meta($i, 'imagepress_purchase', true) != '')
			echo '<a href="' . get_post_meta($i, 'imagepress_purchase', true) . '" target="_blank" rel="external"><i class="fa fa-shopping-cart"></i> ' . __('Purchase Print', 'imagepress') . '</a>';
		if(get_post_meta($i, 'imagepress_purchase', true) != '')
			echo '</p>';
		?>
	</div>

    <hr>

	<div class="widget-container widget_text">
		<h3 class="widget-title"><i class="fa fa-tags"></i> <?php echo __('Related', 'imagepress'); ?></h3>
		<div class="textwidget">
			<p><i class="fa fa-user"></i> <?php echo __('More by the same author', 'imagepress'); ?> (<a href="<?php echo get_author_posts_url($post->post_author); ?>"><?php echo __('view all', 'imagepress'); ?></a>)</p>
			<?php echo cinnamon_get_related_author_posts($post->post_author); ?>
		</div>
	</div>
	<?php
}

function ip_author() {
	// check for external portfolio // if page call is made from subdomain (e.g. username.domain.ext), display external page
	$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	$parseUrl = parse_url($url);
	$ext_detect = trim($parseUrl['path']);
	if($ext_detect == '/') {
		echo '<div id="hub-loading"></div>';
		echo do_shortcode('[cinnamon-profile-blank]');
	}
	else {
		echo do_shortcode('[cinnamon-profile]');
	}
}

function get_the_ip_slug($id) {
	$post_data = get_post($id, ARRAY_A);
	$slug = $post_data['post_name'];
	return $slug; 
}



function ip_redirect_default_page() {
    return home_url();
}
add_filter('login_redirect', 'ip_redirect_default_page');

function imagepress_login_logo_url() {
    return get_bloginfo( 'url' );
}
function imagepress_login_logo_url_title() {
    return __('Powered by ImagePress', 'imagepress');
}
function imagepress_login_error_override() {
    return __('Incorrect login details.', 'imagepress');
}
function imagepress_login_head() {
    // https://codex.wordpress.org/Plugin_API/Action_Reference/login_enqueue_scripts
    $ip_login_image = get_option('ip_login_image');

    // get random image
    $args = array( 
        'post_type' => 'poster',
        'numberposts' => 1,
        'orderby' => 'rand',
        'post_status' => 'publish',
        'tax_query' => array(array(
            'taxonomy' => 'imagepress_image_category',
            'field' => 'slug',
            'terms' => array('staffpicks'),
            'operator' => 'IN'
        ))
    ); 
    $posters = get_posts($args);
    foreach($posters as $post) {
        $src = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full');
        $author_id = $post->post_author;;
        $credit = ' &nbsp;&nbsp;-&nbsp;&nbsp; Poster by ' . get_avatar($author_id, 16) . ' <a href="' . get_author_posts_url($author_id) . '">' . get_the_author_meta('display_name', $author_id) . '</a>';
    }
    $ip_login_image = $src[0];

    //wp_enqueue_script('jquery');

    /**
    echo '<script>
    jQuery(document).ready(function() {
        jQuery(".imagepress-login-footer").append(\'' . $credit . '\');
    });
    </script>';
    /**/
    echo '<style>';
        if(!empty($ip_login_image))
            echo 'body.login { background-image: url("' . $ip_login_image . '"); background-repeat: no-repeat; background-attachment: fixed; background-position: center; background-size: cover; padding: 64px !important; overflow: hidden; }';
        else
            echo 'body.login { background-color: ' .  get_option('ip_login_bg') . '; padding: 64px !important; overflow: hidden; }';

        echo '.login form { background-color: ' .  get_option('ip_login_box_bg') . '; }';

        if(get_option('ip_login_flat_mode') == 1) {
            echo '.login form { box-shadow: none; border-radius: 0; background: none !important; }';
            echo '.login .button-primary { box-shadow: none; border: 0 none; border-radius: 0; } .login .button-primary:hover, .login .button-primary:active, .login .button-primary:focus { box-shadow: none; }';
            echo '.login input[type="text"], .login input[type="password"] { box-shadow: none; }';
        }

        echo '#login { background-color: rgba(30, 40, 51, 0.95) !important; padding: 16px 24px !important; box-shadow: 0 0 0 1920px rgba(30, 40, 51, 0.5), 0 2px 2px rgba(0, 0, 0, 0.15); border-radius: 3px; }';
        echo '.login .button-primary { box-shadow: none; border-color: ' . get_option('ip_login_button_bg') . '; background-color: ' . get_option('ip_login_button_bg') . '; color: ' . get_option('ip_login_button_text') . '; }';
        echo '.login .button-primary:hover { box-shadow: none; border-color: ' . get_option('ip_login_button_bg') . '; background-color: ' . get_option('ip_login_button_bg') . '; color: ' . get_option('ip_login_button_text') . '; }';
        echo '.login .button-primary:focus { box-shadow: none; border-color: ' . get_option('ip_login_button_bg') . '; background-color: ' . get_option('ip_login_button_bg') . '; color: ' . get_option('ip_login_button_text') . '; }';
        echo '.login .button-primary:active { box-shadow: none; border-color: ' . get_option('ip_login_button_bg') . '; background-color: ' . get_option('ip_login_button_bg') . '; color: ' . get_option('ip_login_button_text') . '; }';
        echo '.login input[type="text"]:focus, .login input[type="password"]:focus { border-color: ' . get_option('ip_login_button_bg') . '; }';

        echo '.login h1 a { background-image: url(https://posterspy.com/wp-content/uploads/2014/12/posterspylogonew12.png) !important; background-size: 290px; width: auto; }';
        echo '.login label { color: ' . get_option('ip_login_box_text') . '; }';
        echo 'p#backtoblog { display: none; }';
        echo '.imagepress-login-footer { text-align: center; margin-top: 1em; color: #ffffff; }';
        echo '#reg_passmail, #resetpassform .indicator-hint { color: #ffffff; }';
        echo '.imagepress-login-footer a { color: #ffffff !important; }';
        echo '.imagepress-login-footer .avatar { vertical-align: sub; }';
        echo '.login a, .login a:hover, #nav a { color: ' . get_option('ip_login_page_text') . '; }';
        echo '#nav a { color: ' . get_option('ip_login_page_text') . ' !important; }';

        echo '@media only screen and (max-width: 480px) and (max-device-width: 480px) {
            body.login,
            .mobile.login {
                width: 100% !important;
                background: none !important;
                padding: 0 !important;
            }
            #login {
                width: 100%;
                height: 100%;
                border-radius: 0;
                margin: 0;
            }
        }
    .social-auth {
        color: #ffffff;
    }
    .social-auth-icon {
        display: block !important;
        margin: 8px 0 !important;
        padding: 4px;
        text-align: center;
    }
    .social-auth-icon:nth-child(2) {
        background-color: #55acee;
    }
    .social-auth-icon:nth-child(3) {
        background-color: #3b5998;
    }
    </style>';

    remove_action('login_head', 'wp_shake_js', 12);
}
function imagepress_admin_login_redirect( $redirect_to, $request, $user ) {
    global $user;
    if(isset($user->roles) && is_array($user->roles)) {
        if(in_array('administrator', $user->roles)) {
            return $redirect_to;
        } else {
            return home_url(); // customize this link
        }
    }
    else {
        return $redirect_to;
    }
}
function imagepress_login_checked_remember_me() {
    add_filter('login_footer', 'imagepress_rememberme_checked');
}
function imagepress_rememberme_checked() {
    echo '<script>document.getElementById("rememberme").checked = true;</script>';
}
function imagepress_login_footer() {
    //echo '<p>' . do_action( 'wordpress_social_login' )  . '</p>';
    //echo '<p>' . do_shortcode('[wordpress_social_login]')  . '</p>';
    echo '<p class="imagepress-login-footer">' . get_option('ip_login_copyright') . '</p>';
}
function imagepress_change_register_page_msg($message) {
    if(strpos($message, 'Register For This Site') == true) {
		$message = '<p class="message">' . __('Register for PosterSpy', 'imagepress') . '</p>';
	}

	return $message;
}

add_action('init', 'imagepress_login_checked_remember_me');
    
add_action('login_head', 'imagepress_login_head');
add_action('login_footer','imagepress_login_footer');
    
add_filter('login_headerurl', 'imagepress_login_logo_url');
add_filter('login_headertitle', 'imagepress_login_logo_url_title');
add_filter('login_errors', 'imagepress_login_error_override');
add_filter('login_redirect', 'imagepress_admin_login_redirect', 10, 3);
add_filter('login_message', 'imagepress_change_register_page_msg');



function ipGetBaseUri() {
    $currentPath = $_SERVER['PHP_SELF']; 
    $pathInfo = pathinfo($currentPath); 
    $hostName = $_SERVER['HTTP_HOST']; 
    $protocol = strtolower(substr($_SERVER['SERVER_PROTOCOL'], 0, 5)) == 'https://' ? 'https://' : 'http://';

	return $hostName;
}

function ip_custom_field($type, $label, $name, $show, $required = '') {
    if ((string) $show === 'upload') {
        if ((string) get_option($label) !== '') {
    		$out = '<p><input type="' . $type . '" id="' . $name . '" name="' . $name . '" placeholder="' . get_option($label) . '" ' . $required . '></p>';

		    return $out;
        }
    } else if ((string) $show === 'optionsave') {
        update_option($label, $_POST[$label]);
    } else if ((string) $show === 'optionview') {
        $out = '<p>
			<input type="text" name="' . $label . '" id="' . $label . '" value="' . get_option($label) . '" class="regular-text">
			<label for="' . $label . '">Custom field</label>
            <br><small>Leave blank to disable</small>
		</p>';

	    return $out;
    }
}
