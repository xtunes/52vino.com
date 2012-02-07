<?php
/**
 * The template for displaying Category Archive pages.
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */

get_header(); ?>
    <div class="main">
    	<div class="wrap">
				<div class="featured left"><img src="/images/cat8.jpg"/></div>
				<div class="left w560">
					<div class="breadcrumb">
<span class="red arrow">&gt;</span><?php
if(function_exists('bcn_display'))
{
	bcn_display();
}
?>
</div>
<div class="productlist">
    			<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
    		  <div class="item">
				<a href="<?php the_permalink() ?>" target="_top"><?php the_post_thumbnail('fullsize');?></a>
    		  </div>
    		  <?php endwhile; endif; ?>
    		      	<div class="clear"></div>
</div>
			</div>
    	</div>
    	<div class="clear"></div>
    </div>
<?php get_footer(); ?>
