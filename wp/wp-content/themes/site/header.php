<!doctype html>
<!-- paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/ -->
<!--[if lt IE 7 ]> <html class="no-js ie6" lang="en"> <![endif]-->
<!--[if IE 7 ]>    <html class="no-js ie7" lang="en"> <![endif]-->
<!--[if IE 8 ]>    <html class="no-js ie8" lang="en"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <base href="<?php echo get_option('home','http://52vino.com/') ?>">
  <title><?php
	/*
	 * Print the <title> tag based on what is being viewed.
	 */
	global $page, $paged;

	wp_title( '|', true, 'right' );

	// Add the blog name.
	bloginfo( 'name' );

	// Add the blog description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		echo " | $site_description";

	// Add a page number if necessary:
	if ( $paged >= 2 || $page >= 2 )
		echo ' | ' . sprintf( __( 'Page %s', 'twentyten' ), max( $paged, $page ) );

	?></title>
  <meta name="description" content="">
  <meta name="author" content="xtunes.cn">
  <!-- Mobile viewport optimized: j.mp/bplateviewport -->
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- Place favicon.ico & apple-touch-icon.png in the root of your domain and delete these references -->
  <link rel="shortcut icon" href="/favicon.ico">
  <link rel="apple-touch-icon" href="/apple-touch-icon.png">
  <link rel="stylesheet" type="text/css" media="all" href="css/style.css" />
  <link rel="stylesheet" type="text/css" href="css/ddsmoothmenu.css" />
  <!-- All JavaScript at the bottom, except for Modernizr which enables HTML5 elements & feature detects -->
  <script src="js/libs/modernizr-1.7.min.js"></script>
<?php
	/* Always have wp_head() just before the closing </head>
	 * tag of your theme, or you will break many plugins, which
	 * generally use this hook to add elements to <head> such
	 * as styles, scripts, and meta tags.
	 */
	wp_head();
?>
<script type="text/javascript" src="js/ddsmoothmenu.js"></script>
<script type="text/javascript">

ddsmoothmenu.init({
	mainmenuid: "menu", //menu DIV id
	orientation: 'h', //Horizontal or vertical menu: Set to "h" or "v"
	classname: 'ddsmoothmenu', //class added to menu's outer DIV
	//customtheme: ["#1c5a80", "#18374a"],
	contentsource: "markup" //"markup" or ["container_id", "path_to_menu_file"]
})

</script>
</head>

<body class="page" id="<?php the_ID(); ?>">
	<div class="header">
			<div class="wrap relative">
				<div class="logo">
					<a href="/"><img src="/images/logo.png"></a>
				</div>
				<div class="topbar">
					<div class="searchform left">	<form action="/" id="searchform" method="get">
		<input type="text" placeholder="搜索" id="s" name="s" class="field">
		<input type="submit" value="搜索" id="searchsubmit" name="submit" class="submit">
	</form>
					</div>
					<a href="#"><img src="/images/zhanghu.png"></a>
					<a href="#"><img src="/images/cart.png"></a>
				</div>
				<div id="menu">
					<ul>
						<li><a href="#">热点&nbsp;·&nbsp;活动</a>
							<ul>
								<li><a class="s1" href="/?page_id=25">新闻快报</a></li>								
								<li><a class="s2" href="/?page_id=21">活动日程表</a></li>
								<li><a class="s3" href="/?page_id=23">培训课程表</a></li>								
							</ul>
						</li>
						<li><a href="#">理念&nbsp;·&nbsp;优势</a>
							<ul>
								<li><a class="s4" href="/?page_id=16">品牌缘起</a></li>								
								<li><a class="s5" href="/?page_id=18">团队介绍</a></li>
								<li><a class="s6" href="/?page_id=19">合作伙伴</a></li>
							</ul>
						</li>
						<li><a href="#">酒品&nbsp;·&nbsp;精选</a>
							<ul>
								<li><a class="s7" href="/?cat=4">法国葡萄酒</a></li>
								<li><a class="s8" href="#">新世界葡萄酒</a></li>
								<li><a class="s9" href="#">礼品酒</a></li>
								<li><a class="s10" style="margin-left:-40px" href="#">日常饮用</a></li>
							</ul>
						</li>
						<li><a href="#">行家&nbsp;·&nbsp;解密</a></li>
						<li><a href="#">酒铺&nbsp;·&nbsp;服务</a>
							<ul>
								<li><a class="s11" href="/?page_id=18">礼品中心</a></li>
								<li><a class="s12" href="/?page_id=19">网上店铺</a></li>								
							</ul>
						</li>
					</ul>
				</div>
			</div>
		</div>