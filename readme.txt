=== MU Global Options Plugin ===
Tags: MPMU, MU, Global, Options, Akismet.
Donate link: https://online.nwf.org/site/Donation2?df_id=6620&6620.donation=form1
Requires at least: 3.0
Tested up to: 3.0.1
Contributors: Keith Graham
Stable tag: 0.8

Plugin that uses options from main blog in other blogs.

== Description ==
Plugin that uses options from main blog in other blogs.

This allows all blogs on a network to use the options set on the Main Blog (blog #1). The Wordpress administrator can check off which options are to be shared among blogs. Almost all the current options are listed by name. Once the options have been checked and the plugin updated then all other blogs will see the option value set in the main blog.

This is useful for networked blogs where some options do not change across the blogs.

The primary reason for this plugin is to let all blogs use the same Wordpress API key, so that Akismet does not have to be configured on each blog, and it is not necessary to hack WordPress files to share Akismet.

The global options plugin also works with the All-in-one-SEO plugin, but the plugin will still show a red warning on the administration pages. This is because the this package checks options when it is loaded by Wordpress and not when the admin panel is initialized. The plugin works, in spite of the warning.

There is a Read/Write check box that allows the other blogs to change change the global settings. This is useful for collecting global statistics, but is very dangerous if you allow your users access to the admin panel. Use it with extreme care or if you are the only user who has access to the admin panels on the blogs.

I have tested it with other plugins and it seems to work consistently with all of them, but I can't be sure that any one plugin will work.

I have changed some other WordPress options such as the permalink structure using this plugin and it works without problems, but be very careful changing any WordPress options that might be site specific, such as blog name.

The process of looking up an option on another blog is not trivial, but I did not notice much degradation in response time during testing. Large or busy installations my see a change in performance.

I consider this plugin to be potentially dangerous for the casual user. If you get in trouble uninstall the plugin from the main blog or delete the plugin from the plugins directory.
  
== Installation ==
1. Download the plugin.
2. Upload the plugin to your wp-content/plugins directory.
3. Network Activate the plugin if you wish to use in all blogs. If only selected blogs are to be used then activate on main blog and individual blogs.
4. On you main blog go to the settings menu for the MU Global Options Plugin and check off the options that you wish to globalize. Update.
5. Test on other blogs make sure there are no side effects.


== Changelog ==

= 0.5 =
* initial release to test code 

= 0.6 =
* fixed a bug in read/write options 

= 0.7 =
* corrected the last fixed a bug in read/write options 

= 0.8 =
* Fixed options not initializing correctly

== Support ==
This plugin is in active development. All feedback is welcome on "<a href="http://www.blogseye.com/" title="Wordpress plugin: Stop Spammer Registrations Plugin">program development pages</a>".
This plugin is free and I expect nothing in return. However, a link on your blog to one of my personal sites would be appreciated.
<a target="_blank" href="http://www.westnyackhoney.com/blog">Local West Nyack Honey</a> (I raise bees and sell the honey)
<a target="_blank" href="http://www.cthreepo.com/blog">Wandering Blog </a> (My personal Blog)
<a target="_blank"  href="http://www.cthreepo.com">Resources for Science Fiction</a> (Writing Science Fiction)
<a target="_blank"  href="http://www.jt30.com">The JT30 Page</a> (Amplified Blues Harmonica)
<a target="_blank"  href="http://www.harpamps.com">Harp Amps</a> (Vacuum Tube Amplifiers for Blues)
<a target="_blank"  href="http://www.blogseye.com">Blog&apos;s Eye</a> (PHP coding)
<a target="_blank"  href="http://www.cthreepo.com/bees">Bee Progress Beekeeping Blog</a> (My adventures as a new beekeeper)
