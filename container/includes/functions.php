<?php
/**
  * Customise Pages columns.
  *
  * Customises column in WordPress Pages section
  *
  * @author Ciprian Popescu <getbutterfly@gmail.com>
  *
  * @since 3.0.0
  *
  */

add_filter('manage_pages_columns', 'page_column_views');
add_action('manage_pages_custom_column', 'page_custom_column_views', 5, 2);

function page_column_views($defaults) {
    // Remove unused columns
    unset($defaults['author']);
    unset($defaults['comments']);

    // Add custom columns
    $defaults['page-layout'] = 'Page Template';

    return $defaults;
}
function page_custom_column_views($column_name, $id) {
    if ($column_name === 'page-layout') {
        $set_template = get_post_meta(get_the_ID(), '_wp_page_template', true);
        if ($set_template == 'default') {
            echo 'Default';
        }
        $templates = get_page_templates();
        ksort($templates);
        foreach (array_keys($templates) as $template) {
            if ($set_template == $templates[$template]) {
                echo $template;
            }
        }
    }
}

/**
  * Remove default page author and comments.
  *
  * Removes and disables support for author and comments for Pages
  *
  * @author Ciprian Popescu <getbutterfly@gmail.com>
  *
  * @since 3.0.0
  *
  */
add_action('admin_init', 'moon_deactivate_support');

function moon_deactivate_support() {
    remove_post_type_support('page', 'comments');
    remove_post_type_support('page', 'author');
}

/**
  * Check if page has been opened via AJAX call
  *
  * @author Ciprian Popescu <getbutterfly@gmail.com>
  *
  * @since 3.0.0
  *
  */
function ip_is_ajax() {
    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
}
