<footer class="entry-footer">
<span class="cat-links">Categories: <?php the_category(', '); ?></span>
<?php if ( comments_open() ) { 
echo '<span class="meta-sep">|</span> <span class="comments-link"><a href="' . get_comments_link() . '">Comments</a></span>';
} ?>
</footer>
