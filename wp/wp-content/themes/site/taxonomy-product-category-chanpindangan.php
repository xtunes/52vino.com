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
    		<div class="innerwrap">
				<div class="featured left"><img src="/images/cat20.jpg"/></div>
				<div class="left w560 relative">
					<div class="breadcrumb">
<span class="red arrow">&gt;</span><?php
if(function_exists('bcn_display'))
{
	bcn_display();
}
?>
</div>

<div class="productlist lipinjiu">
	<img src="/images/pic2.gif"/>
<div style="margin-top:30px;">
		    			<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
    		  <div class="item2">
				<div class="title"><?php the_title()?></div>
				<div class="info"><?php the_content()?></div>
    		  </div>
    		  <?php endwhile; endif; ?>
</div>
<?php wp_pagenavi(); ?>
    		      	<div class="clear"></div>
</div>
			</div>
    	</div>
    	<div class="clear"></div>
    	</div>
    </div>
<?php get_footer(); ?>
