<?php
/**
 * Database Schema functions for Admin Management
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Default Schema Configuration
 * Used for UI display and initial DB population.
 */
function admin_mang_get_default_schema() {
    return array(
        array('name' => 'My Profile',           'type' => 'page'),
        array('name' => 'Listing Archive',     'type' => 'page'),
        array('name' => 'Listing Single View', 'type' => 'page'),
        array('name' => 'logout',              'label' => 'Logout', 'type' => 'value'),
        array('name' => 'login',               'label' => 'Login', 'type' => 'value'),
        array('name' => 'auth_client_id',      'label' => 'Auth Client ID', 'type' => 'value'),
    );
}

/**
 * Create custom table for admin management
 */
function admin_mang_create_tables() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'admin_management';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        id INT(11) NOT NULL AUTO_INCREMENT,
        name VARCHAR(255) NOT NULL,
        page_id VARCHAR(255) DEFAULT NULL,
        value TEXT DEFAULT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (id),
        UNIQUE KEY unique_name (name)
    ) $charset_collate;";

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta($sql);

    // Initial population of default entries if missing
    $defaults = admin_mang_get_default_schema();
    $default_names = array();

    foreach ($defaults as $entry) {
        $default_names[] = $entry['name'];
        $exists = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM $table_name WHERE name = %s",
            $entry['name']
        ));

        if (!$exists) {
            $wpdb->insert(
                $table_name,
                array(
                    'name' => $entry['name'],
                    'page_id' => null,
                    'value' => null,
                    'created_at' => current_time('mysql'),
                    'updated_at' => current_time('mysql')
                ),
                array('%s', '%s', '%s', '%s', '%s')
            );
        }
    }

    // Prune entries that are not in the default schema to keep database clean
    $current_entries = $wpdb->get_col("SELECT name FROM $table_name");
    if (!empty($current_entries)) {
        foreach ($current_entries as $db_name) {
            if (!in_array($db_name, $default_names)) {
                $wpdb->delete($table_name, array('name' => $db_name));
            }
        }
    }
}

