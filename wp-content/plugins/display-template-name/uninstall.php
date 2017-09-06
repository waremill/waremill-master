<?php 
if( !defined( 'ABSPATH') && !defined('WP_UNINSTALL_PLUGIN') )
    exit();

$adminOptionsName = "DisplayTemplateNameAdminOptions";
delete_option($adminOptionsName);