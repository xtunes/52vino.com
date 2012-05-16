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
				<div class="featured left"><img src="/images/cat9.jpg"/></div>
				<div class="left w560">
					<div class="breadcrumb">
<span class="red arrow">&gt;</span><?php
if(function_exists('bcn_display'))
{
	bcn_display();
}
?>
</div>
<div class="content">
<div class="newslist left cat9-2" style="padding-left:68px;">
	<div class="newscattitle"><?php the_category(); ?></div>
	<img src="/images/new.gif"/>
	<?php $i = 0?>
    			<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
    		  <div class="item">
				<a href="<?php the_permalink() ?>" target="_top" class="<?php if($i<2) echo 'red' ?>"><?php the_title();?></a>
    		  </div>
    		  <?php $i++; endwhile; endif; ?>
    		      	<div class="clear"></div>
</div>
					<div class="right"><img src="/images/cat9-2.jpg" width="135" height="197"/></div>
										<?php wp_pagenavi(); ?>
</div>
			</div>
    	</div>
    	<div class="clear"></div>
    </div>
<?php get_footer(); ?>
