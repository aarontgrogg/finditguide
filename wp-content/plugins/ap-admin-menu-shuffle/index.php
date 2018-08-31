<?php
/**
 * Plugin Name: AdvantiPro - Admin Menu Shuffle
 * Plugin URI: https://www.advantipro.de
 * Description: Re-orders and re-names items within the admin menu
 * Version: 1.0
 * Author: Aaron T. Grogg, AdvantiPro GmbH
 * License: GPLv2
 *
 * Resources:
 * https://code.tutsplus.com/articles/customizing-the-wordpress-admin-custom-admin-menus--wp-33200
 * https://wordpress.stackexchange.com/questions/276230/how-can-i-control-the-position-in-the-admin-menu-of-items-added-by-plugins
 * https://www.easywebdesigntutorials.com/reorder-left-admin-menu-and-add-a-custom-user-role/
 * https://www.easywebdesigntutorials.com/adding-links-in-the-top-admin-toolbar-menu/
 * https://code.tutsplus.com/articles/customizing-your-wordpress-admin--wp-24941
 **/


/**
 * Remove menu items from top nav
 **/

if ( ! function_exists( 'ap_top_nav_remove_menu_items' ) ) :
    function ap_top_nav_remove_menu_items() {
        global $wp_admin_bar;
        // node name = <li id="_______", minus the "wp-admin-bar-"
        // "wp-admin-bar-updates" => "updates"
        $wp_admin_bar->remove_node( 'wp-logo' ); // WP logo, links
        $wp_admin_bar->remove_node( 'updates' ); // WP Updates
        $wp_admin_bar->remove_node( 'comments' ); // Comments bubble, count
        $wp_admin_bar->remove_node( 'delete-cache' ); // Cache clear
        //$wp_admin_bar->remove_node( 'wpseo-menu' ); // Yoast
        $wp_admin_bar->remove_node( 'maintenance_options' ); // Maintenance
        $wp_admin_bar->remove_node( 'tribe-events' ); // Events
        $wp_admin_bar->remove_node( 'updraft_admin_node' ); // Updraft
    }
endif;
add_action( 'wp_before_admin_bar_render', 'ap_top_nav_remove_menu_items', 999 );


/**
 * Remove menu items from left nav
 **/

if ( ! function_exists( 'ap_left_nav_remove_menu_items' ) ) :
    function ap_left_nav_remove_menu_items() {
        $user = wp_get_current_user();
        // remove for all users
        remove_menu_page( 'edit-comments.php' ); // Comments
        //remove_menu_page( 'wsal-auditlog' ); // Audit Log
        // only remove if not an admin
        if ( ! $user->has_cap( 'manage_options' ) ) {
            //remove_menu_page( 'edit-comments.php' );
            //remove_submenu_page( 'edit.php', 'edit-tags.php?taxonomy=post_tag' ); // Parent, Child
        }
    }
endif;
add_action( 'admin_init', 'ap_left_nav_remove_menu_items' );


/**
 * Reorder menu items in left nav
 **/

if ( ! function_exists( 'ap_left_nav_change_menu_order' ) ) :
    function ap_left_nav_change_menu_order( $menu_order ) {
        //echo get_bloginfo("language");
        /*
            Either:
                'edit.php?post_type=page'
            Or:
                everything after 'admin.php?page='
        */
        return array(
            'index.php', // Dashboard
            'upload.php', // Media
            'edit.php', // Posts
            'edit.php?post_type=page', // Pages
            'edit.php?post_type=tribe_events', // Events
            'edit.php?post_type=portfolio', // Portfolio
            'edit.php?post_type=product', // Products
            'menu-posts-product', // Products
            'product', // Products
                'separator1', // -- separator --
            'blog2social', // Blog2Social
            'wpcf7', // Contact
			'elementor', // Elementor
            'EWD-UFAQ-Options', // FAQs
            'edit.php?post_type=ig_campaign', // Icegram
            'edit.php?post_type=mailmunch_page', // Landing Pages (MailChimp)
            'mailchimp-mailmunch', // MailChimp
            'nm_mailchimp', // Mailchimp Campaign
            'revslider', // Slider Revolution
                'separator2', // -- separator --
            'themes.php', // Appearance
            'gadwp_settings', // Google Analytics (GADWP)
            'mailchimp-for-wp', // MailChimp for WP
            'maintenance', // Maintenance
            'maxmegamenu', // Mega Menu
            'plugins.php', // Plugins
            'tables-supsystic', // Pricing Table...
            'wpseo_dashboard', // SEO (Yoast)
            'options-general.php', // Settings
            'simple-share-buttons-adder', // Simple Share Buttons
            'smush', // Smush
            'tools.php', // Tools
            'users.php', // Users
            'edit.php?post_type=shop_order', // WooCommerce
            'vc-general', // WPBakery
            'sitepress-multilingual-cms/menu/languages.php', // WPML
            'WP-Optimize', // WP-Optimize
                'separator-last', // -- separator --
            'GOTMLS-settings', // Anti-Malware
            'wsal-auditlog', // Audit Log
            'icwp-wpsf', // Shield Security
            'sucuriscan', // Sucuri Security
        );
    }
endif;
add_filter( 'custom_menu_order', 'ap_left_nav_change_menu_order', 10, 1 );
add_filter( 'menu_order', 'ap_left_nav_change_menu_order', 10, 1 );


/**
 * Create new top-level menu items for left nav
 **/

/*if ( ! function_exists( 'ap_left_nav_add_menu_items' ) ) :
    function ap_left_nav_add_menu_items() {
        // add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );
        add_menu_page('Admin', 'Admin', 'manage_options', 'ap_custom_admin', 'ap_left_nav_new_admin_page', 'dashicons-admin-generic');
        // add_submenu_page($parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function);
        add_submenu_page('ap_custom_admin', 'Audit Log', 'Audit Log', 'manage_options', 'wsal-auditlog');
    }
endif;
add_action( 'admin_init', 'ap_left_nav_add_menu_items' );*/


/**
 * New admin page...
 **/

/*if ( ! function_exists( 'ap_left_nav_new_admin_page' ) ) :
    function ap_left_nav_new_admin_page() {
        ?>
        <div class="wrap">
            <h2>Admin Page</h2>
            <?php
                // your admin pages content would be added here!
            ?>
        </div>
        <?php
    }
endif;*/


/**
 * Move menu items to new parent in left nav
 **/

/*if ( ! function_exists( 'ap_left_nav_move_menu_items' ) ) :
    function ap_left_nav_move_menu_items() {
        // Tools
        //add_management_page('Audit Log', 'Audit Log', 'manage_options', 'wsal-auditlo');
        // Settings
        //add_options_page('page-title', 'submenu title', 'manage_options', 'upload.php');
    }
endif;
add_action( 'admin_init', 'ap_left_nav_move_menu_items', 999 );*/


?>