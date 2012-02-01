<?php
/**
 * The template for displaying Category Archive pages.
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
    			    		    		<div class="breadcrumbs">
<?php
if(function_exists('bcn_display'))
{
	bcn_display();
}
?>
</div>
    			<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
    		  <div class="downloaditem">
    		  	<h3><a href="<?php the_permalink() ?>" target="_top"><?php the_title(); ?></a></h3>
    		  	<div class="intro"><?php the_excerpt(); ?> </div>
    		  </div>
    		  <?php endwhile; endif; ?>
			</div>
    	</div>
    	<div class="clear"></div>
    </div>
<?php get_footer(); ?>
