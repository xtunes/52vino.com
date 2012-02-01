<div class="sidebar">
		<div class="sidemenu">
			<?php if ( in_category(array('6','7','8','9')) || is_category(array( '6', '7', '8', '9' ))) {
				wp_nav_menu( array( 'container_class' => 'menu-header', 'menu' => 'download', 'depth' => 2 ) );
			}else if(is_page('product') ||  is_child('product') ||  is_child('39')) {
				wp_nav_menu( array( 'container_class' => 'menu-header', 'menu' => 'product', 'depth' => 2 ) );
			}else if(is_page('download') ||  is_child('download')) {
				wp_nav_menu( array( 'container_class' => 'menu-header', 'menu' => 'download', 'depth' => 2 ) );
			}else if(is_page('technology') ||  is_child('technology') || is_category(array( '12' ))) {
				wp_nav_menu( array( 'container_class' => 'menu-header', 'menu' => 'technology', 'depth' => 2 ) );
			}else if(is_page('education') ||  is_child('education')) {
				wp_nav_menu( array( 'container_class' => 'menu-header', 'menu' => 'education', 'depth' => 2 ) );
			}else if(is_page('cooperation') ||  is_child('cooperation')) {
				wp_nav_menu( array( 'container_class' => 'menu-header', 'menu' => 'cooperation', 'depth' => 2 ) );
			}else if(is_page('contact') ||  is_child('contact')) {
				wp_nav_menu( array( 'container_class' => 'menu-header', 'menu' => 'contact', 'depth' => 2 ) );
			}else if(is_page('about') ||  is_child('about')) {
				wp_nav_menu( array( 'container_class' => 'menu-header', 'menu' => 'about', 'depth' => 2 ) );
			}else{
				
			}
			?>
		</div>
		<div class="shortcut">
			<h2 class="green">快捷通道</h2>
			<?php wp_nav_menu( array( 'container_class' => 'menu-header', 'menu' => 'shortcut' ) ); ?>
		</div>
		<div class="friendlylinks">
			<h2>友情链接</h2>
			<ul>
				<li><a href="http://www.shanghaifeiyi.cn">上海飞熠软件技术有限公司</a></li>
				<li><a href="http://www.designbuilder.co.uk">DesignBuilder Software Ltd</a></li>
			</ul>
		</div>
</div>