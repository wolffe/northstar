<?php
function moon_ui_cpt_feed_ad() {
	$labels = [
		'name'                  => 'Feed Ads',
		'singular_name'         => 'Feed Ad',
		'menu_name'             => 'Feed Ads',
		'name_admin_bar'        => 'Feed Ad',
		'archives'              => 'Feed Ad Archives',
		'attributes'            => 'Feed Ad Attributes',
		'parent_item_colon'     => 'Parent Feed Ad:',
		'all_items'             => 'All Feed Ads',
		'add_new_item'          => 'Add New Feed Ad',
		'add_new'               => 'Add New',
		'new_item'              => 'New Feed Ad',
		'edit_item'             => 'Edit Feed Ad',
		'update_item'           => 'Update Feed Ad',
		'view_item'             => 'View Feed Ad',
		'view_items'            => 'View Feed Ads',
		'search_items'          => 'Search Feed Ad',
		'not_found'             => 'Not found',
		'not_found_in_trash'    => 'Not found in Trash',
		'featured_image'        => 'Featured Image',
		'set_featured_image'    => 'Set featured image',
		'remove_featured_image' => 'Remove featured image',
		'use_featured_image'    => 'Use as featured image',
		'insert_into_item'      => 'Insert into feed ad',
		'uploaded_to_this_item' => 'Uploaded to this feed ad',
		'items_list'            => 'Feed ads list',
		'items_list_navigation' => 'Feed ads list navigation',
		'filter_items_list'     => 'Filter feed ads list',
	];
	$args = [
		'label'                 => 'Feed Ad',
		'description'           => 'Feed Ad',
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
