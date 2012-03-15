<?php
/**
 * The template for displaying Category Archive pages.
 * Template Name: homepage
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */

get_header(); ?>
    <div class="main">
		<div class="wrap">
				<div class="featured left"><?php the_post_thumbnail('fullsize');?></div>
				<div class="left w580">
					<div class="breadcrumb">
<span class="red arrow">&gt;</span><?php
if(function_exists('bcn_display'))
{
	bcn_display();
}
?>
</div>
					<div class="content p1">
						 <?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
					<?php the_content(); ?>
				<?php endwhile; // end of the loop. ?>
					</div>
					<div class="more" href="#">显示更多</div>
				</div>
				<div class="clear"></div>
	</div>
<?php get_footer(); ?>

