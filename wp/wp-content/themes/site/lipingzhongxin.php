<?php
/**
 * Template Name: 礼品中心

 */

get_header(); ?>
    	<div class="main">
			<div class="wrap">
				<div class="breadcrumb jiu">
					<img style="float:left" src="/images/jiu.png"><span class="red arrow">&gt;</span>礼品中心					
				</div>
				 <?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
					<?php the_content(); ?>
					<?php endwhile; // end of the loop. ?> 
			</div>
		</div>
<?php get_footer(); ?>
