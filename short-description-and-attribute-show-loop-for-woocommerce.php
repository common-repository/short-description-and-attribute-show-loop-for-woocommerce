<?php
/**
 * Plugin Name: Short Description and Attribute Show Loop For Woocommerce
 * Description: Show Description On shop and category page
 * Version:     1.0
 * Author:      Gravity Master
 * License:     GPLv2 or later
 * Text Domain: short-description-and-attribute-show-loop-for-woocommerce
 */

/* Prevent direct access to the file */
if ( ! defined( 'ABSPATH' ) ) {
    die();
}

/* Define constants for the plugin */
if ( ! defined( 'GMWPDL_PREFIX' ) ) {
    define( 'GMWPDL_PREFIX', 'gmwpdl' );
}
if ( ! defined( 'GMWPDL_PLUGIN_DIR' ) ) {
    define( 'GMWPDL_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
}
if ( ! defined( 'GMWPDL_PLUGIN_BASENAME' ) ) {
    define( 'GMWPDL_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
}
if ( ! defined( 'GMWPDL_PLUGIN_URL' ) ) {
    define( 'GMWPDL_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}

/**
 * Autoloader function to automatically include necessary classes
 *
 * @param string $class Name of the class to be loaded
 */
if ( ! function_exists( 'gmwpdl_class_auto_loader' ) ) {
    
    function gmwpdl_class_auto_loader( $class ) {
        $includes = GMWPDL_PLUGIN_DIR . 'includes/' . $class . '.php';
        
        /* Check if the file exists and the class does not already exist */
        if ( is_file( $includes ) && ! class_exists( $class ) ) {
            include_once( $includes );
            return;
        }
    }
}

/* Register the autoloader function */
spl_autoload_register( 'gmwpdl_class_auto_loader' );

/* Initialize necessary modules */
new GMWPDL_Cron();
new GMWPDL_Admin();
new GMWPDL_Frontend();
