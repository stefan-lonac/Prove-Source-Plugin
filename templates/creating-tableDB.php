<?php

// Creating table in DATABASE
function create_plugin_database_table() {
    global $wpdb;

    $tblname = 'prove_source';
    $wp_track_table = $wpdb->prefix . "$tblname";

    #Check to see if the table exists already, if not, then create it
    if($wpdb->get_var( "show tables like '$wp_track_table'" ) != $wp_track_table) {

        $sql = "CREATE TABLE `". $wp_track_table . "` ( ";
        $sql .= "  `id`  int(11)   NOT NULL auto_increment, ";
        $sql .= "  `name`  varchar(250)   NOT NULL, ";
        $sql .= "  `email`  varchar(250)   NOT NULL, ";
        $sql .= "  `number`  varchar(250)   NOT NULL, ";
        $sql .= "  `country`  varchar(250)   NOT NULL, ";
        $sql .= "  `date_time` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP, ";
        $sql .= "  PRIMARY KEY `order_id` (`id`) "; 
        $sql .= ") ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ; ";
        require_once( ABSPATH . '/wp-admin/includes/upgrade.php' );
        dbDelta($sql);
    }
}