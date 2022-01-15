<?php
// if uninstall.php is not called by WordPress, die
if (!defined('WP_UNINSTALL_PLUGIN')) die;

// Removes for single site.
delete_option('td_db_version');

// Removes for multisite.
delete_site_option('td_db_version');

// Drops the custom reservation table.
global $wpdb;
$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}td_reservations");