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
				<div class="featured left"><img src="/images/cat11.jpg"/></div>
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
					<ul class="newslist left" style="padding-left:68px;">
							<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
    		  <li>
				<a href="<?php the_permalink() ?>" target="_top"><?php the_title();?></a>
    		  </li>
    		  <?php endwhile; endif; ?>
					</ul>
										<?php wp_pagenavi(); ?>
</div>
					</div>
					<div class="clear"></div>
				</div>
				
	</div>
<?php get_footer(); ?>
