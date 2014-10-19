<?php


/**
* Load the correct database class file.
*
* This function is used to load the database class file either at runtime or by
* wp-admin/setup-config.php. We must globalize $wpdb to ensure that it is
* defined globally by the inline code in wp-db.php.

*/
if ( file_exists( dirname( __FILE__ ) .  '/config.php') ) {

    /** The config file resides in ABSPATH */
    require_once( dirname( __FILE__ ) . '/config.php' );

}

function require_db() {
    global $db;

    require_once(ABSPATH . 'assets/global/dataConnection/db.php');

    if ( isset( $db ) )
    return;

    $db = new db( );
}
require_db();

?>