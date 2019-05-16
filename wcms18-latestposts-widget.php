<?php
/**
 * Plugin Name: WCMS18 Latest Posts Widget
 * Plugin URI:  https://thehiveresistance.com/wcms18-latestposts-widget
 * Description: This plugin adds a widget for displaying the latest posts.
 * Version:     0.1
 * Author:      Johan Nordström
 * Author URI:  https://thehiveresistance.com
 * License:     WTFPL
 * License URI: http://www.wtfpl.net/
 * Text Domain: wcms18-latestposts-widget
 * Domain Path: /languages
 */

require("class.LatestPostsWidget.php");

function wlpw_widgets_init() {
	register_widget('LatestPostsWidget');
}
add_action('widgets_init', 'wlpw_widgets_init');
