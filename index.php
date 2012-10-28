<?php
/*
Plugin Name: WG bit.ly shortener
Description: Uses bit.ly's shorten service to automatically create a shortened URL when publishing a post.
Version: 0.3.3
Author: Erik Hedberg (erik@webbgaraget.se)
Author URI: http://webbgaraget.se/
License: GPLv2 or later
*/

require_once( dirname( __FILE__ ) . '/settings/WGBitLySettingsView.php' );
require_once( dirname( __FILE__ ) . '/ajax/WGBitLyAjaxStats.php' );
require_once( dirname( __FILE__ ) . '/WGBitLyPost.php' );
require_once( dirname( __FILE__ ) . '/WGBitLyDashboard.php' );
