=== Recommended Links ===
Contributors: Ven Francis Dano-og
Author URI: http://ven.revereasia.com/
Plugin URI: http://ven.revereasia.com/wordpress/plugins/recommended-links
Tags: recommend, link, blogroll, featured, links, recommended
Requires at least: 3.0
Tested up to: 3.6
Stable tag: 2.5.9
License: GPLv2 or later

A simple plugin for your recommended links.

== Description ==

Best for users that likes to show where they have positively reviewed, featured,
and awarded from other sites. The plugin adds a rel="nofollow" attribute allows you to
tell the search engines that you do not want the link to pass any link value (a.k.a. "PageRank"),
that it should not be counted as an endorsement of the target page. This eliminates the
value of the link for spammers, which is why Google invented it in the first place.

== Installation ==

Upload the plugin in your blog then activate it.

== Usage ==

Use it with a dynamic_sidebar:

	<?php if ( is_active_sidebar( 'sidebar-custom' ) ) : ?>
		<div id="secondary" class="widget-area" role="complementary">
			<?php dynamic_sidebar( 'sidebar-custom' ); ?>
		</div><!-- #secondary -->
	<?php endif; ?>