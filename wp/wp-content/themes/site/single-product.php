<?php
/**
 * The Template for displaying all single posts.
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */

get_header(); ?>
  <div class="main">
		<div class="wrap">
						  <?php				    
             /****************************************************************************************************
                http://wordpress.org/support/topic/plugin-custom-field-template-use-media-picker-and-output-image 
             *****************************************************************************************************/
             $postid = $post->ID;
             $imageid = get_post_meta($postid,'image',true);
             $imageurl = wp_get_attachment_image_src($imageid, 'full');
             if(empty($imageurl)){
               $imageurl = "/images/thumb.gif";
             }else{
               $imageurl = $imageurl[0];
             }
             ?>
				<div class="featured left"><img src="<?php echo $imageurl; ?>"/></div>
				<div class="left w560">
					<div class="breadcrumb">
<span class="red arrow">&gt;</span><?php
if(function_exists('bcn_display'))
{
	bcn_display();
}
?>
</div>
					<div class="product">
						 <?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
					<?php the_content(); ?>
				<?php endwhile; // end of the loop. ?>
					</div>
				</div>
				<div class="clear"></div>
	</div>

<?php get_footer(); ?>
