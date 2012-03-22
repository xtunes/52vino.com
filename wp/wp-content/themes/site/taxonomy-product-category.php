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
				<div class="featured left"><img src="/images/cat8.jpg"/></div>
				<div class="left w560 relative">
					<div class="breadcrumb">
<span class="red arrow">&gt;</span><?php
if(function_exists('bcn_display'))
{
	bcn_display();
}
?>
</div>
<div class="productlist">
<?php
$taxonomyName = "product-category";
$term2 = $wp_query->queried_object;
$termID = $term2->term_id;
$termchildren = get_term_children( $termID, $taxonomyName );
$childrennum = count($termchildren);

if ($childrennum==0){
	$term = get_term_by( 'id',$termID, $taxonomyName);
$termparentID=$term->parent;
$termchildren = get_term_children($termparentID, $taxonomyName );
echo '<ul class="underlying">';
foreach ($termchildren as $child) {
	$term = get_term_by( 'id', $child, $taxonomyName );
	echo '<li><a href="' . get_term_link( $term->name, $taxonomyName ) . '">' . $term->name . '</a></li>';
}
echo '</ul>';
}
else{
	echo '<ul class="underlying">';
foreach ($termchildren as $child) {
	$term = get_term_by( 'id', $child, $taxonomyName );
	echo '<li><a href="' . get_term_link( $term->name, $taxonomyName ) . '">' . $term->name . '</a></li>';
}
echo '</ul>';
}
?>
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
    </div>
<?php get_footer(); ?>
