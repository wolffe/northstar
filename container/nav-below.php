<?php
global $wp_query;

if($wp_query->max_num_pages > 1) { ?>
    <nav id="nav-below" class="navigation" role="navigation">
        <div class="nav-next"><?php previous_posts_link('<i class="fas fa-chevron-left"></i> Back'); ?></div>
        <div class="nav-previous"><?php next_posts_link('Next <i class="fas fa-chevron-right"></i>'); ?></div>
    </nav>
    <div class="nav-below-stats">
        <?php
        if(get_query_var('paged') == 0) {
            $p = 1;
        } else {
            $p = get_query_var('paged');
        }
        ?>
        <p>Currently displaying page <b><?php echo $p; ?></b> of <b><?php echo $wp_query->max_num_pages; ?></b></p>
        <?php if($p > 1 && !is_search()) { ?>
            <p><a href="<?php echo preg_replace("/(\/page\/)\w+/", '', strtok($_SERVER['REQUEST_URI'], '?')); ?>">Return to page 1</a></p>
        <?php } ?>
    </div>
<?php } ?>