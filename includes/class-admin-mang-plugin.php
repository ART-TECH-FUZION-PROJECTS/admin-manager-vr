<?php
/**
 * Main Plugin Class for Admin Management
 */

if (!defined('ABSPATH')) {
    exit;
}

class Admin_Mang_Plugin {

    public function __construct() {
        add_action('admin_menu', array($this, 'admin_mang_register_menus'));
        add_action('admin_enqueue_scripts', array($this, 'admin_mang_enqueue_assets'));
        add_filter('plugin_action_links_' . plugin_basename(ADMIN_MANG_PATH . 'admin-management.php'), array($this, 'admin_mang_add_settings_link'));

        // Initialize AJAX handlers
        new Admin_Mang_AJAX();
    }

    /**
     * Add Settings link on plugins page
     */
    public function admin_mang_add_settings_link($links) {
        $settings_link = '<a href="admin.php?page=admin-management">Settings</a>';
        array_push($links, $settings_link);
        return $links;
    }

    /**
     * Register Menus
     */
    public function admin_mang_register_menus() {
        add_menu_page(
            'Admin Management',
            'Admin Management',
            'manage_options',
            'admin-management',
            array($this, 'admin_mang_page_management_screen'),
            'dashicons-admin-generic',
            30
        );

        add_submenu_page(
            'admin-management',
            'Page Management',
            'Page Management',
            'manage_options',
            'admin-management',
            array($this, 'admin_mang_page_management_screen')
        );

        add_submenu_page(
            'admin-management',
            'Database',
            'Database',
            'manage_options',
            'admin-mang-database',
            array($this, 'admin_mang_database_screen')
        );
    }

    /**
     * Enqueue Assets
     * Delegating all logic to the centralized loader.
     */
    public function admin_mang_enqueue_assets($hook) {
        admin_mang_load_assets($hook);
    }

    /**
     * Page Management Screen
     */
    public function admin_mang_page_management_screen() {
        admin_mang_render_view('admin-management');
    }

    /**
     * Database Management Screen
     */
    public function admin_mang_database_screen() {
        admin_mang_render_view('database');
    }
}
