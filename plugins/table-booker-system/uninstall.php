<?php
// If uninstall.php is not called by WordPress, die.
if (!defined('WP_UNINSTALL_PLUGIN')) die;

// Removes for single and multi site options.
delete_option('td_db_version');
delete_site_option('td_db_version');

// Drops the custom reservation table.
global $wpdb;
$wpdb->query("DROP TABLE {$wpdb->prefix}td_reservations;");