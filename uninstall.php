<?php
//if uninstall not called from WordPress exit
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) )
    exit();

// delete our metadata for all users
delete_metadata( 'user', 0, 'dnxasi_meta_signature', '', true );
// delete options for our plugin from the options table
delete_option( 'dnxasi_settings' );
