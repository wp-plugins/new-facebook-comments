<?php
/*
Plugin Name:  New Facebook Comments
Description:  Facebook comments makes it simple to add the Facebook comments system to your WordPress site. You can also insert the facebook comment box as a shortcode into any post, page or theme.
Version: 1.3
Author: Tayyab
Author URI:https://www.google.com/+tayyabismail0o1
Copyright: 2015, Tayyab

*/
if ( is_admin() && ( !defined( 'DOING_AJAX' ) || !DOING_AJAX ) )
	require 'class-admin.php';
else
	require 'class-frontend.php';

// Add settings link on plugin page
function fb_link($links) {
  $settings_link = '<a href="options-general.php?page=fbcomments">Settings</a>';
  array_unshift($links, $settings_link);
  return $links;
}

$plugin = plugin_basename(__FILE__);
add_filter("plugin_action_links_$plugin", 'fb_link' );
?>