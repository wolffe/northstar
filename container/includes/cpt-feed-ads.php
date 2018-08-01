<?php
// Register Custom Post Type
function moon_ui_cpt_feed_ad() {

	$labels = [
		'name'                  => _x( 'Feed Ads', 'Post Type General Name', 'moon-ui' ),
		'singular_name'         => _x( 'Feed Ad', 'Post Type Singular Name', 'moon-ui' ),
		'menu_name'             => __( 'Feed Ads', 'moon-ui' ),
		'name_admin_bar'        => __( 'Feed Ad', 'moon-ui' ),
		'archives'              => __( 'Feed Ad Archives', 'moon-ui' ),
		'attributes'            => __( 'Feed Ad Attributes', 'moon-ui' ),
		'parent_item_colon'     => __( 'Parent Feed Ad:', 'moon-ui' ),
		'all_items'             => __( 'All Feed Ads', 'moon-ui' ),
		'add_new_item'          => __( 'Add New Feed Ad', 'moon-ui' ),
		'add_new'               => __( 'Add New', 'moon-ui' ),
		'new_item'              => __( 'New Feed Ad', 'moon-ui' ),
		'edit_item'             => __( 'Edit Feed Ad', 'moon-ui' ),
		'update_item'           => __( 'Update Feed Ad', 'moon-ui' ),
		'view_item'             => __( 'View Feed Ad', 'moon-ui' ),
		'view_items'            => __( 'View Feed Ads', 'moon-ui' ),
		'search_items'          => __( 'Search Feed Ad', 'moon-ui' ),
		'not_found'             => __( 'Not found', 'moon-ui' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'moon-ui' ),
		'featured_image'        => __( 'Featured Image', 'moon-ui' ),
		'set_featured_image'    => __( 'Set featured image', 'moon-ui' ),
		'remove_featured_image' => __( 'Remove featured image', 'moon-ui' ),
		'use_featured_image'    => __( 'Use as featured image', 'moon-ui' ),
		'insert_into_item'      => __( 'Insert into feed ad', 'moon-ui' ),
		'uploaded_to_this_item' => __( 'Uploaded to this feed ad', 'moon-ui' ),
		'items_list'            => __( 'Feed ads list', 'moon-ui' ),
		'items_list_navigation' => __( 'Feed ads list navigation', 'moon-ui' ),
		'filter_items_list'     => __( 'Filter feed ads list', 'moon-ui' ),
	];
	$args = [
		'label'                 => __( 'Feed Ad', 'moon-ui' ),
		'description'           => __( 'Feed Ad', 'moon-ui' ),
		'labels'                => $labels,
		'supports'              => ['title', 'editor', 'thumbnail', 'custom-fields'],
		'hierarchical'          => true,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 20,
		'menu_icon'             => 'dashicons-pressthis',
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => true,		
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'capability_type'       => 'post',
		'show_in_rest'          => true,
	];
	register_post_type( 'feed-ad', $args );

}
add_action( 'init', 'moon_ui_cpt_feed_ad', 0 );
