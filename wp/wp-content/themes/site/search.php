<?php
/**
 * The template for displaying Search Results pages.
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
    			<?php if ( have_posts() ) : ?>
    			    		    		<h1 class="page-title"><?php printf( __( 'Search Results for: %s', 'twentyten' ), '<span>' . get_search_query() . '</span>' ); ?></h1>
    		  <ul class="newslist">
    		  	<li><a href="<?php the_permalink() ?>" target="_top"><?php the_title(); ?></a></li>
    		  </ul>
    		  <?php else : ?>
				<div id="post-0" class="post no-results not-found">
					<h1 class="entry-title"><?php _e( 'Nothing Found', 'twentyten' ); ?></h1>
					<div class="entry-content">
						<div><?php _e( 'Sorry, but nothing matched your search criteria. Please try again with some different keywords.', 'twentyten' ); ?></div>
					</div><!-- .entry-content -->
				</div><!-- #post-0 -->
<?php endif; ?>
			</div>
    	</div>
    	<div class="clear"></div>
    </div>
<?php get_footer(); ?>
	