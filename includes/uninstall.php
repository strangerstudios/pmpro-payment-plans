<?php
/**
 * Clean up on uninstall and if the PMPro settings are set to delete data on uninstall.
 * @since 0.1
 */

// exit if uninstall/delete not called
if ( ! defined( 'ABSPATH' ) ) {
    exit();
}

function pmpropp_uninstall() {

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
            $wpdb->query($sql); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Static query; the table name is built from $wpdb->prefix and a hardcoded list of PMPro meta tables.

        }       
    }
}