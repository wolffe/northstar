<!doctype html>
<html lang="en-GB">
<head>
<meta http-equiv="x-ua-compatible" content="ie=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">

<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>

<?php wp_head(); ?>

</head>
<body <?php body_class(); ?>>

<?php if(isset($_GET['deleted'])) { ?>
    <div class="message noir-success">Image removed!</div>
<?php } ?>
<div class="mmenu">
    <span class="mmenu-trigger montserrat-regular" style="padding: 4px 16px; cursor: pointer; color: #ffffff;"><i class="fa fa-times"></i> Close</span>
    <?php if(is_active_sidebar('responsive-menu-widget-1')) dynamic_sidebar('responsive-menu-widget-1'); ?>
    <form class="responsive-search" role="search" method="get" id="searchform" action="https://posterspy.com/"><input type="text" placeholder="Search posters..." name="s" id="s"></form>
    <?php wp_nav_menu(['theme_location' => 'responsive-menu', 'container' => false]); ?>
    <?php if(is_active_sidebar('responsive-menu-widget-2')) dynamic_sidebar('responsive-menu-widget-2'); ?>
</div>
<div class="overlay">

<?php // Main menu bar ?>
<div class="nav-container"></div>
<div class="hmenu">
    <ul class="shiny-menu">
        <li class="mobile-only"><span class="mmenu-trigger"><a href="#"><i class="fa fa-bars"></i></a></span></li>

        <li><a href="https://posterspy.com/" class="menu-logo-large-noes no-border"><img src="https://posterspy.com/wp-content/uploads/2018/03/Logo-no-icon.png" alt="" height="40"></a></li>
        <li><div class="search menu-search"><form role="search" method="get" id="searchform" action="https://posterspy.com/" style="display:inline"><input type="text" placeholder="Search PosterSpy" name="s" id="s"><input type="hidden" name="post_type" value="poster"></form></div></li>

        <?php if(is_user_logged_in()) { ?>
            <li><a href="https://posterspy.com/upload/" class="btn btn-primary"><i class="fa fa-upload"></i> Upload</a></li>
        <?php } ?>

        <?php if(!is_user_logged_in()) { ?>
            <li class="mobile-only menu-mobile-right"><a href="https://posterspy.com/login/" class="no-border"><i class="fa fa-sign-in" aria-hidden="true"></i> Log in</a></li>
        <?php } ?>
    </ul>

    <ul class="shiny-menu shiny-menu-right">
        <?php //if (isset($_GET['beta'])) { ?>
            <li><a href="https://posterspy.com/"><i class="fa fa-home"></i> Home</a></li>
        <?php //} ?>
        <li><a href="https://posterspy.com/posters/" class="browse-posters"><i class="fa fa-globe"></i> Browse</a></li>
        <li><a href="https://posterspy.com/artists/" class="browse-artists"><i class="fa fa-user"></i> Artists</a></li>
        <li><a href="https://posterspy.com/all-collections/" class="browse-collections"><i class="fa fa-th-large"></i> Collections</a></li>
	<li><a href="https://posterspy.com/creative-briefs" class="browse-creative-briefs"><i class="fa fa-pencil"></i> Creative Briefs</a></li>
	<li><a href="https://posterspy.com/store" class="browse-store"><i class="fa fa-shopping-cart"></i> Store</a></li>
        <li><a href="#" class="no-border"><i class="fa fa-fw fa-bars"></i></a>
            <ul>
                <li><a href="https://posterspy.com/about/" class="no-border"><i class="fa fa-fw fa-info-circle"></i> About</a></li>
                <li><a href="https://posterspy.com/advertise/" class="no-border"><i class="fa fa-fw fa-rocket"></i> Advertise</a></li>
                <li><a href="mailto:contact@posterspy.com" class="no-border"><i class="fa fa-fw fa-envelope"></i> Contact</a></li>
                <li><hr></li>
		<li><a href="https://posterspy.com/category/magazine/" class="no-border"><i class="fa fa-fw fa-newspaper-o"></i> Magazine</a></li>
		<li><a href="http://eepurl.com/biz1pP"target="_blank"class="no-border"><i class="fa fa-fw fa-envelope-o"></i> Newsletter</a></li>
		<li><hr></li>
                <li><a href="https://www.facebook.com/PosterSpy"target="_blank" class="no-border"><i class="fa fa-fw fa-facebook-official"></i> Facebook</a></li>
                <li><a href="https://twitter.com/posterspy"target="_blank" class="no-border"><i class="fa fa-fw fa-twitter"></i> Twitter</a></li>
                <li><a href="https://instagram.com/posterspy/"target="_blank" class="no-border"><i class="fa fa-fw fa-instagram"></i> Instagram</a></li>
	</ul>
        </li>

        <?php
        $current_user = wp_get_current_user();
        $cid = $current_user->ID;
        ?> 
        <?php if(is_user_logged_in()) { ?>
            <li><div class="bell-pepper"><?php echo ip_notifications_menu_item(); ?></div></li>

            <li><a href="<?php echo get_author_posts_url($cid); ?>" class="no-border"><?php echo get_avatar($cid, 32); ?></a>
                <ul>
                    <li><a href="<?php echo get_author_posts_url($cid); ?>" class="no-border"><i class="fa fa-fw fa-user"></i> My Profile</a></li>
                    <li><a href="https://posterspy.com/profile/" class="no-border"><i class="fa fa-fw fa-cog"></i> Profile Settings</a></li>
                    <li><a href="<?php echo wp_logout_url('https://posterspy.com/'); ?>" class="no-border"><i class="fa fa-fw fa-sign-out"></i> Log Out</a></li>
                </ul>
            </li>
        <?php } ?>

        <?php if(!is_user_logged_in()) { ?>
            <li><a href="https://posterspy.com/login/?action=register">Sign up</a></li>
            <li><a href="https://posterspy.com/login/" class="no-border">Log in</a></li>
        <?php } ?>
    </ul>
</div>

<?php if(is_front_page() && !is_user_logged_in()) { ?>
    <div class="onboarding">
        <h4>Join the leading showcase platform for poster artists<br>and discover creative opportunities in the entertainment industry</h4>
        <a class="btn btn-primary" href="https://posterspy.com/login/?action=register">Get Started</a>
        <br>
        <a class="link-gray" href="https://posterspy.com/login/">Log in</a>
        <div class="onboarding-overlay"></div>
    </div>
<?php } ?>

<div id="wrap">
    <div id="wrapper" class="hfeed">
        <div id="container">