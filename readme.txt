=== Let's Mix Shortcode ===
Contributors: talus, Ralph83
Donate link: http://www.installedforyou.com/letsmix-shortcode
Tags: shortcode, lets mix, letsmix, quicktag
Requires at least: 2.8
Tested up to: 3.1.1
Stable tag: 1.0

Test1The Let's Mix Shortcode plugin allows you to integrate a player widget with tracklist from Let's Mix into your Wordpress Blog by using a Wordpress shortcode.

== Description ==

TestThe Let's Mix Shortcode plugin allows you to easily integrate a player widget with tracklist from Let's Mix into your Wordpress Blog by using a Wordpress shortcode.

Usage in your posts:

[letsmix]http://www.letsmix.com/mix/MIXID/MIX_NAME[/letsmix]

*Please Note*: If you put the URL or Mix ID within the opening tag like:

[letsmix url=http://www.letsmix.com/mix/MIXID/MIX_NAME]

OR

[letsmix mix_id=MIXID]

...then you should *not* use the closing tag: [/letsmix]

It also supports the following optional parameters:

* size=(big (=default), tall or wide)
* autostart=(no, yes, false, true, 1, 0 (=default))
* width=(numeric or percentage (e.g. 500, 75%), gets overwritten if size parameter is used)
* height=(numeric or percentage (e.g. 500, 75%), gets overwritten if size parameter is used)
* tracklist=(no, yes (=default), false, true, 1, 0)

Examples using parameters:

[letsmix size=wide]http://www.letsmix.com/mix/MIXID/MIX_NAME[/letsmix]
Embeds a wide player.

[letsmix size=tall tracklist=no autostart=yes]MIXID[/letsmix]
Embeds a tall player which autoplays (once webpage containing embedded player is loaded) and no tracklist.

[letsmix url=http://www.letsmix.com/mix/MIXID/MIX_NAME tracklist=0]
Embeds the default player (big) and no tracklist.

[letsmix mix_id=MIXID height=250 width=500 tracklist=false]
Embeds a player with custom dimensions and no tracklist.

== Installation ==

1. Upload letsmix-shortcode folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Use the shortcode in your posts.

== Frequently Asked Questions ==


== Screenshots ==

1. Shows embedded player with a tracklist.
2. Shows 'Visual' post editor with quicktag button.
3. Shows 'HTML' post editor with quicktag button.

== Changelog ==

= 1.0 =
* First version

