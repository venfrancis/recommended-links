=== Recommended Links ===
Contributors: Ven Francis Dano-og
Author URI: http://ven.revereasia.com/
Plugin URI: https://github.com/venfrancis/recommended-links/
Tags: recommend, link, blogroll, featured, links, recommended
Requires at least: 3.0
Tested up to: 3.6
Stable tag: 2.5.9
License: GPLv2 or later

A simple plugin for your recommended links.

== Description ==

Best for users that wish to show where they had positively reviewed, featured,
and awarded from other sites in a two column layout (http://ven.revereasia.com/projects/wordpress/plugins/recommended-links/screenshot/rc-link.png). The plugin
adds a rel="nofollow" attribute allows you to tell the search engines that
you do not want the link to pass any link value (a.k.a. "PageRank"), that it should
not be counted as an endorsement of the target page. This eliminates the value of
the link for spammers, which is why Google invented it in the first place.

== Installation ==

Upload the plugin in your blog then activate it.

== Usage ==

Use it within a widgetized area or call through the_widget (http://codex.wordpress.org/Function_Reference/the_widget) function.

example:

	<?php the_widget( 'recommended_links_home_widget', 'title=Good Reads' ); ?>


View it in action: http://www.immap.com.ph/
