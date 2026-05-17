<?php
/**
 * Helper and Reusable Functions for Admin Management
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Check if table exists
 */
function admin_mang_table_exists() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'admin_management';
    return $wpdb->get_var("SHOW TABLES LIKE '$table_name'") === $table_name;
}

/**
 * Get table row count
 */
function admin_mang_get_row_count() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'admin_management';
    if (!admin_mang_table_exists()) return 0;
    return $wpdb->get_var("SELECT COUNT(*) FROM $table_name");
}

/**
 * Get last update timestamp
 */
function admin_mang_get_last_updated() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'admin_management';
    if (!admin_mang_table_exists()) return 'N/A';
    $last_updated = $wpdb->get_var("SELECT MAX(updated_at) FROM $table_name");
    return $last_updated ? $last_updated : 'N/A';
}

/**
 * Get all entries
 */
function admin_mang_get_all_entries() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'admin_management';
    if (!admin_mang_table_exists()) return array();
    return $wpdb->get_results("SELECT * FROM $table_name ORDER BY id ASC", ARRAY_A);
}

