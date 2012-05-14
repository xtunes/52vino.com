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
				<div class="featured left"><img src="/images/cat18.jpg"/></div>
				<div class="left w560 relative">
					<div class="breadcrumb">
<span class="red arrow">&gt;</span><?php
if(function_exists('bcn_display'))
{
	bcn_display();
}
?>
</div>
<div class="productlist mingzhuangjiu">
<div class="side">
	<h2>1855年左岸酒庄分级</h2>
	<ul>
		<li><a class="current" href="/?product-category=fgc">一级列级酒庄 First Growths Commune</a></li>
		<li><a href="/?product-category=sgc">二级列级酒庄 Second Growths Commune</a></li>
		<li><a href="/?product-category=tgc">三级列级酒庄 Third Growths Commune</a></li>
		<li><a href="/?product-category=fougc">四级列级酒庄 Fourth Growths Commune</a></li>
		<li><a href="/?product-category=fifgc">五级列级酒庄 Fifth Growths Commune</a></li>
	</ul>
	<h2>1955年右岸酒庄分级</h2>
	<ul>
		<li><a href="/?product-category=arank">一级列级酒庄 A级</a></li>
		<li><a href="/?product-category=brank">一级列级酒庄 B级</a></li>
		<li><a class="current" href="/?product-category=rank">列级酒庄</a></li>
	</ul>
</div>
<div class="mingzhuangjiulist">
		    			<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
    		  <div class="">
				<a href="<?php the_permalink() ?>" target="_top"><?php the_title()?></a>
    		  </div>
    		  <?php endwhile; endif; ?>
</div>
    		      	<div class="clear"></div>
</div>
			</div>
    	</div>
    	<div class="clear"></div>
    	</div>
    </div>
<?php get_footer(); ?>
