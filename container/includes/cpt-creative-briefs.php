<?php
// Register Custom Post Type
function moon_ui_cpt_creative_brief() {

	$labels = array(
		'name'                  => _x( 'Creative Briefs', 'Post Type General Name', 'moon-ui' ),
		'singular_name'         => _x( 'Creative Brief', 'Post Type Singular Name', 'moon-ui' ),
		'menu_name'             => __( 'Creative Briefs', 'moon-ui' ),
		'name_admin_bar'        => __( 'Creative Brief', 'moon-ui' ),
		'archives'              => __( 'Creative Brief Archives', 'moon-ui' ),
		'attributes'            => __( 'Creative Brief Attributes', 'moon-ui' ),
		'parent_item_colon'     => __( 'Parent Creative Brief:', 'moon-ui' ),
		'all_items'             => __( 'All Creative Briefs', 'moon-ui' ),
		'add_new_item'          => __( 'Add New Creative Brief', 'moon-ui' ),
		'add_new'               => __( 'Add New', 'moon-ui' ),
		'new_item'              => __( 'New Creative Brief', 'moon-ui' ),
		'edit_item'             => __( 'Edit Creative Brief', 'moon-ui' ),
		'update_item'           => __( 'Update Creative Brief', 'moon-ui' ),
		'view_item'             => __( 'View Creative Brief', 'moon-ui' ),
		'view_items'            => __( 'View Creative Briefs', 'moon-ui' ),
		'search_items'          => __( 'Search Creative Brief', 'moon-ui' ),
		'not_found'             => __( 'Not found', 'moon-ui' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'moon-ui' ),
		'featured_image'        => __( 'Featured Image', 'moon-ui' ),
		'set_featured_image'    => __( 'Set featured image', 'moon-ui' ),
		'remove_featured_image' => __( 'Remove featured image', 'moon-ui' ),
		'use_featured_image'    => __( 'Use as featured image', 'moon-ui' ),
		'insert_into_item'      => __( 'Insert into creative brief', 'moon-ui' ),
		'uploaded_to_this_item' => __( 'Uploaded to this creative brief', 'moon-ui' ),
		'items_list'            => __( 'Creative briefs list', 'moon-ui' ),
		'items_list_navigation' => __( 'Creative briefs list navigation', 'moon-ui' ),
		'filter_items_list'     => __( 'Filter creative briefs list', 'moon-ui' ),
	);
	$args = array(
		'label'                 => __( 'Creative Brief', 'moon-ui' ),
		'description'           => __( 'Creative Brief', 'moon-ui' ),
		'labels'                => $labels,
		'supports'              => array( 'title', 'editor', 'excerpt', 'thumbnail', 'custom-fields', 'page-attributes', 'post-formats', ),
		'hierarchical'          => true,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 20,
		'menu_icon'             => 'dashicons-clipboard',
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => true,		
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'capability_type'       => 'page',
		'show_in_rest'          => true,
	);
	register_post_type( 'creative-brief', $args );

}
add_action( 'init', 'moon_ui_cpt_creative_brief', 0 );
