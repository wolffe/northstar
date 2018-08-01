<?php
function cinnamon_count_user_posts_by_type($userid, $post_type = 'post') {
    global $wpdb;

    $ip_slug = get_option('ip_slug');

    $where = get_posts_by_author_sql($ip_slug, true, $userid);
    $count = $wpdb->get_var("SELECT COUNT(*) FROM $wpdb->posts $where");

    return apply_filters('get_usernumposts', $count, $userid);
}

function cinnamon_PostViews($id, $count = true) {
    $axCount = get_user_meta($id, 'ax_post_views', true);
    if ($axCount == '')
        $axcount = 0;

    if ($count == true) {
        $axCount++;
        update_user_meta($id, 'ax_post_views', $axCount);
    }

    return $axCount;
}

function cinnamon_author_base() {
    global $wp_rewrite;

    $cinnamon_author_slug = get_option('cinnamon_author_slug');
    $author_slug = $cinnamon_author_slug; // change slug name
    $wp_rewrite->author_base = $author_slug;
}

function cinnamon_get_related_author_posts($author) {
    global $post;

    $ip_slug = get_option('ip_slug');
    $authors_posts = get_posts([
        'author' => $author,
        'posts_per_page' => 9,
        'post_type' => $ip_slug
    ]);

    $output = '';
    if ($authors_posts) {
        $output .= '<div class="cinnamon-grid"><ul>';
            foreach ($authors_posts as $authors_post) {
                $output .= '<li><a href="' . get_permalink($authors_post->ID) . '">' . get_the_post_thumbnail($authors_post->ID, 'thumbnail') . '</a></li>';
            }
        $output .= '</ul></div>';
    }

    return $output;
}

function cinnamon_extra_contact_info($contactmethods) {
    unset($contactmethods['aim']);
    unset($contactmethods['yim']);
    unset($contactmethods['jabber']);

    $contactmethods['facebook'] = 'Facebook';
    $contactmethods['twitter'] = 'Twitter';
    $contactmethods['instagram'] = 'Instagram';
    $contactmethods['linkedin'] = 'LinkedIn';

    return $contactmethods;
}

function cinnamon_get_portfolio_posts($author, $count, $size = 'thumbnail') {
    global $post;

    $ip_slug = get_option('ip_slug');
    $authors_posts = get_posts([
        'author' => $author,
        'post_type' => $ip_slug,
        'posts_per_page' => $count,
        'meta_key' => 'imagepress_sticky',
        'meta_value' => 1,
    ]);

    $output = '';
    if($authors_posts) {
        $output .= '<div id="cinnamon-index"><a href="#"><i class="fa fa-th-large"></i> ' . get_option('cinnamon_label_index') . '</a></div>
        <div id="cinnamon-feature"></div>
        <div class="cinnamon-grid-blank">';
            foreach ($authors_posts as $authors_post) {
                $post_thumbnail_id = get_post_thumbnail_id($authors_post->ID);
                $post_thumbnail_url = wp_get_attachment_url( $post_thumbnail_id );
                $output .= '<a href="#" rel="' . $post_thumbnail_url . '">' . get_the_post_thumbnail($authors_post->ID, $size) . '</a>';
            }
        $output .= '</div>';
    }

    return $output;
}

function user_query_count_post_type($args) {
    $args->query_from = str_replace("post_type = 'post' AND", "post_type IN ('" . get_option('ip_slug') . "') AND ", $args->query_from);
}

/* CINNAMON CARD SHORTCODE */
function cinnamon_card($atts, $content = null) {
    extract(shortcode_atts([
        'author' => '',
        'count' => 99999,
        'sort' => 0
    ], $atts));

    global $post;

    $ip_slug = get_option('ip_slug');

    if (empty($author))
        $author = get_current_user_id();

    $display = '';

    add_action('pre_user_query', 'user_query_count_post_type', 1);
    $hub_users = get_users(['number' => $count, 'order' => 'DESC', 'orderby' => 'post_count']);

    $display .= '<div id="author-cards">';

    $display .= '<div class="cinnamon-sortable">
        <div class="innersort">
            <h4>Sort</h4>
            <span class="sort" data-sort="name"><i class="fa fa-circle fa-fw"></i> A-Z</span>
            <span class="sort initial" data-sort="uploads" data-order="desc"><i class="fa fa-circle fa-fw"></i> Most uploads</span>
            <span class="sort" data-sort="followers" data-order="desc"><i class="fa fa-circle fa-fw"></i> Most followers</span>
        </div>
        <div class="innersort">
			<h4>' . get_option('ip_author_find_title') . '</h4>
			<input type="text" class="search" placeholder="' . get_option('ip_author_find_placeholder') . '">
		</div>
		<div style="clear: both;"></div>
	</div>';

	$display .= '<ul class="list">';

    foreach($hub_users as $user) {
        $author = $user->ID;
        $hub_user_info = get_userdata($author);
		$hub_location = get_the_author_meta('hub_location', $author);

        $card = '<li class="cinnamon-card">';
            $authors_posts = get_posts([
                'author' => $author,
                'posts_per_page' => get_option('ip_cards_per_author'),
                'post_type' => $ip_slug
            ]);
    
			if($authors_posts) {
                $card .= '<div class="mosaicflow">';
                    foreach($authors_posts as $authors_post) {
                        $card .= '<div><a href="' . get_permalink($authors_post->ID) . '">' . get_the_post_thumbnail($authors_post->ID, get_option('ip_cards_image_size')) . '</a></div>';
                    }
                $card .= '</div>';
            }
    
			$card .= '<div class="avatar-holder"><a href="' . get_author_posts_url($author) . '">' . get_avatar($author, 104) . '</a></div>';

            if(get_the_author_meta('user_title', $author) == 'Verified')
                $verified = ' <span class="teal hint hint--right" data-hint="' . get_option('cms_verified_profile') . '"><i class="fa fa-check-circle"></i></span>';
            else
                $verified = '';
            $card .= '<h3><a href="' . get_author_posts_url($author) . '" class="name">' . $hub_user_info->first_name . ' ' . $hub_user_info->last_name . '</a>' . $verified . '</h3>';
			if(!empty($hub_location))
				$card .= '<div class="location-holder"><small><i class="fa fa-map-marker teal"></i> <span class="location">' . get_the_author_meta('hub_location', $author) . '</span></small></div>';
    
			$card .= '<div class="cinnamon-stats">
				<div class="cinnamon-meta"><span class="views">' . kformat(cinnamon_PostViews($author, false)) . '</span><br><small>views</small></div>
				<div class="cinnamon-meta"><span class="followers">' . kformat(pwuf_get_follower_count($author)) . '</span><br><small>followers</small></div>
				<div class="cinnamon-meta"><span class="uploads">' . kformat(cinnamon_count_user_posts_by_type($author, $ip_slug)) . '</span><br><small>uploads</small></div>
			</div>';
        $card .= '</li>';

        if(ipGetBaseUri() == 'posterspy.com') {
            if($hub_user_info->first_name != '' && !empty($hub_location) && cinnamon_count_user_posts_by_type($author, $ip_slug) > 0) {
                $display .= $card;
            }
        }
        else {
            if(cinnamon_count_user_posts_by_type($author, $ip_slug) > 0) {
                $display .= $card;
            }
        }
    }
    $display .= '</ul><ul class="pagination"></ul></div>';
    $display .= '<div style="clear: both;"></div>';

    return $display;
}

/* CINNAMON PROFILE (BLANK) SHORTCODE */
function cinnamon_profile_blank($atts, $content = null) {
	extract(shortcode_atts(['author' => ''], $atts));

    $author = get_user_by('slug', get_query_var('author_name'));
    $author = $author->ID;

    $author_rewrite = get_user_by('slug', get_query_var('author_name'));
    $author_rewrite = $author_rewrite->user_login;

    if(empty($author))
        $author = get_current_user_id();

    $hub_user_info = get_userdata($author);
    $ip_slug = get_option('ip_slug');

    $hub_googleplus = ''; $hub_facebook = ''; $hub_twitter = '';
    if($hub_user_info->googleplus != '')
        $hub_googleplus = '<a href="' . $hub_user_info->googleplus . '" target="_blank"><i class="fa fa-google-plus-square"></i></a>';
    if($hub_user_info->facebook != '')
        $hub_facebook = '<a href="' . $hub_user_info->facebook . '" target="_blank"><i class="fa fa-facebook-square"></i></a>';
    if($hub_user_info->twitter != '')
        $hub_twitter = '<a href="https://twitter.com/' . $hub_user_info->twitter . '" target="_blank"><i class="fa fa-twitter-square"></i></a>';

    $hub_email = '<a href="mailto:' . get_the_author_meta('email', $author) . '" target="_blank"><i class="fa fa-envelope-square"></i></a>';

	$display = '';

	// themes // 1.0
	$theme = get_user_meta($author, 'cinnamon_portfolio_theme', true);
	if(empty($theme)) {
		$theme = 'default';
		update_user_meta($author, 'cinnamon_portfolio_theme', $theme);
	}

	if($theme == 'default') {
		$display .= '<style>.cornholio { max-width: 930px; margin: 0 auto; } .cornholio .c-main { text-align: center; font-size: 32px; font-weight: 300; } .cornholio .c-description { text-align: center; font-size: 14px; font-weight: 300; } .cornholio .c-social { text-align: center; font-size: 24px; } .cornholio .c-footer { text-align: center; font-size: 12px; }</style>';
		$display .= '<style>html, body { background-color: ' . get_user_meta($author, 'hub_portfolio_bg', true) . '; color: ' . get_user_meta($author, 'hub_portfolio_text', true) . '; } a, a:hover { color: ' . get_user_meta($author, 'hub_portfolio_link', true) . '; } ul#tab li.active a { border-bottom: 1px solid ' . get_user_meta($author, 'hub_portfolio_link', true) . '; }</style>';
		$cinnamon_size = 'thumbnail';
	}
	if($theme == 'sidebar-left') {
		$display .= '<style>.cornholio { max-width: 100%; margin: 0 auto; } .cornholio .c-main { text-align: center; font-size: 32px; font-weight: 300; } .cornholio .c-description { display: none; text-align: center; font-size: 14px; font-weight: 300; } .cornholio .c-social { text-align: center; font-size: 24px; margin: 16px 0; } .cornholio .c-footer { text-align: center; font-size: 12px; } .cornholio-top { width: 20%; float: left; padding-top: 64px; } .cornholio-bottom { width: 80%; float: right; border-left: 1px solid ' . get_user_meta($author, 'hub_portfolio_link', true) . '; } .cinnamon-grid-blank img { width: 248px; height: auto; } .about { padding: 90px 64px 256px 64px; } hr { border-top: 1px solid ' . get_user_meta($author, 'hub_portfolio_link', true) . '; opacity: 0.25; } #tab { box-shadow: none; text-align: left; } ul#tab li.active a { font-weight: 700; } #tab li { display: block; } #tab li a { margin: 0; }</style>';
		$display .= '<style>html, body { background-color: ' . get_user_meta($author, 'hub_portfolio_bg', true) . '; color: ' . get_user_meta($author, 'hub_portfolio_text', true) . '; } a, a:hover { color: ' . get_user_meta($author, 'hub_portfolio_link', true) . '; } ul#tab li.active a { border-bottom: 1px solid ' . get_user_meta($author, 'hub_portfolio_link', true) . '; }</style>';
		$cinnamon_size = 'imagepress_sq_std';
	}
	// end themes

	$display .= '<script>jQuery(document).ready(function(){ jQuery("' . get_option('cinnamon_hide') . '").hide(); });</script>
	<div class="cornholio">
		<div class="cornholio-top">
			<div class="c-main">' . $hub_user_info->first_name . ' ' . $hub_user_info->last_name . '</div>
			<div class="c-description"> ' . get_the_author_meta('hub_field', $author) . '<br><small>' . get_the_author_meta('hub_location', $author) . '</small></div>
			<div class="c-social">' . $hub_facebook . ' ' . $hub_twitter . ' ' . $hub_googleplus . ' ' . $hub_email;
				if($hub_user_info->user_url != '')
					$display .= ' <a href="' . $hub_user_info->user_url . '" rel="external" target="_blank"><i class="fa fa-link"></i></a>';
			$display .= '</div>

			<ul id="ip-tab">
				<li><a href="#" class="c-index">' . get_option('cinnamon_label_portfolio') . '</a></li>
				<li><a href="#">' . get_option('cinnamon_label_about') . '</a></li>
			</ul>
		</div>';

        $display .= '<div class="cornholio-bottom">
			<div class="ip_clear"></div>
			<div class="tab_icerik">';
				$display .= cinnamon_get_portfolio_posts($author, 12, $cinnamon_size);
			$display .= '</div>
			<div class="tab_icerik about">
				<h3>' . get_option('cinnamon_label_about') . '</h3>';
				$display .= make_clickable(wpautop($hub_user_info->description));
			$display .= '</div>
		</div>';

			$display .= '<div class="ip_clear"></div><hr><div class="c-footer">&copy; ' . $hub_user_info->first_name . ' ' . $hub_user_info->last_name . ' ' . date('Y') . '</div>';
			$display .= '<div class="c-footer">Portfolio provided by ' . get_bloginfo('name') . '</div>';
    $display .= '</div>';

    return $display;
}

/* CINNAMON PROFILE SHORTCODE */
function cinnamon_profile($atts, $content = null) {
    extract(shortcode_atts(['author' => ''], $atts));

    global $post;

    $author = get_user_by('slug', get_query_var('author_name'));
    $author = $author->ID;

    $author_rewrite = get_user_by('slug', get_query_var('author_name'));
    $author_rewrite = $author_rewrite->user_login;

    $ip_slug = get_option('ip_slug');

    if (empty($author))
        $author = get_current_user_id();

    $hub_user_info = get_userdata($author);

    $display = $hub_facebook = $hub_twitter = $hub_instagram = $hub_linkedin = $hub_user_url = $verified = '';

    if ($hub_user_info->facebook != '')
        $hub_facebook = ' <a href="' . $hub_user_info->facebook . '" target="_blank"><i class="fa fa-facebook"></i></a>';
    if ($hub_user_info->twitter != '')
        $hub_twitter = ' <a href="https://twitter.com/' . $hub_user_info->twitter . '" target="_blank"><i class="fa fa-twitter"></i></a>';
    if ($hub_user_info->instagram != '')
        $hub_instagram = ' <a href="https://instagram.com/' . $hub_user_info->instagram . '/" target="_blank"><i class="fa fa-instagram"></i></a>';
    if ($hub_user_info->linkedin != '')
        $hub_linkedin = ' <a href="' . $hub_user_info->linkedin . '" target="_blank"><i class="fa fa-linkedin"></i></a>';

    $hca = get_the_author_meta('hub_custom_cover', $author);
    $hca = wp_get_attachment_url($hca);
    if (!isset($hca) || empty($hca))
        $hca = IP_PLUGIN_URL . '/img/coverimage.png';

    $logged_in_user = wp_get_current_user();
    $hub_url = $hub_user_info->user_url;
    $hub_field = get_the_author_meta('hub_field', $author);
    $hub_location = get_the_author_meta('hub_location', $author);

    $display .= '<div class="cinnamon-cover" style="background: url(' . $hca . ') no-repeat center center; -webkit-background-size: cover; -moz-background-size: cover; -o-background-size: cover; background-size: cover;"></div>
    <div class="profile-hub-container">
        <div class="ip-tab">
            <ul class="ip-tabs active">
                <li class="current"><a href="#">Uploads<span>' . kformat(cinnamon_count_user_posts_by_type($author, $ip_slug)) . '</span></a></li>';

                if ((int) get_option('cinnamon_show_followers') === 1 && (int) pwuf_get_follower_count($author) > 0) {
                    $display .= '<li><a href="#">Followers<span>' . kformat(pwuf_get_follower_count($author)) . '</span></a></li>';
                }
                if ((int) get_option('cinnamon_show_following') === 1 && (int) pwuf_get_following_count($author) > 0) {
                    $display .= '<li><a href="#">Following<span>' . kformat(pwuf_get_following_count($author)) . '</span></a></li>';
                }
                if ((int) get_option('cinnamon_show_likes') === 1 && (int) frontEndUserLikesCount($author) > 0) {
                    $display .= '<li><a href="#">Loved posters<span>' . kformat(frontEndUserLikesCount($author)) . '</span></a></li>';
                }
                if ((int) get_option('cinnamon_show_awards') === 1) {
                    $display .= '<li><a href="#">Awards</a></li>';
                }

                if ((int) ipCollectionCount($author) > 0) {
                    $display .= '<li><a href="#">Collections<span>' . kformat(ipCollectionCount($author)) . '</span></a></li>';
                }
            $display .= '</ul>';

            if ((string) get_the_author_meta('user_title', $author) === 'Verified')
                $verified = ' <span class="teal hint hint--right" data-hint="' . get_option('cms_verified_profile') . '"><i class="fa fa-check-circle"></i></span>';

            $hubuser = get_user_by('id', $author);
            $hubuser = sanitize_title($hubuser->user_login);
            $hub_name = $hub_user_info->first_name . ' ' . $hub_user_info->last_name;
            if (empty($hub_user_info->first_name) && empty($hub_user_info->last_name))
                $hub_name = $hubuser;

            if ($hub_user_info->user_url != '')
                $hub_user_url = ' <a href="' . $hub_user_info->user_url . '" rel="external" target="_blank"><i class="fa fa-link"></i></a>';

            $display .= '<div class="cinnamon-profile-global-separator"></div>';
            $display .= '<div class="cinnamon-profile-sidebar">
                <div class="cinnamon-avatar">' . get_avatar($author, 120) . '</div>
                <h2 class="cinnamon-nametag">' . $hub_name . $verified . '</h2>';
                if (!empty($hub_location)) {
                    $display .= '<div class="cinnamon-locationtag">' . $hub_location . '</div>';
                }

                $display .= '<div class="cinnamon-sidebar-content centre">';
                    if (is_user_logged_in() && $author_rewrite != $logged_in_user->user_login) {
                        $display .= do_shortcode('[follow_links follow_id="' . $author . '"]');
                    }

                    if (get_the_author_meta('hub_status', $author) == 1) {
                        if (!empty(get_the_author_meta('hub_email', $author))) {
                            $hub_email = get_the_author_meta('hub_email', $author);
                        } else {
                            $hub_email = get_the_author_meta('email', $author);
                        }

                        $display .= ' <a href="mailto:' . $hub_email . '" class="btn btn-small btn-primary"><i class="fa fa-fw fa-envelope"></i> Contact</a>';
                    }
                $display .= '</div>';

                if (!empty($hub_user_info->description)) {
                    $display .= '<div class="cinnamon-bio">' . wpautop(strip_tags(html_entity_decode($hub_user_info->description))) . '</div>';
                }

                if (!empty(get_the_author_meta('hub_skills', $author))) {
                    $display .= '<h3>Skills</h3>';

                    $skills = get_the_author_meta('hub_skills', $author);
                    foreach ($skills as $key => $item) {
                        $display .= '<span class="cinnamon-skill cinnamon-skill-' . $key . '">' . trim($skills[$key]) . '</span>';
                    }
                }

                if (!empty(get_the_author_meta('hub_proficiency', $author))) {
                    $display .= '<h3>Software</h3>';

                    $proficiency = get_the_author_meta('hub_proficiency', $author);
                    foreach ($proficiency as $key => $item) {
                        $display .= '<span class="cinnamon-software cinnamon-software-' . $key . '">' . trim($proficiency[$key]) . '</span>';
                    }
                }

                $display .= '<h3>Social</h3>
                <div class="cinnamon-social">' . $hub_facebook . $hub_twitter . $hub_instagram . $hub_linkedin . $hub_user_url . '</div>';

                $repeatable_fields_featured = get_user_meta($author, 'repeatable_fields_featured', true);
                if ($repeatable_fields_featured) {
                    $display .= '<h3>Featured In</h3>';
                    foreach($repeatable_fields_featured as $field_featured) {
                        $display .= '<div class="repeatable-row-featured">
                            <a href="' . $field_featured['repeatable_url_featured'] . '" target="_blank">' . $field_featured['repeatable_name_featured'] . '</a>
                        </div>';
                    }
                }

                $repeatable_fields_external = get_user_meta($author, 'repeatable_fields_external', true);
                if ($repeatable_fields_external) {
                    $display .= '<h3>External Links</h3>';
                    foreach($repeatable_fields_external as $field_external) {
                        $display .= '<div class="repeatable-row-external">
                            <a href="' . $field_external['repeatable_url_external'] . '" target="_blank">' . $field_external['repeatable_name_external'] . '</a>
                        </div>';
                    }
                }

                if (is_user_logged_in() && $author_rewrite == $logged_in_user->user_login) {
                    $display .= '<div class="cinnamon-sidebar-content centre"><a href="' . get_option('cinnamon_edit_page') . '" class="btn btn-primary"><i class="fa fa-pencil-square-o"></i> ' . get_option('cinnamon_edit_label') . '</a></div>';
                }

            $display .= '</div>
            <div class="tab_content">
                <div class="ip-tabs-item" style="display: block;">' . do_shortcode('[imagepress-show user="' . $author . '" sort="no"]') . '</div>';

				if (get_option('cinnamon_show_followers') == 1) {
					$display .= '<div class="ip-tabs-item" style="display: none;">';
						$arr = pwuf_get_followers($author);
						if($arr) {
							$display .= '<div class="cinnamon-followers">';
								foreach($arr as $value) {
									$user = get_user_by('id', $value);
									$display .= '<a href="' . get_author_posts_url($value) . '">' . get_avatar($value, 40) . '</a> ';
								}
								unset($value);
							$display .= '</div>';
						}
					$display .= '</div>';
				}

				if(get_option('cinnamon_show_following') == 1) {
					$display .= '<div class="ip-tabs-item" style="display: none;">';
						$arr = pwuf_get_following($author);
						if($arr) {
							$display .= '<div class="cinnamon-followers">';
								foreach($arr as $value) {
									$user = get_user_by('id', $value);
									$display .= '<a href="' . get_author_posts_url($value) . '">' . get_avatar($value, 40) . '</a> ';
								}
								unset($value);
							$display .= '</div>';
						}
					$display .= '</div>';
				}

				if(get_option('cinnamon_show_likes') == 1) {
					$display .= '<div class="ip-tabs-item" style="display: none;">';
						$display .= frontEndUserLikes($author);
					$display .= '</div>';
				}

				if(get_option('cinnamon_show_awards') == 1) {
					$display .= '<div class="ip-tabs-item" style="display: none;">';
						$award_terms = wp_get_object_terms($author, 'award');
						if(!empty($award_terms)) {
							if(!is_wp_error($award_terms)) {
								foreach($award_terms as $term) {
									// get custom FontAwesome
									$t_ID = $term->term_id;
									$term_data = get_option("taxonomy_$t_ID");

									$display .= '<span class="cinnamon-award-list-item" title="' . $term->description . '">';
										if(isset($term_data['img']))
											$display .= '<i class="fa ' . $term_data['img'] . '"></i> ';
										else
											$display .= '<i class="fa fa-trophy"></i> ';
									$display .= $term->name . '</span>';
								}
							}
						}
					$display .= '</div>';
				}

				$display .= '<div class="ip-tabs-item" style="display: none;">';
					$display .= ip_collections_display_public($author);
				$display .= '</div>';

			$display .= '</div>
		</div>';





        $display .= '<div style="clear: both;"></div>';


    $display .= '</div>';

    return $display;
}

function cinnamon_profile_edit($atts, $content = null) {
    extract(shortcode_atts(['author' => ''], $atts));

    global $wpdb, $current_user, $post;
    get_currentuserinfo();

    $error = [];

    if ('POST' == $_SERVER['REQUEST_METHOD'] && !empty($_POST['action']) && $_POST['action'] == 'update-user') {
        if (!empty($_POST['pass1']) && !empty($_POST['pass2'])) {
            if ($_POST['pass1'] == $_POST['pass2'])
                wp_update_user(['ID' => $current_user->ID, 'user_pass' => esc_attr($_POST['pass1'])]);
            else
                $error[] = 'The passwords you entered do not match. Your password was not updated.';
        }

        if (!empty($_POST['url']))
            wp_update_user(['ID' => $current_user->ID, 'user_url' => esc_url($_POST['url'])]);
        if (!empty($_POST['email'])) {
            if (!is_email(esc_attr($_POST['email'])))
                $error[] = 'The email you entered is not valid. Please try again.';
            else if (email_exists(esc_attr($_POST['email'])) != $current_user->ID)
                $error[] = 'This email is already used by another user. Try a different one.';
            else {
                wp_update_user(['ID' => $current_user->ID, 'user_email' => esc_attr($_POST['email'])]);
            }
        }

        if (!empty($_POST['first-name']))
            update_user_meta($current_user->ID, 'first_name', esc_attr($_POST['first-name']));
        if (!empty($_POST['last-name']))
            update_user_meta($current_user->ID, 'last_name', esc_attr($_POST['last-name']));

        if (!empty($_POST['nickname'])) {
            update_user_meta($current_user->ID, 'nickname', esc_attr($_POST['nickname']));
            $wpdb->update($wpdb->users, ['display_name' => $_POST['nickname']], ['ID' => $current_user->ID], null, null);
        }

        if (!empty($_POST['description']))
            update_user_meta($current_user->ID, 'description', esc_attr($_POST['description']));

        if (!empty($_POST['facebook']))
            update_user_meta($current_user->ID, 'facebook', esc_attr($_POST['facebook']));
        if (!empty($_POST['twitter']))
            update_user_meta($current_user->ID, 'twitter', esc_attr($_POST['twitter']));
        if (!empty($_POST['instagram']))
            update_user_meta($current_user->ID, 'instagram', esc_attr($_POST['instagram']));
        if (!empty($_POST['linkedin']))
            update_user_meta($current_user->ID, 'linkedin', esc_attr($_POST['linkedin']));

        /* MULTIPLE REPEATABLE FIELDS */
        $old_featured = get_user_meta($current_user->ID, 'repeatable_fields_featured', true);
        $old_external = get_user_meta($current_user->ID, 'repeatable_fields_external', true);

        $new_featured = [];
        $new_external = [];

        $names_featured = $_POST['repeatable_name_featured'];
        $names_external = $_POST['repeatable_name_external'];

        $urls_featured = $_POST['repeatable_url_featured'];
        $urls_external = $_POST['repeatable_url_external'];

        $count_featured = count($names_featured);
        $count_external = count($names_external);

        for ($i = 0; $i < $count_featured; $i++) {
            if ($names_featured[$i] != '') :
                $new_featured[$i]['repeatable_name_featured'] = stripslashes(strip_tags($names_featured[$i]));
            if ($urls_featured[$i] == 'https://')
                $new_featured[$i]['repeatable_url_featured'] = '';
            else
                $new_featured[$i]['repeatable_url_featured'] = stripslashes($urls_featured[$i]);
            endif;
        }
        if (!empty( $new_featured ) && $new_featured != $old_featured)
            update_user_meta($current_user->ID, 'repeatable_fields_featured', $new_featured);
        else if (empty($new_featured) && $old_featured)
            delete_user_meta($current_user->ID, 'repeatable_fields_featured', $old_featured);

        for ($i = 0; $i < $count_external; $i++) {
            if ($names_external[$i] != '') :
                $new_external[$i]['repeatable_name_external'] = stripslashes(strip_tags($names_external[$i]));
            if ($urls_external[$i] == 'https://')
                $new_external[$i]['repeatable_url_external'] = '';
            else
                $new_external[$i]['repeatable_url_external'] = stripslashes($urls_external[$i]);
            endif;
        }
        if (!empty( $new_external ) && $new_external != $old_external)
            update_user_meta($current_user->ID, 'repeatable_fields_external', $new_external);
        else if (empty($new_external) && $old_external)
            delete_user_meta($current_user->ID, 'repeatable_fields_external', $old_external);
        /**/

        // Avatar and cover upload
        if ($_FILES) {
            require_once(ABSPATH . "wp-admin" . '/includes/image.php');
            require_once(ABSPATH . "wp-admin" . '/includes/file.php');
            require_once(ABSPATH . "wp-admin" . '/includes/media.php');

            foreach ($_FILES as $file => $array) {
                if (!empty($_FILES[$file]['name'])) {
                    $file_id = media_handle_upload($file, 0);
                    if ($file_id > 0) {
                        update_user_meta($current_user->ID, $file, $file_id);
                    }
                }
            }
        }
        //

        if (count($error) == 0) {
            do_action('edit_user_profile_update', $current_user->ID);
            echo '<p class="message noir-success">Profile updated successfully!</p>';
        }
    }
    ?>

    <div id="post-<?php the_ID(); ?>">
        <div class="entry-content entry cinnamon">
            <?php if (!is_user_logged_in()) : ?>
                <p class="warning">You must be logged in to edit your profile.</p>
            <?php else : ?>
                <?php if (count($error) > 0) echo '<p class="error">' . implode('<br>', $error) . '</p>'; ?>

                <form method="post" id="adduser" action="<?php the_permalink(); ?>" enctype="multipart/form-data">
                    <div class="ip-tab" style="width: 1170px; margin: 0 auto;">
                        <ul class="ip-tabs ip-profile active" style="float: left; width: 200px; padding: 0;">
                            <li class="current" style="float: none;"><a href="#"><?php echo get_option('cinnamon_pt_account'); ?></a></li>
                            <li style="float: none;"><a href="#"><?php echo get_option('cinnamon_pt_author'); ?></a></li>
                            <li style="float: none;"><a href="#" class="imagepress-collections">Collections</a></li>
                            <?php if (isset($_GET['dev'])) { ?>
                                <li style="float: none;"><a href="#">Linked Accounts</a></li>
                            <?php } ?>
                        </ul>

                        <div class="tab_content" style="width: 920px; float: right;">
                            <div class="ip-tabs-item" style="display: block;">
                                <?php
                                $hcc = get_the_author_meta('hub_custom_cover', $current_user->ID);
                                $hca = get_the_author_meta('hub_custom_avatar', $current_user->ID);
                                $hcc = wp_get_attachment_url($hcc);
                                $hca = wp_get_attachment_url($hca);
                                ?>
                                <table class="form-table">
                                    <?php if (!is_admin()) { ?>
                                        <tr>
                                            <td style="text-align: center; vertical-align: middle; width: 30%;">
                                                <img src="<?php echo $hca; ?>" width="90" alt="My PosterSpy avatar">
                                            </td>
                                            <td style="text-align: center; vertical-align: middle; width: 70%;">
                                                <div class="cinnamon-cover-preview" style="background: url('<?php echo $hcc; ?>') no-repeat center center; -webkit-background-size: cover; -moz-background-size: cover; -o-background-size: cover; background-size: cover;"></div>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                    <tr>
                                        <td style="text-align: center;">
                                            <label for="hub_custom_avatar" class="hub_label_avatar">
                                                <input type="file" name="hub_custom_avatar" id="hub_custom_avatar" value="<?php echo get_the_author_meta('hub_custom_avatar', $current_user->ID); ?>">
                                                <div class="profile-mini-heading"><i class="fa fa-fw fa-upload" aria-hidden="true"></i> Change Avatar</div>
                                                <small>Recommended size is 500x500.</small>
                                            </label>
                                        </td>
                                        <td style="text-align: center;">
                                            <label for="hub_custom_cover" class="hub_label_cover">
                                                <input type="file" name="hub_custom_cover" id="hub_custom_cover" value="<?php echo get_the_author_meta('hub_custom_cover', $current_user->ID); ?>" class="regular-text">
                                                <div class="profile-mini-heading"><i class="fa fa-fw fa-upload" aria-hidden="true"></i> Change Profile Banner</div>
                                                <small>Recommended size is 1080x300.</small>
                                            </label>
                                        </td>
                                    </tr>
                                </table>

                                <table class="form-table">
                                    <tr>
                                        <td colspan="2"><h3>Basic Info</h3></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label for="first-name">First name</label>
                                            <input name="first-name" type="text" id="first-name" value="<?php the_author_meta('first_name', $current_user->ID); ?>">
                                        </td>
                                        <td>
                                            <label for="last-name">Last name</label>
                                            <input name="last-name" type="text" id="last-name" value="<?php the_author_meta('last_name', $current_user->ID); ?>">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <label for="email">Email Address</label>
                                            <input name="email" type="email" id="email" value="<?php the_author_meta('user_email', $current_user->ID); ?>">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <label for="nickname">Nickname</label>
                                            <input name="nickname" type="text" id="nickname" value="<?php the_author_meta('nickname', $current_user->ID); ?>">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label for="hub_location">Location</label>
                                            <input type="text" name="hub_location" id="hub_location" value="<?php echo esc_attr(get_the_author_meta('hub_location', $current_user->ID)); ?>" class="regular-text">
                                        </td>
                                        <td>
                                            <label for="url">Website</label>
                                            <input name="url" type="text" id="url" value="<?php the_author_meta('user_url', $current_user->ID); ?>">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label for="hub_field">Occupational field</label>
                                            <input type="text" name="hub_field" id="hub_field" value="<?php echo esc_attr(get_the_author_meta('hub_field', $current_user->ID)); ?>" class="regular-text">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <label for="description">About Me <small>(500 characters max)</small></label>
                                            <textarea name="description" id="description" rows="4" style="width: 100%;"><?php the_author_meta('description', $current_user->ID); ?></textarea>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2"><h3>Hiring</h3></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label for="hub_email">Email Address (public, for hiring purposes)</label>
                                            <input type="email" name="hub_email" id="hub_email" value="<?php echo esc_attr(get_the_author_meta('hub_email', $current_user->ID)); ?>" class="regular-text">
                                        </td>
                                        <td>
                                            <label for="hub_status">Status</label>
                                            <select name="hub_status" id="hub_status">
                                                <option value="1"<?php if(get_the_author_meta('hub_status', $current_user->ID) == 1) echo ' selected'; ?>>Available for hire</option>
                                                <option value="0"<?php if(get_the_author_meta('hub_status', $current_user->ID) == 0) echo ' selected'; ?>>Not available for hire</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2"><small>Being available for hire will show a contact icon on your profile. All hiring/contact emails will be sent to the email address selected.</small></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2"><h3>Skills</h3></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <?php
                                            $cinnamonSkills = get_option('cinnamon_skills');
                                            $cinnamonSkills = explode(',', $cinnamonSkills);

                                            echo '<div class="hub-skills">';
                                                foreach ($cinnamonSkills as $key => $item) {
                                                    $skills = get_the_author_meta('hub_skills', $current_user->ID);
                                                    $checked = '';
                                                    if (in_array(trim($cinnamonSkills[$key]), $skills)) {
                                                        $checked = 'checked';
                                                    }

                                                    echo '<input type="checkbox" name="hub_skills[]" id="hub-skills-' . $key . '" value="' . trim($cinnamonSkills[$key]) . '" ' . $checked . '><label for="hub-skills-' . $key . '">' . trim($cinnamonSkills[$key]) . '</label><br>';
                                                }
                                            echo '</div>';
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2"><h3>Software Proficiency</h3></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <?php
                                            $cinnamonSoftware = get_option('cinnamon_software');
                                            $cinnamonSoftware = explode(',', $cinnamonSoftware);

                                            echo '<div class="hub-proficiencies">';
                                                foreach ($cinnamonSoftware as $key => $item) {
                                                    $proficiency = get_the_author_meta('hub_proficiency', $current_user->ID);
                                                    $checked = '';
                                                    if (in_array(trim($cinnamonSoftware[$key]), $proficiency)) {
                                                        $checked = 'checked';
                                                    }

                                                    echo '<input type="checkbox" name="hub_proficiency[]" id="hub-proficiency-' . $key . '" value="' . trim($cinnamonSoftware[$key]) . '" ' . $checked . '><label for="hub-proficiency-' . $key . '">' . trim($cinnamonSoftware[$key]) . '</label><br>';
                                                }
                                            echo '</div>';
                                            ?>
                                        </td>
                                    </tr>
                                </table>

                                <?php $repeatable_fields_featured = get_user_meta($current_user->ID, 'repeatable_fields_featured', true); ?>

                                <style>
                                .empty-row { display: none; }
                                </style>
                                <script>
                                jQuery(document).ready(function($) {
                                    $('#add-row-featured').on('click', function() {
                                        var row = $('.empty-row-featured').clone(true);
                                        row.removeClass('empty-row-featured');
                                        row.insertBefore('#repeatable-fieldset-one-featured > .repeatable-row-featured:last');

                                        return false;
                                    });
                                    $('#add-row').on('click', function() {
                                        var row = $('.empty-row').clone(true);
                                        row.removeClass('empty-row');
                                        row.insertBefore('#repeatable-fieldset-one > .repeatable-row:last');

                                        return false;
                                    });
                                    $('.remove-row-featured').on('click', function() {
                                        $(this).parents('.repeatable-row-featured').remove();

                                        return false;
                                    });
                                    $('.remove-row').on('click', function() {
                                        $(this).parents('.repeatable-row').remove();

                                        return false;
                                    });
                                    /**
                                    $('#repeatable-fieldset-one tbody').sortable({
                                        opacity: 0.6,
                                        revert: true,
                                        cursor: 'move',
                                        handle: '.sort'
                                    });
                                    /**/
                                });
                                </script>

                                <table class="form-table">
                                    <tr>
                                        <td colspan="2"><h3>Featured In<br><small>Links to where you've been published, interviews, articles on your work</small></h3></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <div id="repeatable-fieldset-one-featured">
                                                <?php if ($repeatable_fields_featured) { ?>
                                                    <?php foreach($repeatable_fields_featured as $field_featured) { ?>
                                                        <div class="repeatable-row-featured">
                                                            <span style="display: inline-block; width: 30%;"><input type="text" class="widefat" name="repeatable_name_featured[]" value="<?php if($field_featured['repeatable_name_featured'] != '') echo esc_attr( $field_featured['repeatable_name_featured'] ); ?>" placeholder="Title"></span>
                                                            <span style="display: inline-block; width: 60%;"><input type="url" class="widefat" name="repeatable_url_featured[]" value="<?php if ($field_featured['repeatable_url_featured'] != '') echo esc_attr( $field_featured['repeatable_url_featured'] ); ?>" placeholder="https://"></span>
                                                            <span style="display: none; width: 2%;"><a class="sort"><i class="fa fa-arrows" aria-hidden="true"></i></a></span>
                                                            <span style="display: inline-block; width: 2%;"><a class="button remove-row-featured" href="#"><i class="fa fa-times" aria-hidden="true"></i></a></span>
                                                        </div>
                                                    <?php } ?>
                                                <?php } else { ?>
                                                    <div class="repeatable-row-featured">
                                                        <span style="display: inline-block; width: 30%;"><input type="text" class="widefat" name="repeatable_name_featured[]" placeholder="Title"></span>
                                                        <span style="display: inline-block; width: 60%;"><input type="url" class="widefat" name="repeatable_url_featured[]" placeholder="https://"></span>
                                                        <span style="display: none; width: 2%;"><a class="sort"><i class="fa fa-arrows" aria-hidden="true"></i></a></span>
                                                        <span style="display: inline-block; width: 2%;"><a class="button remove-row-featured" href="#"><i class="fa fa-times" aria-hidden="true"></i></a></span>
                                                    </div>
                                                <?php } ?>

                                                <!-- empty hidden one for jQuery -->
                                                <div class="repeatable-row-featured empty-row-featured">
                                                    <span style="display: inline-block; width: 30%;"><input type="text" class="widefat" name="repeatable_name_featured[]" placeholder="Title"></span>
                                                    <span style="display: inline-block; width: 60%;"><input type="url" class="widefat" name="repeatable_url_featured[]" placeholder="https://"></span>
                                                    <span style="display: none; width: 2%;"><a class="sort"><i class="fa fa-arrows" aria-hidden="true"></i></a></span>
                                                    <span style="display: inline-block; width: 2%;"><a class="button remove-row-featured" href="#"><i class="fa fa-times" aria-hidden="true"></i></a></span>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <a id="add-row-featured" href="#">Add Row</a>
                                        </td>
                                    </tr>
                                </table>

                                <?php $repeatable_fields_external = get_user_meta($current_user->ID, 'repeatable_fields_external', true); ?>

                                <table class="form-table">
                                    <tr>
                                        <td colspan="2"><h3>External Links<br><small>Your stores or websites</small></h3></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <div id="repeatable-fieldset-one">
                                                <?php if ($repeatable_fields_external) { ?>
                                                    <?php foreach ($repeatable_fields_external as $field_external) { ?>
                                                        <div class="repeatable-row">
                                                            <span style="display: inline-block; width: 30%;"><input type="text" class="widefat" name="repeatable_name_external[]" value="<?php if($field_external['repeatable_name_external'] != '') echo esc_attr( $field_external['repeatable_name_external'] ); ?>" placeholder="Title"></span>
                                                            <span style="display: inline-block; width: 60%;"><input type="url" class="widefat" name="repeatable_url_external[]" value="<?php if ($field_external['repeatable_url_external'] != '') echo esc_attr( $field_external['repeatable_url_external'] ); ?>" placeholder="https://"></span>
                                                            <span style="display: none; width: 2%;"><a class="sort"><i class="fa fa-arrows" aria-hidden="true"></i></a></span>
                                                            <span style="display: inline-block; width: 2%;"><a class="button remove-row" href="#"><i class="fa fa-times" aria-hidden="true"></i></a></span>
                                                        </div>
                                                    <?php } ?>
                                                <?php } else { ?>
                                                    <div class="repeatable-row">
                                                        <span style="display: inline-block; width: 30%;"><input type="text" class="widefat" name="repeatable_name_external[]" placeholder="Title"></span>
                                                        <span style="display: inline-block; width: 60%;"><input type="url" class="widefat" name="repeatable_url_external[]" placeholder="https://"></span>
                                                        <span style="display: none; width: 2%;"><a class="sort"><i class="fa fa-arrows" aria-hidden="true"></i></a></span>
                                                        <span style="display: inline-block; width: 2%;"><a class="button remove-row" href="#"><i class="fa fa-times" aria-hidden="true"></i></a></span>
                                                    </div>
                                                <?php } ?>

                                                <!-- empty hidden one for jQuery -->
                                                <div class="repeatable-row empty-row">
                                                    <span style="display: inline-block; width: 30%;"><input type="text" class="widefat" name="repeatable_name_external[]" placeholder="Title"></span>
                                                    <span style="display: inline-block; width: 60%;"><input type="url" class="widefat" name="repeatable_url_external[]" placeholder="https://"></span>
                                                    <span style="display: none; width: 2%;"><a class="sort"><i class="fa fa-arrows" aria-hidden="true"></i></a></span>
                                                    <span style="display: inline-block; width: 2%;"><a class="button remove-row" href="#"><i class="fa fa-times" aria-hidden="true"></i></a></span>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <a id="add-row" href="#">Add Row</a>
                                        </td>
                                    </tr>
                                </table>

                                <table class="form-table">
                                    <tr>
                                        <td colspan="2"><h3>Social</h3></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <label for="facebook">Facebook profile URL</label>
                                            <input name="facebook" type="url" id="facebook" value="<?php the_author_meta('facebook', $current_user->ID); ?>" placeholder="https://">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <label for="twitter">Twitter username</label>
                                            <input name="twitter" type="text" id="twitter" value="<?php the_author_meta('twitter', $current_user->ID); ?>" placeholder="Username">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <label for="instagram">Instagram username</label>
                                            <input name="instagram" type="text" id="instagram" value="<?php the_author_meta('instagram', $current_user->ID); ?>" placeholder="Username">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <label for="linkedin">LinkedIn URL</label>
                                            <input name="linkedin" type="text" id="linkedin" value="<?php the_author_meta('linkedin', $current_user->ID); ?>" placeholder="https://">
                                        </td>
                                    </tr>
                                </table>
                            </div>

                            <div class="ip-tabs-item" style="display: none;">
                                <table class="form-table">
                                    <tr>
                                        <th><label for="pass1">Password *</label></th>
                                        <td><input name="pass1" type="password" id="pass1"></td>
                                    </tr>
                                    <tr>
                                        <th><label for="pass2">Repeat password *</label></th>
                                        <td><input name="pass2" type="password" id="pass2"></td>
                                    </tr>
                                </table>
                            </div>

                            <div class="ip-tabs-item" style="display: none;">
                                <p>
                                    <a href="#" class="toggleModal btn btn-primary"><i class="fa fa-plus"></i> Create new collection</a>
                                    <span class="ip-loadingCollections"><i class="fa fa-cog fa-spin"></i> Loading collections...</span>
                                    <span class="ip-loadingCollectionImages"><i class="fa fa-cog fa-spin"></i> Loading collection images...</span>
                                    <a href="#" class="imagepress-collections imagepress-float-right button"><i class="fa fa-refresh"></i></a>
                                </p>
                                <div class="modal">
                                    <h2>Create new collection</h2>
                                    <a href="#" class="close toggleModal"><i class="fa fa-times"></i> Close</a>

                                    <input type="hidden" id="collection_author_id" name="collection_author_id" value="<?php echo $current_user->ID; ?>">
                                    <p><input type="text" id="collection_title" name="collection_title" placeholder="Collection title"></p>
                                    <p><label>Make this collection</label> <select id="collection_status"><option value="1">Public</option><option value="0">Private</option></select></p>
                                    <p class="ip-paragraph-gap-6"><small><a href="<?php echo get_option('ip_collections_read_more_link'); ?>" target="_blank"><i class="fa fa-question-circle"></i> <?php echo get_option('ip_collections_read_more'); ?></a></small></p>
                                    <p>
                                        <input type="submit" value="Create" class="addCollection">
                                        <label class="collection-progress"><i class="fa fa-cog fa-spin"></i></label>
                                        <label class="showme"> <i class="fa fa-check"></i> Collection created!</label>
                                    </p>
                                </div>

                                <div class="collections-display"></div>
                            </div>

                            <?php if (isset($_GET['dev'])) { ?>
                                <div class="ip-tabs-item" style="display: none;">
                                    <h3>Linked Accounts</h3>
                                    <p>Linking your social accounts enables you to auto share your uploads.</p>

                                    <div id="fb-root"></div>
                                    <script>
                                    (function(d, s, id) {
                                        var js, fjs = d.getElementsByTagName(s)[0];
                                        if (d.getElementById(id)) return;
                                        js = d.createElement(s); js.id = id;
                                        js.src = 'https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.12&appId=1436221290006088&autoLogAppEvents=1';
                                        fjs.parentNode.insertBefore(js, fjs);
                                    }(document, 'script', 'facebook-jssdk'));
                                    </script>

                                    <div class="fb-login-button" data-size="large" data-button-type="continue_with" data-show-faces="false" data-auto-logout-link="true" data-use-continue-as="true" data-scope="public_profile, publish_actions"></div>
                                </div>
                            <?php } ?>
                        </div>
                        <div style="clear: both;"></div>
                    </div>

                    <?php do_action('edit_user_profile', $current_user); ?>
                    <hr>
                    <table class="form-table">
                        <tr>
                            <td colspan="2">
                                <input name="updateuser" type="submit" class="btn btn-primary" id="updateuser" value="Update">
                                <?php wp_nonce_field('update-user'); ?>
                                <input name="action" type="hidden" id="action" value="update-user">
                                <i class="fa fa-share-square"></i> <a href="<?php echo get_author_posts_url($current_user->ID); ?>">View and share your profile></a>
                            </td>
                        </tr>
                    </table>
                </form>
            <?php endif; ?>
        </div>
    </div>
    <?php
}

/* CINNAMON CUSTOM PROFILE FIELDS */
function save_cinnamon_profile_fields($user_id) {
    if (!current_user_can('edit_user', $user_id))
        return false;

    update_user_meta($user_id, 'hub_location', $_POST['hub_location']);
    update_user_meta($user_id, 'hub_field', $_POST['hub_field']);
    update_user_meta($user_id, 'hub_status', $_POST['hub_status']);

    update_user_meta($user_id, 'hub_skills', $_POST['hub_skills']);
    update_user_meta($user_id, 'hub_proficiency', $_POST['hub_proficiency']);

    update_user_meta($user_id, 'hub_portfolio_bg', $_POST['hub_portfolio_bg']);
    update_user_meta($user_id, 'hub_portfolio_text', $_POST['hub_portfolio_text']);
    update_user_meta($user_id, 'hub_portfolio_link', $_POST['hub_portfolio_link']);

    update_user_meta($user_id, 'cinnamon_portfolio_theme', $_POST['cinnamon_portfolio_theme']);

	// Awards
    if (current_user_can('manage_options', $user_id)) {
		update_user_meta($user_id, 'user_title', $_POST['user_title']);

		$tax = get_taxonomy('award');
        $term = $_POST['award'];
        wp_set_object_terms($user_id, $term, 'award', false);
        clean_object_term_cache($user_id, 'award');
    }
}

function hub_gravatar_filter($avatar, $id_or_email, $size) {
    // Do not use email for get_avatar(), use ID
    global $current_user;

    $image_url = get_user_meta($id_or_email, 'hub_custom_avatar', true);
    $custom_avatar = wp_get_attachment_thumb_url($image_url);

    if (!empty($image_url)) {
        return '<img src="' . $custom_avatar . '" class="avatar" width="' . $size . '" height="' . $size . '" alt="">';
    } else {
        return '<img src="https://posterspy.com/wp-content/themes/moon-ui-theme/img/avatar.jpg" class="avatar" width="' . $size . '" height="' . $size . '" alt="">';
    }

    return $avatar;
}

function cinnamon_awards() {
    $args = [
        'hide_empty' => false,
        'pad_counts' => true
    ];
    $terms = get_terms('award', $args);

    if (!empty($terms) && !is_wp_error($terms)) {
        foreach ($terms as $term) {
            // get custom FontAwesome
            $t_ID = $term->term_id;
            $term_data = get_option("taxonomy_$t_ID");

            echo '<p><span class="cinnamon-award-list-item" title="' . $term->description . '">';
                if (isset($term_data['img'])) {
                    echo '<i class="fa ' . $term_data['img'] . '"></i> ';
                } else {
                    echo '<i class="fa fa-trophy"></i> ';
                }
                echo $term->name . '</span> <span>' . $term->description . '<br><small>(' . $term->count . ' author(s) received this award)</small></span>';
            echo '</p>';
        }
    }
}
