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
				<div class="featured left"><img src="/images/cat4.jpg"/></div>
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
						<div class="wine">
<div class="left origin">
<div class="bordeaux link">波尔多</div>
<div class="burgundy link">勃艮第</div>
<div class="champagne link">香槟</div>
<div class="alsace link">阿尔萨斯</div>
<div class="southfrance link">法国南部</div>
</div>
<div class="faguo">
<a class="bordeaux spot" href="/?product-category=bordeaux">1</a>
<a class="burgundy spot" href="#">2</a>
<a class="champagne spot" href="#">3</a>
<a class="alsace spot" href="#">4</a>
<a class="southfrance spot" href="#">5</a>
</div>
<div class="clear"></div>
<div class="originintro">
<div class="bordeaux ph" style="display:none;">波尔多（Bordeaux），法国西南的一个港口城市，人口约93万。是法国第四大城市，位列巴黎，里昂，马赛之后。是阿基坦大区的首府，同时也是吉伦特省的首府。曾是法国旧省吉耶纳的首府，历史上属加斯科涅地区。波尔多地区旅游资源丰富，有许多风景优美保存完好的中世纪城堡。波尔多因此也被称为世界葡萄酒中心，每两年一度，波尔多酒协会举办盛大得国际酒展-Vinexpo。</div>
<div class="burgundy ph" style="display:none;">勃艮第</div>
<div class="champagne ph" style="display:none;">香槟</div>
<div class="alsace ph" style="display:none;">阿尔萨斯</div>
<div class="southfrance ph" style="display:none;">法国南部</div>
</div>
</div>
					</div>
				</div>
				<div class="clear"></div></div>	
	</div>
<?php get_footer(); ?>
