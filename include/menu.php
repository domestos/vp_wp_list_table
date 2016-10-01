<?php
/**
 * Created by PhpStorm.
 * User: v.pelenskyi
 * Date: 29.09.2016
 * Time: 10:22
 */


//   http://wp-kama.ru/function/add_menu_page  - додаткова інфа


function vp_init_create_form_menu()
{
    //prepare main  include
    add_menu_page('Create Form', 'Create Form', 8, 'vp_url_create_form', 'vp_create_form_function_menu', '', 1);
    //prepare sub include and set dipendet to main include
    add_submenu_page('vp_url_create_form', 'Create Table', 'Create Table', 8, 'vp_url_create_table', 'vp_create_form_function_menu');

}

function vp_create_form_function_menu()
{
    switch ($_GET['page']) {
        case 'vp_url_create_form' :
            echo '<h1>MAIN MENU</h1>';
            include_once (__DIR__.'/vp_wp_list_table.php');
            break;
        case 'vp_url_create_table':
            echo '<h1>CREATE TABLE</h1>';
            vp_show_url_create_table();
            break;
    }
}


?>


<?php function vp_show_url_create_table()
{ ?>
    <div class="wrap">
        Create table
        <form action="" method="post">

<textarea name="vp_sql" cols="100%" rows="30">
   CREATE TABLE wp_vp_cf_list_forms(
             id int(20) NOT NULL AUTO_INCREMENT,
             name_form VARCHAR(255) ,
             shortcode_form VARCHAR(255) ,
             PRIMARY KEY  (id)
          );

   CREATE TABLE wp_vp_cf_tables(
            id int(20) NOT NULL AUTO_INCREMENT,
            id_form int(20) NOT NULL,
            name_table VARCHAR(255) ,
            name_column VARCHAR(255),
            title_column VARCHAR(255),
            PRIMARY KEY  (id)
         );
</textarea>
            <input type="submit" name="vp_create_table_submit">
        </form>

    </div>


<?php }

if (isset($_POST['vp_create_table_submit']) && !empty($_POST['vp_sql'])) {
    $sql = $_POST['vp_sql'];

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    global $wpdb;
    dbDelta($sql);
}

?>

