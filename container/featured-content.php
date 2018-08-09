<?php
/**
 * Featured content
 *
 */
?>
<div class="infiniteCarousel">
	<div class="logo">
        <?php if(is_user_logged_in()) { ?>
            <a href="<?php echo home_url(); ?>/upload/" class="button noir-secondary">
                <span style="font-size: 24px; font-weight: 600;"><i class="fas fa-cloud-upload-alt"></i> Upload</span>
                <br><small style="font-weight: 600; font-size: 90%;">Explore and share great poster design</small>
            </a>
        <?php } else { ?>
            <a href="<?php echo home_url(); ?>/sign-up/" class="button noir-secondary">
                <span style="font-size: 24px; font-weight: 600;"><i class="fas fa-pen-square"></i> Sign up</span>
                <br><small style="font-weight: 600; font-size: 90%;">Explore and share great poster design</small>
            </a>
        <?php } ?>
	</div>
	<div class="gallery">
		<ul>
            <?php
            $args = [
                'posts_per_page' => 9999,
                'offset'=> 0,
                'post_type' => 'poster',
                'imagepress_image_category' => 'featured',
                'order' => 'DESC',
            ];

            $myposts = get_posts($args);
            foreach($myposts as $post) : setup_postdata($post); ?>
				<?php if(has_post_thumbnail()) : ?>
					<li>
						<?php the_post_thumbnail('frontpage_high_slider'); ?>
					</li>
				<?php endif; ?>
            <?php endforeach; wp_reset_postdata(); ?>
		</ul>
	</div>
</div>

<div class="clear height-4"></div>
