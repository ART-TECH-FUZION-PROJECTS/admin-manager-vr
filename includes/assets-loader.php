<?php
/**
 * Assets and Resource Loader for Admin Management Plugin
 * Centralizes all CSS, JS, and HTML template loading.
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Helper to enqueue a stylesheet with cache busting
 */
function admin_mang_enqueue_style($handle, $relative_path, $deps = array()) {
    wp_enqueue_style(
        $handle,
        ADMIN_MANG_URL . $relative_path,
        $deps,
        filemtime(ADMIN_MANG_PATH . $relative_path)
    );
}

/**
 * Helper to enqueue a script with cache busting
 */
function admin_mang_enqueue_script($handle, $relative_path, $deps = array(), $in_footer = true) {
    wp_enqueue_script(
        $handle,
        ADMIN_MANG_URL . $relative_path,
        $deps,
        filemtime(ADMIN_MANG_PATH . $relative_path),
        $in_footer
    );
}

/**
 * Dynamic template loader to completely keep paths out of other files
 */
function admin_mang_render_view($template_name) {
    $file_path = ADMIN_MANG_PATH . 'templates/' . $template_name . '.php';
    if (file_exists($file_path)) {
        include $file_path;
    }
}

/**
 * Main function to load assets based on hook
 * Handles common global assets and screen-specific ones.
 */
function admin_mang_load_assets($hook) {
    // Only load on our plugin screens
    $allowed_hooks = array(
        'toplevel_page_admin-management', 
        'admin-management_page_admin-mang-database'
    );

    if (!in_array($hook, $allowed_hooks)) {
        return;
    }

    // Automatically inject the toaster component into the footer for allowed screens
    add_action('admin_footer', 'admin_mang_include_toaster_template');

    // 1. Load Design Tokens (Main Global CSS)
    admin_mang_enqueue_style('admin-mang-design-tokens', 'components/global/global.css');

    // 2. Load Global Utilities (JS)
    admin_mang_enqueue_script('admin-mang-global-utils', 'components/global/global-utils.js', array('jquery'));

    // Localize for AJAX
    wp_localize_script('admin-mang-global-utils', 'admin_mang_obj', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('admin_mang_nonce')
    ));

    // 3. Load Toaster Assets
    admin_mang_enqueue_style('admin-mang-toaster-style', 'components/toaster/toaster.css', array('admin-mang-design-tokens'));
    admin_mang_enqueue_script('admin-mang-toaster-script', 'components/toaster/toaster.js', array('jquery', 'admin-mang-global-utils'));

    // 4. Load Screen Specific Assets
    if ($hook === 'toplevel_page_admin-management') {
        // Page Management Screen
        admin_mang_enqueue_style('admin-mang-page-management-style', 'assets/css/admin-management.css', array('admin-mang-design-tokens', 'admin-mang-toaster-style'));
        admin_mang_enqueue_script('admin-mang-page-management-script', 'assets/js/admin-management.js', array('jquery', 'admin-mang-global-utils', 'admin-mang-toaster-script'));
    } elseif ($hook === 'admin-management_page_admin-mang-database') {
        // Database Management Screen
        admin_mang_enqueue_style('admin-mang-database-style', 'assets/css/database.css', array('admin-mang-design-tokens', 'admin-mang-toaster-style'));
        admin_mang_enqueue_script('admin-mang-database-script', 'assets/js/database.js', array('jquery', 'admin-mang-global-utils', 'admin-mang-toaster-script'));
    }
}

/**
 * Include Toaster HTML component dynamically in the footer
 */
function admin_mang_include_toaster_template() {
    $file_path = ADMIN_MANG_PATH . 'components/toaster/toaster.php';
    if (file_exists($file_path)) {
        include $file_path;
    }
}
