<?php
/**
 * Plugin Name: App WP Smart Banner
 * Plugin URI: https://github.com/devomman/app-wp-smartbanner
 * Description: Customisable App Smart banner for iOS and Android.
 * Version: 1.0
 * Author: Muhammad Omman (@devomman)
 * Author URI: https://github.com/devomman
 * Text Domain: app-wp-smartbanner
 * Domain Path: /languages
 *
 * @package App_WP_Smartbanner
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Require the main class.
require_once 'class-app-wp-smartbanner.php';

/**
 * App_WP_Smartbanner
 *
 * The main function responsible for returning the one true acf Instance to functions everywhere.
 * Use this function like you would a global variable, except without needing to declare the global.
 *
 * Example: <?php $app_wp_smartbanner = app_wp_smartbanner(); ?>
 *
 * @date    08/12/2023
 * @since   1.0
 *
 * @return  App_WP_Smartbanner
 */
function app_wp_smartbanner() {
	global $app_wp_smartbanner;

	// Instantiate only once.
	if ( ! isset( $app_wp_smartbanner ) ) {
		$app_wp_smartbanner = new App_WP_Smartbanner();
		$app_wp_smartbanner->initialize();
	}
	return $app_wp_smartbanner;
}

// Instantiate.
app_wp_smartbanner();
