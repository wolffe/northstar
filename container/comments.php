<?php if ( 'comments.php' == basename( $_SERVER['SCRIPT_FILENAME'] ) ) return; ?>
<section id="comments">
<?php 
if ( have_comments() ) : 
global $comments_by_type;
$comments_by_type = separate_comments( $comments );
if ( ! empty( $comments_by_type['comment'] ) ) : 
?>
<section id="comments-list" class="comments">
<h3 class="comments-title"><?php comments_number(); ?></h3>
<?php if ( get_comment_pages_count() > 1 ) : ?>
<nav id="comments-nav-above" class="comments-navigation" role="navigation">
<div class="paginated-comments-links"><?php paginate_comments_links(); ?></div>
</nav>
<?php endif; ?>
<ul>
<?php wp_list_comments('type=comment&reverse_top_level=true&callback=noir_comments'); ?>
</ul>
<?php if ( get_comment_pages_count() > 1 ) : ?>
<nav id="comments-nav-below" class="comments-navigation" role="navigation">
<div class="paginated-comments-links"><?php paginate_comments_links(); ?></div>
</nav>
<?php endif; ?>
</section>
<?php 
endif; 
if ( ! empty( $comments_by_type['pings'] ) ) : 
$ping_count = count( $comments_by_type['pings'] ); 
?>
<section id="trackbacks-list" class="comments">
<h3 class="comments-title"><?php echo '<span class="ping-count">' . $ping_count . '</span> ' . ( $ping_count > 1 ? __( 'Trackbacks', 'blankslate' ) : __( 'Trackback', 'blankslate' ) ); ?></h3>
<ul>
<?php wp_list_comments( 'type=pings&callback=blankslate_custom_pings' ); ?>
</ul>
</section>
<?php 
endif; 
endif;

$args = [
    'comment_field' =>  '<p class="comment-form-comment"><textarea id="comment" name="comment" cols="45" rows="8" aria-required="true">' . '</textarea></p>',
    'must_log_in' => '<p class="must-log-in">' . sprintf(__('You must be <a href="%s">logged in</a> to post a comment.'), home_url() . '/login/') . '</p>',
    'logged_in_as' => '',
    'comment_notes_after' => '',
    'title_reply'       => __( '<i class="fa fa-comment"></i> Leave a comment' ),
    'title_reply_to'    => __( 'Reply to %s' ),
];

if ( comments_open() ) comment_form($args);
?>
</section>