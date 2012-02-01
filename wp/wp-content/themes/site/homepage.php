<?php
/**
 * Template Name: 首页

 */

get_header(); ?>
    	<div class="main">
			<div class="wrap">
				 <?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
					<?php the_content(); ?>
					<?php endwhile; // end of the loop. ?> 
			</div>
		</div>
<?php get_footer(); ?>
