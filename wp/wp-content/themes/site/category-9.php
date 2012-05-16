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
					<div class="content cat9">
					<ul class="newslist left" style="padding-left:68px;">
						<?php
$taxonomyName = "category";
$term2 = $wp_query->queried_object;
$termID = $term2->term_id;
$termchildren = get_term_children( $termID, $taxonomyName );
$childrennum = count($termchildren);	
foreach ($termchildren as $child) {
	echo '<div style="padding-bottom:24px;">';
	$term = get_term_by( 'id', $child, $taxonomyName );
	echo '<div class="newscattitle"><a href="' . get_term_link( $term->name, $taxonomyName ) . '">' . $term->name . '</a></div>';	
	echo '<img src="/images/new.gif"/>';
	$termID2=$term->term_id;
	query_posts(array( 'cat' => $termID2, 'posts_per_page' => 2, 'orderby' => 'title', 'order' => 'DESC' )  );
// The Loop
while ( have_posts() ) : the_post();
	echo '<div><a class="red" href="' . post_permalink() . '">';
	the_title();
	echo '</a></div>';
endwhile;

// Reset Query
wp_reset_query();
echo '</div>';
}
?>
						
					</ul>
					<div class="right"><img src="/images/cat9-2.jpg"/></div>
</div>
					</div>
				<div class="clear"></div>
								</div>
	</div>
<?php get_footer(); ?>
