<?php

// exit if uninstall/delete not called
if ( !defined( 'ABSPATH' ) && !defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit();
}

if ( get_option( 'pmpro_uninstall', 0 ) ) {

    global $wpdb;

    $tables = array(
        'pmpro_membership_ordermeta',
        'pmpro_membership_levelmeta',
    );

    foreach($tables as $table){

        $table_name = $wpdb->prefix . $table;
        
        // setup sql query
        $sql = "DELETE FROM `$table_name` WHERE `meta_key` = 'payment_plan'";
        
        // run the query
        $wpdb->query($sql);

    }

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );
   
}