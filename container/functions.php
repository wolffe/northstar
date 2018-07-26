<?php
/*
 * Additional Moon UI functions
 */

function remove_redirect_guess_404_permalink($redirect_url) {
    if (is_404()) {
        return false;
    }

    return $redirect_url;
}
add_filter('redirect_canonical', 'remove_redirect_guess_404_permalink');
remove_filter('template_redirect', 'redirect_canonical');

// Custom post types
require get_template_directory() . '/includes/cpt-creative-briefs.php';
require get_template_directory() . '/includes/cpt-feed-ads.php';

// Custom functions
require get_template_directory() . '/includes/functions.php';

function whiskey_load_assets() {
    wp_enqueue_style('google-fonts', 'https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700|Montserrat:400,500,700');

    wp_enqueue_style('posterspy', 'https://posterspy.com/wp-content/themes/moon-ui-theme/css/main.css');
    wp_enqueue_style('posterspy-lightbox', 'https://posterspy.com/wp-content/themes/moon-ui-theme/css/lightbox.css');
    wp_enqueue_style('posterspy-mobile', 'https://posterspy.com/wp-content/themes/moon-ui-theme/css/mobile.css');
    wp_enqueue_style('posterspy-feed', 'https://posterspy.com/wp-content/themes/moon-ui-theme/css/feed.css');

    wp_enqueue_style('posterspy-ui', 'https://posterspy.com/wp-content/themes/moon-ui-theme/css/ui.css');

    wp_enqueue_script('sweetalert2', 'https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.15.1/sweetalert2.min.js', '', '7.15.1', true);
    wp_enqueue_style('sweetalert2', 'https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.15.1/sweetalert2.min.css');

    wp_enqueue_script('resize-sensor', 'https://posterspy.com/wp-content/themes/moon-ui-theme/js/resize-sensor.js', '', '3.3.1', true);
    wp_enqueue_script('sticky-sidebar', 'https://posterspy.com/wp-content/themes/moon-ui-theme/js/sticky-sidebar.min.js', array('resize-sensor'), '3.3.1', true);
    wp_enqueue_script('noir', 'https://posterspy.com/wp-content/themes/moon-ui-theme/js/engine-0.2.js', array('sticky-sidebar', 'jquery'), '0.2.0', true);
}
add_action('wp_enqueue_scripts', 'whiskey_load_assets');

register_sidebar(array(
    'name'          => 'Responsive Menu Widget 1',
    'id'            => 'responsive-menu-widget-1',
    'description'   => 'Appears in the responsive/mobile menu area, above the menu.',
    'before_widget' => '<aside id="%1$s" class="widget %2$s">',
    'after_widget'  => '</aside>',
    'before_title'  => '<h2 class="widget-title">',
    'after_title'   => '</h2>',
));
register_sidebar(array(
    'name'          => 'Responsive Menu Widget 2',
    'id'            => 'responsive-menu-widget-2',
    'description'   => 'Appears in the responsive/mobile menu area, below the menu.',
    'before_widget' => '<aside id="%1$s" class="widget %2$s">',
    'after_widget'  => '</aside>',
    'before_title'  => '<h2 class="widget-title">',
    'after_title'   => '</h2>',
));





add_action('after_setup_theme', 'blankslate_setup');
function blankslate_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('advanced-image-compression');

    add_theme_support('html5', array(
        'search-form', 'comment-form', 'comment-list', 'gallery', 'caption'
    ));

    global $content_width;
    if (!isset($content_width)) $content_width = 920;

    register_nav_menus(array(
        'main-menu' => 'Main Menu',
        'footer-menu' => 'Footer Menu',
        'responsive-menu' => 'Responsive Menu',
    ));
}


add_action('comment_form_before', 'blankslate_enqueue_comment_reply_script');
function blankslate_enqueue_comment_reply_script() {
    if (get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}


add_action('widgets_init', 'blankslate_widgets_init');

function blankslate_widgets_init() {
    register_sidebar(array(
        'name' => __( 'Sidebar Widget Area', 'blankslate' ),
        'id' => 'primary-widget-area',
        'before_widget' => '<div id="%1$s" class="widget-container %2$s">',
        'after_widget' => "</div>",
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ));
}



function noir_comments($comment, $args, $depth) {
    $GLOBALS['comment'] = $comment;
    switch($comment->comment_type) :
        case 'pingback' :
        case 'trackback' :
        if('div' == $args['style']) {
            $tag = 'div';
            $add_below = 'comment';
        }
        else {
            $tag = 'li';
            $add_below = 'div-comment';
        }
        ?>
        <li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">
            <p><?php _e('Pingback:', 'noir'); ?> <?php comment_author_link(); ?> <?php edit_comment_link( __( '(Edit)', 'noir' ), '<span class="edit-link">', '</span>' ); ?></p>
    <?php
            break;
        default :
        global $post;
    ?>
    <li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
        <?php //echo $comment->comment_post_ID; ?>
        <article id="div-comment-<?php comment_ID(); ?>" class="comment-body">
                <?php //if(0 != $args['avatar_size']) echo get_avatar($comment->user_id, 64); ?>
                <footer class="comment-meta">
                    <div class="comment-author vcard">
                        <?php
                        if($comment->user_id) {
                            $user = get_userdata($comment->user_id);
                            $display_name = $user->display_name;

                            if(get_the_author_meta('user_title', $comment->user_id) == 'Verified')
                                $verified = ' <span class="teal hint hint--right" data-hint="' . get_option('cms_verified_profile') . '"><i class="fa fa-check-circle"></i></span>';
                            else
                                $verified = '';

                            $linkie = get_avatar($comment->user_id, 24) . '<b><a href="' . get_author_posts_url($comment->user_id) . '">' . $display_name . '</a> ' . $verified . '</b> <time>' . human_time_diff(get_comment_time('U'), current_time('timestamp')) . ' ago</time>';
                        }
                        else {
                            $linkie = get_avatar($comment->user_id, 24) . '<b><a href="' . $comment->comment_author_url . '" rel="external nofollow">' . $comment->comment_author . '</a></b> <time>' . human_time_diff(get_comment_time('U'), current_time('timestamp')) . ' ago</time>';
                        }
                        ?>

                        <?php echo $linkie; ?>
                    </div><!-- .comment-author -->

                    <?php if('0' == $comment->comment_approved) : ?>
                    <p class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'pietergoosen' ); ?></p>
                    <?php endif; ?>
                </footer><!-- .comment-meta -->

                <div class="comment-content">
                    <?php comment_text(); ?>
                    <p>
                        <small>
                            <?php comment_reply_link(array_merge($args, array('add_below' => 'div-comment', 'depth' => $depth, 'max_depth' => $args['max_depth']))); ?>
                        </small>
                    </p>
                </div><!-- .comment-content -->
            </article><!-- .comment-body -->
    <?php
        break;
    endswitch;
}


/**
 * Adds a box to the main column on the Post and Page edit screens.
 */
function imagepress_add_meta_box() {
    $screens = array('poster');

    foreach($screens as $screen) {
        add_meta_box('imagepress_sectionid', 'Staff Comment', 'imagepress_meta_box_callback', $screen);
    }
}
add_action('add_meta_boxes', 'imagepress_add_meta_box');

function imagepress_meta_box_callback($post) {
    wp_nonce_field('imagepress_meta_box', 'imagepress_meta_box_nonce');
    $value = get_post_meta( $post->ID, '_comment_value_key', true );

    echo '<label for="imagepress_new_field">Add a short staff comment</label><br>';
    echo '<input type="text" id="imagepress_new_field" name="imagepress_new_field" value="' . esc_attr($value) . '" size="60">';
}

function imagepress_save_meta_box_data($post_id) {
    if(!isset($_POST['imagepress_meta_box_nonce'])) {
        return;
    }
    if(!wp_verify_nonce($_POST['imagepress_meta_box_nonce'], 'imagepress_meta_box')) {
        return;
    }
    if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    if(isset($_POST['post_type']) && 'page' == $_POST['post_type']) {
        if(!current_user_can('edit_page', $post_id)) {
            return;
        }
    }
    else {
        if(!current_user_can('edit_post', $post_id)) {
            return;
        }
    }

    if(!isset($_POST['imagepress_new_field'])) {
        return;
    }

    $my_data = sanitize_text_field($_POST['imagepress_new_field']);

    update_post_meta($post_id, '_comment_value_key', $my_data);
}
add_action('save_post', 'imagepress_save_meta_box_data');

// custom width box
function box_ps($atts, $content = null) {
    extract(shortcode_atts(array(
        'width' => 650,
    ), $atts));
    return '<div class="box-ps" style="width:' . $width . 'px; margin:0 auto;">' . do_shortcode($content) . '</div>';
}
add_shortcode('box', 'box_ps');

// custom brief boxes
function box_brief_title($atts, $content = null) {
    extract(shortcode_atts(array(
        'width' => 650,
    ), $atts));
    return '<div class="box-brief-title">' . do_shortcode($content) . '<div style="float:right;">' . get_the_post_thumbnail() . '</div><div style="clear:both;"></div></div>';
}
function box_brief($atts, $content = null) {
    extract(shortcode_atts(array(
        'background' => '#222222',
    ), $atts));
    return '<div class="box-brief" style="background-color: ' . $background . ';">' . do_shortcode($content) . '</div>';
}
function box_date($atts, $content = null) {
    extract(shortcode_atts(array(
        'background' => '#333333',
    ), $atts));
    return '<div class="box-date" style="background-color: ' . $background . ';"><div style="width: 75%; float: left;">' . do_shortcode($content) . '</div><div style="width: 25%; float: right; font-size: 90px; color: #249ee5; text-align: center;"><i class="fa fa-calendar"></i></div><div style="clear:both;"></div></div>';
}
function box_submit($atts, $content = null) {
    extract(shortcode_atts(array(
        'background' => '#444444',
    ), $atts));
    return '<div class="box-submit" style="background-color: ' . $background . ';"><div style="width: 75%; float: left;">' . do_shortcode($content) . '</div><div style="width: 25%; float: right; font-size: 90px; color: #249ee5; text-align: center;"><i class="fa fa-paper-plane"></i></div><div style="clear:both;"></div></div>';
}
function box_prizes($atts, $content = null) {
    extract(shortcode_atts(array(
        'background' => '#555555',
    ), $atts));
    return '<div class="box-prizes" style="background-color: ' . $background . ';"><div style="width: 75%; float: left;">' . do_shortcode($content) . '</div><div style="width: 25%; float: right; font-size: 90px; color: #249ee5; text-align: center;"><i class="fa fa-trophy"></i></div><div style="clear:both;"></div></div>';
}
function box_about($atts, $content = null) {
    extract(shortcode_atts(array(
        'background' => '#666666',
    ), $atts));
    return '<div class="box-about" style="background-color: ' . $background . ';">' . do_shortcode($content) . '<div style="clear:both;"></div></div>';
}

add_shortcode('brief-title', 'box_brief_title');
add_shortcode('brief', 'box_brief');
add_shortcode('date', 'box_date');
add_shortcode('submit', 'box_submit');
add_shortcode('prizes', 'box_prizes');
add_shortcode('about', 'box_about');

function button_ps($atts, $content = null) {
    extract(shortcode_atts(array(
        'type' => 'upload',
        'message' => 'UPLOAD YOUR ENTRY',
    ), $atts));

    if($type == 'upload') {
        return '<a class="button-ps" style="background-color: #0cb8fc; color: #ffffff; margin: 16px auto; padding: 16px; font-size: 24px; text-align: center; width: 356px; display: block; font-family: Montserrat;" href="//posterspy.com/upload/">' . $message . '</a>';
    }
}

add_shortcode('button', 'button_ps');




if (!function_exists('t5_do_not_ask_for_comment_log_in')) {
    add_filter('comment_reply_link', 't5_do_not_ask_for_comment_log_in');

    function t5_do_not_ask_for_comment_log_in($link) {
        if(empty($GLOBALS['user_ID']) && get_option('comment_registration')) {
            return '<a href="//posterspy.com/login/">Log in to reply</a>';
        }

        return $link;
    }
}




function the_about_the_author_box() {
    $name = get_the_author_meta('display_name');
    $bio = get_the_author_meta('user_description');
    $email = get_the_author_meta('user_email');
    $user_url = get_the_author_meta('user_url');
    $user_facebook = get_the_author_meta('facebook');
    $user_twitter = get_the_author_meta('twitter');
    $user_googleplus = get_the_author_meta('googleplus');

    $output = '<div class="aboutbox-container" style="border-top: 1px solid #eeeeee; border-bottom: 1px solid #eeeeee; padding: 24px 0; margin: 24px 0;">
        <div class="aboutbox-left" style="float: left; text-align: left; padding: 0 16px 0 0;">';
            $output .= get_avatar($email, 48);
        $output .= '</div>
        <div class="aboutbox-right" style="margin: 0 0 0 64px;">
            <h3 class="aboutbox-title" style="margin: 0;">' . $name . '</h3>
            <p class="aboutbox-bio">' . $bio . '</p>
            <p style="font-size: 160%;">';
                if(!empty($user_url))
                    $output .= '<a href="' . $user_url  . '"><i class="fa fa-fw fa-link"></i></a>';
                if(!empty($user_facebook))
                    $output .= '<a href="' . $user_facebook  . '"><i class="fa fa-fw fa-facebook-square"></i></a>';
                if(!empty($user_twitter))
                    $output .= '<a href="//twitter.com/' . $user_twitter  . '"><i class="fa fa-fw fa-twitter-square"></i></a>';
                if(!empty($user_googleplus))
                    $output .= '<a href="//plus.google.com/' . $user_googleplus  . '"><i class="fa fa-fw fa-google-plus-square"></i></a>';
            $output .= '</p>
        </div>
        <div style="clear: both;"></div>
    </div>';

    echo $output; // Display the box!
}







// Register Banners
function carousel_ps() {
    $labels = array(
        'name'                => _x( 'Banners', 'Post Type General Name', 'noir' ),
        'singular_name'       => _x( 'Banner', 'Post Type Singular Name', 'noir' ),
        'menu_name'           => __( 'Banners', 'noir' ),
        'parent_item_colon'   => __( 'Parent Banner:', 'noir' ),
        'all_items'           => __( 'All Banners', 'noir' ),
        'view_item'           => __( 'View Banner', 'noir' ),
        'add_new_item'        => __( 'Add New Banner', 'noir' ),
        'add_new'             => __( 'Add New', 'noir' ),
        'edit_item'           => __( 'Edit Banner', 'noir' ),
        'update_item'         => __( 'Update Banner', 'noir' ),
        'search_items'        => __( 'Search Banner', 'noir' ),
        'not_found'           => __( 'Not found', 'noir' ),
        'not_found_in_trash'  => __( 'Not found in Trash', 'noir' ),
    );
    $args = array(
        'label'               => __( 'home_banner', 'noir' ),
        'description'         => __( 'Homepage static banner', 'noir' ),
        'labels'              => $labels,
        'supports'            => array( 'title', 'editor', 'thumbnail', ),
        'hierarchical'        => false,
        'public'              => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_nav_menus'   => false,
        'show_in_admin_bar'   => false,
        'menu_position'       => 20,
        'menu_icon'           => 'dashicons-slides',
        'can_export'          => true,
        'has_archive'         => false,
        'exclude_from_search' => true,
        'publicly_queryable'  => true,
        'rewrite'             => false,
        'capability_type'     => 'post',
    );
    register_post_type( 'home_banner', $args );
}

// Hook into the 'init' action
add_action('init', 'carousel_ps', 0);

add_filter('body_class','noir_body_classes');
function noir_body_classes($c) {
    wp_is_mobile() ? $c[] = 'mobile' : 'desktop';

    return $c;
}


/**
 * Usage: if(function_exists('chip_pagination')) chip_pagination();
 */
function chip_pagination($pages = '', $range = 4) {
    $showitems = ($range * 2) + 1;
    global $paged;
    if(empty($paged)) $paged = 1;

    if($pages == '') {
        global $wp_query;
        $pages = $wp_query->max_num_pages;
        if(!$pages) {
            $pages = 1;
        }
    }

    if(1 != $pages) {
        echo '<div class="noir-pagination"><span>'.__('Page').' '.$paged.' '.__('of').' '.$pages.'</span>';
        if($paged > 2 && $paged > $range+1 && $showitems < $pages) echo '<a href="'.get_pagenum_link(1).'">&laquo; '.__('First').'</a>';
        if($paged > 1 && $showitems < $pages) echo '<a href="'.get_pagenum_link($paged - 1).'">&lsaquo; '.__('Previous').'</a>';

        for($i=1; $i <= $pages; $i++) {
            if(1 != $pages &&(!($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems))
                echo ($paged == $i)? '<span class="noir-current">'.$i.'</span>':'<a href="'.get_pagenum_link($i).'" class="inactive">'.$i.'</a>';
        }

        if($paged < $pages && $showitems < $pages) echo '<a href="'.get_pagenum_link($paged + 1).'">'.__('Next').' &rsaquo;</a>';
        if($paged < $pages-1 &&  $paged+$range-1 < $pages && $showitems < $pages) echo '<a href="'.get_pagenum_link($pages).'">'.__('Last').' &raquo;</a>';
        echo '<div class="clear"></div>';
        echo "</div>\n";
    }
}

/**
 * ImagePress taxonomy filters
 *
 * Customises taxonomy properties
 *
 * @package ImagePress
 * @subpackage Template
 * @since 6.0.0-posterspy
 */
function customize_poster_taxonomy($query) {
    if(get_post_type() == 'poster') {
        if(isset($_GET['sort']) || isset($_GET['range'])) {
            $sort = $_GET['sort'];
            $range = $_GET['range'];
            if($sort == 'likes') {
                $query->set('meta_key', 'votes_count');
                $query->set('orderby', 'meta_value_num');
                $query->set('order', 'DESC');
            } else if($sort == 'views') {
                $query->set('meta_query', array( 'key' => 'post_views_count') );
                $query->set('meta_key', 'post_views_count');
                $query->set('orderby', 'meta_value_num');
                $query->set('order', 'DESC');
            } else if($sort == 'comments') {
                $query->set('orderby', 'comment_count');
                $query->set('order', 'DESC');
            } else if($sort == 'newest') {
                $query->set('orderby', 'date');
                $query->set('order', 'DESC');
            } else if($sort == 'oldest') {
                $query->set('orderby', 'date');
                $query->set('order', 'ASC');
            } else if($sort == 'prints') {
                $meta_query = array(
                    array(
                        'key' => 'imagepress_purchase',
                        'value' => '',
                        'compare' => '!='
                    )
                );
                $query->set('meta_query', $meta_query);
            } else {
                // Sorting defaults to newest posters
                $query->set('orderby', 'date');
                $query->set('order', 'DESC');
            }

            // Range filtering
            if($range == 'lastmonth') {
                $date_query = array(
                    'date_query'    => array(
                        'column'  => 'post_date',
                        'after'   => '- 30 days'
                    )
                );
                $query->set('date_query', $date_query);
            } else if($range == 'lastweek') {
                $date_query = array(
                    'date_query'    => array(
                        'column'  => 'post_date',
                        'after'   => '- 7 days'
                    )
                );
                $query->set('date_query', $date_query);
            } else if($range == 'lastday') {
                $date_query = array(
                    'date_query'    => array(
                        'column'  => 'post_date',
                        'after'   => '- 1 days'
                    )
                );
                $query->set('date_query', $date_query);
            } else if($range == 'alltime') {
            } else {
                // Sorting defaults to newest posters
                $query->set('orderby', 'date');
                $query->set('order', 'DESC');
            }
            // Category filter
            // $query->set( 'cat', '-4' );
        }

        if(is_tax()) {
            $query->set('tax_query', array(
                array(
                    'taxonomy' => get_query_var('taxonomy'),
                    'field' => 'slug',
                    'terms' => get_query_var('term'),
                )
            ));
        }

        if(is_archive() || is_tax()) {
            if(isset($_COOKIE['psppp'])) {
                $posters = $_COOKIE['psppp'];

                if($posters == 1) {
                    $query->set( 'posts_per_page', '44' ); // 6x7=42, 7x9=63, 7x13=91
                } else if($posters == 2) {
                    $query->set( 'posts_per_page', '64' ); // 6x7=42, 7x9=63, 7x13=91
                } else if($posters == 3) {
                    $query->set( 'posts_per_page', '100' ); // 6x7=42, 7x9=63, 7x13=91
                }
            } else {
                $query->set( 'posts_per_page', '44' ); // 6x7=42, 7x9=63, 7x13=91
            }

            // Checkboxes
            if(isset($_COOKIE['pspurchase']) && $_COOKIE['pspurchase'] == 1) {
                $meta_query = array(
                    array(
                        'key' => 'imagepress_purchase',
                        'value' => '',
                        'compare' => '!='
                    )
                );
                $query->set( 'meta_query', $meta_query );
            }
            //
        }
    }
}
add_action('pre_get_posts', 'customize_poster_taxonomy', 20);

function change_wp_search_size($queryVars) {
    if(isset($_REQUEST['s'])) {
        if(isset($_COOKIE['psppp'])) {
            $posters = $_COOKIE['psppp'];

            if($posters == 1) {
                $queryVars['posts_per_page'] = 44; // 6x7=42, 7x9=63, 7x13=91
            } else if($posters == 2) {
                $queryVars['posts_per_page'] = 64; // 6x7=42, 7x9=63, 7x13=91
            } else if($posters == 3) {
                $queryVars['posts_per_page'] = 100; // 6x7=42, 7x9=63, 7x13=91
            }
        } else {
            $queryVars['posts_per_page'] = 44; // 6x7=42, 7x9=63, 7x13=91
        }
    }

    return $queryVars;
}
add_filter('request', 'change_wp_search_size');

function build_sort_filters($type) {
    if(isset($_GET['range'])) {
        $url = '?sort=' . $type . '&range=' . $_GET['range'];
    } else {
        $url = '?sort=' . $type . '&range=alltime';
    }

    return preg_replace("/(\/page\/)\w+/", "", strtok($_SERVER["REQUEST_URI"], '?') . $url);
}

function build_range_filters($type) {
    if(isset($_GET['sort'])) {
        $url = '?sort=' . $_GET['sort'] . '&range=' . $type;
    } else {
        $url = '?sort=newest&range=' . $type;
    }

    return preg_replace("/(\/page\/)\w+/", "", strtok($_SERVER["REQUEST_URI"], '?') . $url);
}

function getDiscoverFilters() {
    $out = '';

    if(is_tax()) {
        $term = get_query_var('term');
        $out .= '<div class="poster-taxonomy-details" data-term="' . $term . '"></div>';
    }

    $out .= '<div class="poster-filters">
        <div class="ip-sorter-primary">
            <div class="cinnamon-dropdown">
                <button class="dropbtn dropsort">Sort posters by <i class="fa fa-chevron-down"></i></button>
                <div class="cinnamon-dropdown-content">
                    <a href="' . build_sort_filters('comments') . '">Most Commented</a>
                    <a href="' . build_sort_filters('views') . '">Most Viewed</a>
                    <a href="' . build_sort_filters('likes') . '">Most Loved</a>
                    <a href="' . build_sort_filters('newest') . '">Newest</a>
                    <a href="' . build_sort_filters('oldest') . '">Oldest</a>
                </div>
            </div>

            <div class="cinnamon-dropdown">
                <button class="dropbtn droprange">All time <i class="fa fa-chevron-down"></i></button>
                <div class="cinnamon-dropdown-content">
                    <a href="' . build_range_filters('lastday') . '">Today</a>
                    <a href="' . build_range_filters('lastweek') . '">This week</a>
                    <a href="' . build_range_filters('lastmonth') . '">This month</a>
                    <a href="' . build_range_filters('alltime') . '">All time</a>
                </div>
            </div>

            <form class="search">
                <input type="search" name="s" placeholder="Search posters...">
                <input type="hidden" name="post_type" value="poster">
            </form>
        </div>

        <div class="ip-sorter-secondary">
            <a href="https://posterspy.com/posters/" class="term-all">All</a>
            <a href="https://posterspy.com/genre/movies/" class="term-movies">Movies</a>
            <a href="https://posterspy.com/genre/tv-shows/" class="term-tv-shows">TV Shows</a>
            <a href="https://posterspy.com/genre/video-games/" class="term-video-games">Video Games</a>
            <a href="https://posterspy.com/genre/comics/" class="term-comics">Comics</a>
            <a href="https://posterspy.com/genre/music/" class="term-music">Music</a>
            <a href="https://posterspy.com/genre/staffpicks/" class="term-staffpicks">Staff Picks</a>

            <div class="cinnamon-dropdown-options">
                <button class="dropbtn-options"><i class="fa fa-cog"></i></button>
                <div class="cinnamon-dropdown-options-content">
                    <div class="cinnamon-dropdown-title">Posters per page</div>
                    <a href="#" id="ppp1"><i class="fa fa-circle"></i><i class="fa fa-circle-o"></i><i class="fa fa-circle-o"></i></a>
                    <a href="#" id="ppp2"><i class="fa fa-circle"></i><i class="fa fa-circle"></i><i class="fa fa-circle-o"></i></a>
                    <a href="#" id="ppp3"><i class="fa fa-circle"></i><i class="fa fa-circle"></i><i class="fa fa-circle"></i></a>
                </div>
            </div>
        </div>

        <div class="ip-sorter-tertiary">
            <input type="checkbox" id="showpurchase" name="showpurchase" value="1"> <label for="showpurchase">Only show prints for sale</label>
        </div>
    </div>';

    return $out;
}

/*
 * Limit access to WordPress
 *
 * Limit access to non-admin users to WordPress backend.
 */
add_action('init', 'moon_ui_blockusers_init');
function moon_ui_blockusers_init() {
    if (is_admin() && !current_user_can('administrator') && !(defined('DOING_AJAX') && DOING_AJAX)) {
        wp_redirect(home_url());
        exit;
    }
}
