<?php
/*
Plugin Name: Testimonials
Plugin URI: https://www.webiots.com
Description: Testimonials
Version: 1.0
Author: Webiots
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
include_once(ABSPATH . 'wp-includes/pluggable.php');
/**
 * You can disable RESTAPI2 only for old WP
 **/
define("TESTIMONAILS_RESTAPI2",true);
include('includes/functions.php');

if (is_admin()) { //if admin include the admin specific functions
    require_once(dirname( __FILE__ ).'/includes/options.php');
}


//Setup

add_action( 'wp_enqueue_scripts', 'testmonials_scripts_styles' );



/**
 * Register all shortcodes
 *
 * @return null
 */

function register_shortcodes_form() {
    add_shortcode( 'webiots-testimonials-form', 'shortcode_webiots_testimonials_form' );
}
add_action( 'init', 'register_shortcodes_form' );



function register_shortcodes() {
    add_shortcode( 'webiots-testimonials', 'shortcode_webiots_testimonials' );
}
add_action( 'init', 'register_shortcodes' );

/*
 *   Lists
 */

// TODO Update Css

