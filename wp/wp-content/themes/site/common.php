<?php
/**
 * Template Name: common

 */

get_header();?>
   <div id="main">
   <?php get_sidebar(); ?>
        <div id="content">
           <?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
					<?php the_content(); ?>
			<?php endwhile; // end of the loop. ?>
        </div>
        <div class="clear"></div>
    </div>
<?php get_footer(); ?>