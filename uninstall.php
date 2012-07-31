<?php
// Bail out if the file is not called from WordPress
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) )
	exit();

global $wpdb;

$query = "DELETE FROM $wpdb->usermeta WHERE meta_key = 'moneyflow_data';";

$wpdb->query( $query );