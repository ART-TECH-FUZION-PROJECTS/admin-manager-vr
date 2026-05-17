<?php
/**
 * Plugin Name: Admin Management
 * Plugin URI: https://arttechfuzion.com
 * Description: A comprehensive admin management system for managing pages, settings, and database operations.
 * Version: 1.2.0
 * Author: Art-Tech Fuzion
 * Text Domain: admin-management
 * 
 * @package AdminManagement
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Define constants
 */
define('ADMIN_MANG_PATH', plugin_dir_path(__FILE__));
define('ADMIN_MANG_URL', plugin_dir_url(__FILE__));

/**
 * Include files
 */
require_once ADMIN_MANG_PATH . 'includes/helpers.php';
require_once ADMIN_MANG_PATH . 'includes/db-schema.php';
require_once ADMIN_MANG_PATH . 'includes/assets-loader.php'; // New Loader
require_once ADMIN_MANG_PATH . 'includes/class-admin-mang-ajax.php';
require_once ADMIN_MANG_PATH . 'includes/class-admin-mang-plugin.php';

/**
 * Initialize Plugin
 */
new Admin_Mang_Plugin();
