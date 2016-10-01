<?php

/*
Plugin Name: Vp Create Form
Plugin URI: http://URI_Of_Page_Describing_Plugin_and_Updates
Description: A brief description of the Plugin.
Version: 1.0
Author: v.pelenskyi
Author URI: http://URI_Of_The_Plugin_Author
License: A "Slug" license name e.g. GPL2


http://2web-master.ru/wp_list_table-%E2%80%93-poshagovoe-rukovodstvo.html
*/

include_once (__DIR__ . '/include/menu.php');
add_action('admin_menu', 'vp_init_create_form_menu');
?>
