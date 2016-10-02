<?php
/**
 * Created by PhpStorm.
 * User: varenik
 * Date: 03.10.16
 * Time: 0:01
 */


register_activation_hook(__FILE__, 'create_db');

function create_db(){
    global $vp_table_db_version;
    $vp_table_db_version = '1.1';

    global $wpdb;
    global $vp_table_db_version;

    $table_name = $wpdb->prefix . 'vp_list_form';

    $sql = "CREATE TABLE " . $table_name . " (
      id int(11) NOT NULL AUTO_INCREMENT,
      name form NOT NULL,
      shortcode VARCHAR(255) NOT NULL,
      PRIMARY KEY  (id)
    );";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);

    add_option('vp_table_db_version', $vp_table_db_version);
    $installed_ver = get_option('vp_table_db_version');
    if ($installed_ver != $vp_table_db_version) {
        $sql = "CREATE TABLE " . $table_name . " (
          id int(11) NOT NULL AUTO_INCREMENT,
          name form NOT NULL,
          shortcode VARCHAR(255) NOT NULL,
          PRIMARY KEY  (id)
        );";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);

        update_option('vp_table_db_version', $vp_table_db_version);




}
?>