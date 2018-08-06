<?php if (have_posts()) : while (have_posts()) : the_post();
    $i = get_the_ID();

    $user_info = get_userdata(get_the_author_id());
    $post_thumbnail_id = get_post_thumbnail_id($i);

    $image_attributes = wp_get_attachment_image_src($post_thumbnail_id, 'imagepress_pt_lrg');

    echo '<div class="ip_box"><a href="' . get_permalink($i) . '" class="ip_box_img"><img src="' . $image_attributes[0] . '" alt="' . get_the_title($i) . '"></a><div class="ip_box_top"><a href="' . get_permalink($i) . '" class="imagetitle">' . get_the_title($i) . '</a><span class="name">' . get_avatar(get_the_author_id(), 16) . ' <a href="' . get_author_posts_url(get_the_author_id()) . '">' . get_the_author() . '</a></span></div></div>';
endwhile; endif;
