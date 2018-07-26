<?php if(have_posts()) : ?>
    <?php while(have_posts()) : the_post(); ?>
        <?php
        $i = get_the_ID();

        $user_info = get_userdata(get_the_author_id());
        $post_thumbnail_id = get_post_thumbnail_id($i);

        $image_attributes = wp_get_attachment_image_src($post_thumbnail_id, 'imagepress_pt_std');

        /**
        $tag = wp_get_post_terms($user_image->ID, 'imagepress_image_tag');
        $tag = $tag[0]->slug;
        if($tag == 'work-in-progress')
            $tagged = '<i class="fa fa-lg fa-flask"></i>';
        else if($tag == 'request-critique')
            $tagged = '<i class="fa fa-lg fa-comments-o"></i>';
        else
            $tagged = '';
        /**/

        echo '<div class="ip_box"><a href="' . get_permalink($i) . '" class="ip_box_img"><img src="' . $image_attributes[0] . '" alt="' . get_the_title($i) . '"></a><div class="ip_box_top"><a href="' . get_permalink($i) . '" class="imagetitle">' . get_the_title($i) . '</a><span class="name">' . get_avatar(get_the_author_id(), 16) . ' <a href="' . get_author_posts_url(get_the_author_id()) . '">' . get_the_author() . '</a></span></div></div>';
        ?>
    <?php endwhile; ?>
<?php endif; ?>