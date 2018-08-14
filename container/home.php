<?php get_header(); ?>

<!--// HOME /-->
<section id="content-wide" role="main">
	<div style="margin: 32px 0;">
		<h1 style="text-transform: uppercase; font-weight: 300; font-size: 44px; text-align: center; margin: 0;">Welcome to <span style="font-weight: 700;" class="teal">PosterSpy</span></h1>
		<h2 style="text-transform: uppercase; text-align: center; font-weight: 300; font-size: 14px;">A global community for poster artists and poster art lovers. <b>Upload and Explore.</b></h2>
	</div>

<h1 style="font-weight: 300; font-size: 25px;">PosterSpy News <small><i class="far fa-newspaper"></i></small></h1>

	<section class="category-tabs">
		<div id="tabs">
			<ul id="tabs-nav">
				<li><a href="#tab1">Magazine</a></li>
				<li><a href="#tab2">Interviews</a></li>
				<li><a href="#tab3">Q&amp;A's</a></li>
			</ul>

			<div id="tabs-content">
				<div id="tab1" class="content">
					<div class="infinite-carousel infinite-carousel-upcoming">
						<div class="viewport">
							<div class="list">
								<?php
								$args = [
									'category_name' => 'magazine',
									'posts_per_page' => 99
								];
								$the_query = new WP_Query($args);

								if($the_query->have_posts()) {
									while($the_query->have_posts()) {
										$the_query->the_post();

										//global $post_meta;
										//$post_meta->the_meta();
										?>
											<?php $featured = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'featured-thumbnail'); ?>
											<div class="item" style="background: url(<?php echo $featured[0]; ?>) no-repeat center center;">
												<div class="inlay">
													<div class="date"><i class="fas fa-calendar"></i></div>
													<div class="category"><?php the_time(get_option('date_format')); ?></div>
													<div class="title"><a href="<?php the_permalink(); ?>"><?php echo get_the_title(); ?></a></div>
												</div>
											</div>
										<?php
									}
								} else {
									// no posts found
								}

								wp_reset_postdata();
								?>
							</div>
						</div>
						<button class="pre"><i class="fas fa-chevron-left"></i></button>
						<button class="next"><i class="fas fa-chevron-right"></i></button>
					</div>
				</div>
				<div id="tab2" class="content">
					<div class="infinite-carousel infinite-carousel-bestsellers">
						<div class="viewport">
							<div class="list">
								<?php
								$args = [
									'category_name' => 'interviews',
									'posts_per_page' => 99
								];
								$the_query = new WP_Query($args);

								if($the_query->have_posts()) {
									while($the_query->have_posts()) {
										$the_query->the_post();
										?>
											<?php $featured = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'featured-thumbnail'); ?>
											<div class="item" style="background: url(<?php echo $featured[0]; ?>) no-repeat center center;">
												<div class="inlay">
													<div class="date"><i class="fas fa-calendar"></i></div>
													<div class="category"><?php the_time(get_option('date_format')); ?></div>
													<div class="title"><a href="<?php the_permalink(); ?>"><?php echo get_the_title(); ?></a></div>
												</div>
											</div>
										<?php
									}
								} else {
									// no posts found
								}

								wp_reset_postdata();
								?>
							</div>
						</div>
						<button class="pre"><i class="fas fa-chevron-left"></i></button>
						<button class="next"><i class="fas fa-chevron-right"></i></button>
					</div>
				</div>
				<div id="tab3" class="content">
					<div class="infinite-carousel infinite-carousel-new">
						<div class="viewport">
							<div class="list">
								<?php
								$args = [
									'category_name' => 'qas',
									'posts_per_page' => 99
								];
								$the_query = new WP_Query($args);

								if($the_query->have_posts()) {
									while($the_query->have_posts()) {
										$the_query->the_post();
										?>
											<?php $featured = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'featured-thumbnail'); ?>
											<div class="item" style="background: url(<?php echo $featured[0]; ?>) no-repeat center center;">
												<div class="inlay">
													<div class="date"><i class="fas fa-calendar"></i></div>
													<div class="category"><?php the_time(get_option('date_format')); ?></div>
													<div class="title"><a href="<?php the_permalink(); ?>"><?php echo get_the_title(); ?></a></div>
												</div>
											</div>
										<?php
									}
								} else {
									// no posts found
								}

								wp_reset_postdata();
								?>
							</div>
						</div>
						<button class="pre"><i class="fas fa-chevron-left"></i></button>
						<button class="next"><i class="fas fa-chevron-right"></i></button>
					</div>
				</div>
			</div>
		</div>
	</section>
	
	<h1 style="font-weight: 500; font-size: 32px;">Staff Favourites <small><i class="fas fa-star yellow"></i></small></h1>
	
	<?php echo do_shortcode('[imagepress-show category="featured" limit="7" count="7" type="random" size="imagepress_pt_sm"]'); ?>
	<h4><p class="right"><a href="<?php echo home_url(); ?>/all-posters/staff-favourites">Browse all Staff Favourites</a> <i class="fas fa-arrow-circle-right"></i></p></h4>

	<h1 style="font-weight: 500; font-size: 25px;">Recent Submissions <small><i class="fas fa-upload"></i></small></h1>
	
	<?php echo do_shortcode('[imagepress-show limit="21" count="21" size="imagepress_pt_sm" sort="yes"]'); ?>
	<h4><p class="right"><a href="<?php echo home_url(); ?>/all-posters/">Browse all uploads</a> <i class="fas fa-arrow-circle-right"></i></p></h4>
</section>




<?php get_footer(); ?>