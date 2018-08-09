<div class="moon-lightbox-close"><i class="fas fa-times" aria-hidden="true"></i></div>

<aside id="sidebar" class="sidebar-lightbox" role="complementary">
    <?php
    global $post;
    $post_thumbnail_id = get_post_thumbnail_id(get_the_ID());
    $author_id = $post->post_author;

    if (get_post_meta(get_the_ID(), 'imagepress_purchase', true) != '') {
        echo '<div class="moon-lightbox-purchase">This poster is available to purchase. <a href="' . get_post_meta(get_the_ID(), 'imagepress_purchase', true) . '" class="btn btn-tertiary" target="_blank" rel="external"><i class="fas fa-shopping-cart"></i> Buy now</a></div>';
    }
    ?>

    <div class="widget-container widget_text">
        <div class="moon-lightbox-author">
            <div class="moon-lightbox-author-avatar">
                <?php echo get_avatar($post->post_author, 48); ?>
            </div>
            <div class="moon-lightbox-author-details">
                <?php
                if (get_the_author_meta('user_title', $post->post_author) == 'Verified') {
                    $verified = ' <span class="teal hint hint--right" data-hint="Verified Profile"><i class="fas fa-check-circle"></i></span>';
                } else {
                    $verified = '';
                }
                ?>
                <b><?php the_author_posts_link(); ?></b> <?php echo $verified; ?>
                <br><small><?php echo pwuf_get_follower_count($post->post_author); ?> followers</small>
            </div>
        </div>

        <div style="text-align: center">
            <?php echo ipGetPostLikeLink(get_the_ID()); ?>
            <?php ip_frontend_add_collection(get_the_ID()); ?>
            <a href="#" id="lightbox-share" class="btn btn-primary"><i class="fas fa-share-alt" aria-hidden="true"></i> Share</a>

            <div class="social-hub">
                <a style="background-color: #3B5998;" href="#" onclick="window.open('https://www.facebook.com/sharer/sharer.php?u='+encodeURIComponent(location.href), 'facebook-share-dialog', 'width=626,height=436'); return false;"><i class="fab fa-facebook-square"></i></a>
                <a style="background-color: #00ACED;" href="https://twitter.com/share" target="_blank" onclick="javascript:window.open(this.href,
  '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;" class="twitter-share" data-via="getButterfly" data-related="getButterfly" data-count="none" data-hashtags="posterspy"><i class="fab fa-twitter-square"></i></a><script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
                <a style="background-color: #CB2027;" href='javascript:void(run_pinmarklet1())'><i class="fab fa-pinterest-square"></i></a><script>function run_pinmarklet1() { var e=document.createElement('script'); e.setAttribute('type','text/javascript'); e.setAttribute('charset','UTF-8'); e.setAttribute('src','http://assets.pinterest.com/js/pinmarklet.js?r='+Math.random()*99999999); document.body.appendChild(e); }</script>
                <a style="background-color: #2C4762;" href="http://www.tumblr.com/share"target="_blank" onclick="javascript:window.open(this.href,
  '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;"><i class="fab fa-tumblr-square"></i></a>
                <a style="background-color: #d23e30;" href="#" onclick="javascript:window.open('https://plus.google.com/share?url='+encodeURIComponent(location.href),'', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;"><i class="fab fa-google-plus-square"></i></a>
                <div class="clearfix"></div>
            </div>

            <?php imagepress_get_users_love(get_the_ID()); ?>
        </div>

        <hr>
        <h1 id="lightbox-title">
            <?php
            $terms = get_the_terms(get_the_ID(), 'imagepress_image_category');
            if ($terms) {
                $terms_slugs = [];
                foreach ($terms as $term ) {
                    if ($term->slug == 'staffpicks') {
                        echo '<span class="hint hint--right yellow" data-hint="Staff Pick"><i class="fas fa-star"></i> </span>';
                    }
                    $terms_slugs[] = $term->slug;
                }
            }
            
            the_title();
            ?>
        </h1>
        <?php the_content(); ?>

        <div class="details-box">
            <div>
                <i class="fas fa-eye" aria-hidden="true"></i>
                <br><?php echo ip_getPostViews(get_the_ID()); ?>
            </div>
            <div>
                <i class="fas fa-heart" aria-hidden="true"></i>
                <br><?php echo imagepress_get_like_count(get_the_ID()); ?>
            </div>
            <div>
                <i class="fas fa-comment" aria-hidden="true"></i>
                <br><?php echo get_comments_number(get_the_ID()); ?>
            </div>
            <div>
                Uploaded
                <br><span title="<?php the_time(get_option('date_format')); ?>"><?php echo human_time_diff(get_the_time('U'), current_time('timestamp')) . ' ago'; ?></span>
            </div>
            <div>
                Category
                <br><?php echo ip_get_the_term_list(get_the_ID(), 'imagepress_image_category', '', ', ', '', []); ?>
            </div>
        </div>

        <h3 class="details-box-title">Comments (<?php echo get_comments_number(get_the_ID()); ?>)</h3>
        <?php
        $args = [
            'title_reply' => '',
            'logged_in_as' => '',
        ];
        ?>
        <ol class="commentlist">
            <?php comment_form($args, get_the_ID()); ?>

            <?php    
            //Gather comments for a specific page/post 
            $comments = get_comments([
                'post_id' => get_the_ID(),
                'status' => 'approve',
            ]);

            //Display the list of comments
            wp_list_comments([
                'reverse_top_level' => false,
                'callback' => 'noir_comments',
            ], $comments);
            ?>
        </ol>

        <div class="textwidget">
            <?php
            global $wpdb;

            $result = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "ip_collectionmeta WHERE image_ID = '" . get_the_ID() . "'", ARRAY_A);
            if ($result) {
                echo '<h3 class="details-box-title">Collections</h3>';
            }
            ?>
			<?php ip_frontend_view_image_collection(get_the_ID()); ?>
        </div>
    </div>

    <hr>

    <div class="widget-container widget_text">
        <div class="textwidget" style="text-align: center;">
            <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
            <!-- PosterSpy 300x250 -->
            <ins class="adsbygoogle" style="display:inline-block;width:300px;height:250px" data-ad-client="ca-pub-0187897359531094" data-ad-slot="1421253422"></ins>
            <script>(adsbygoogle = window.adsbygoogle || []).push({});</script>
            <br>
            <a href="https://posterspy.com/ads" target="_blank">Advertisement</a>
        </div>
    </div>
    <hr>

    <div class="widget-container widget_text">
        <div class="textwidget" style="text-align: center;">
            <br>
            <?php
            if (isset($_POST['featureme'])) {
                global $wpdb;
                $act_time = current_time('mysql', true);
                $wpdb->query("INSERT INTO " . $wpdb->prefix . "notifications (ID, userID, postID, actionType, actionTime) VALUES (null, $author_id, " . get_the_ID() . ", 'featured', '" . $act_time . "')");
            }

            if (current_user_can('manage_options')) { ?>
                <form method="post" style="display: inline;">
                    <input type="submit" name="featureme" class="btn btn-primary" value="Feature this poster and notify users">
                </form>
            <?php } ?>
            <br>
        </div>
    </div>

    <?php if (is_active_sidebar('hub-widget-area')) { ?>
        <div id="primary" class="widget-area">
            <?php dynamic_sidebar('hub-widget-area'); ?>
        </div>
    <?php } ?>
</aside>
