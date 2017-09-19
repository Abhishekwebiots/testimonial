<?php
/*
Plugin Name: Testimonials Plugin For Webiots
Plugin URI: https://www.webiots.com
Description: Testimonials
Version: 1.0
Author: Abhishek Jain
Author URI: https://www.webiots.com
License: GPLv2 or later
Text Domain: webiots-testimonials
*/


/**
 * Plugin Base File
 **/
define("TESTIMONAILS_PATH",dirname(__FILE__));
/**
 * Plugin Base Directory
 **/
define("TESTIMONAILS_DIR",basename(TESTIMONAILS_PATH));

/**
 * You can disable RESTAPI2 only for old WP
 **/
define("TESTIMONAILS_RESTAPI2",true);
include('includes/functions.php');




//Setup

add_action( 'wp_enqueue_scripts', 'testmonials_scripts_styles' );



/**
 * Register all shortcodes
 *
 * @return null
 */
function register_shortcodes() {
    add_shortcode( 'webiots-testimonials', 'shortcode_webiots_testimonials' );
}
add_action( 'init', 'register_shortcodes' );
