<?php
/**
 * The Template for displaying all single posts.
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */

get_header(); ?>
    <div id="main" class="wrap">
    	<?php get_sidebar(); ?>
    	<div class="rightpart">
    		<div class="content">
    		   <?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
					<?php the_content(); ?>
				<?php endwhile; // end of the loop. ?>
			</div>
    	</div>
    	<div class="clear"></div>
    </div>

<?php get_footer(); ?>
