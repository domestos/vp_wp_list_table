<?php
/**
 * Created by PhpStorm.
 * User: v.pelenskyi
 * Date: 29.09.2016
 * Time: 14:56
 *
 * http://wp-kama.ru/function/wpdb#get_results---vyborka-kombinirovannyh-rezultatov   -- $WPDB
 */
echo "<div class  = 'wrap'>";
echo "<h1>WP LIST TABLE - </h1>";
echo "</div >";


$myListTable = new My_List_Table();


$myListTable->prepare_items();
$myListTable->display();


if (!class_exists('WP_List_Table')) {
    require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}


/*
global $wpdb;
$table_name = $wpdb->prefix . 'vp_cf_tables';
$sql_get_columns = "SELECT name_column, title_column FROM " . $table_name . " WHERE id_form = 1";
$columns = $wpdb->get_results($sql_get_columns);


function print_array($array)
{
    echo "<pre>";
    print_r($array);
    echo "</pre>";
}

foreach ($columns as $key => $value) {
//    $column = get_array_from_columns($value);

    if (is_object($value)) {
        $v = get_object_vars($value);
    }

    $column = array($v['name_column'] => $v['title_column']);

    if (empty($temp)) {
        $temp = $column;
    } else {
        $columns = array_merge($temp, $column);
        $temp = $columns;
    }
    print_array($columns);
}
*/


class My_List_Table extends WP_List_Table
{
    var $data= null;
    var
        $columns = null;

    function vp_get_data()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'vp_cf_list_forms';
        $sql = "SELECT * FROM " . $table_name;
        $this->data = $wpdb->get_results($sql, 'ARRAY_A');
        return $this->data;
    }

    function print_array($array)
    {
        echo "<pre>";
        print_r($array);
        echo "</pre>";
    }

    function get_columns()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'vp_cf_tables';
        $sql_get_columns = "SELECT name_column, title_column FROM " . $table_name . " WHERE id_form = 1";
        $columns = $wpdb->get_results($sql_get_columns);
        foreach ($columns as $key => $value) {
            if (is_object($value)) {
                $v = get_object_vars($value);
            }
            $column = array($v['name_column'] => $v['title_column']);
            $columns = array_merge( array('cb'=> '<input type="checkbox" />'), $column);
            if (empty($temp)) {
                $temp = $columns;
            } else {

                $columns = array_merge($temp, $column);
                $temp = $columns;
            }
        }


        $this->columns = $columns;
        return $columns;
    }



    function prepare_items()
    {
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        $this->_column_headers = array($columns, $hidden, $sortable);
        $this->items = $this->vp_get_data();

        usort( $this->data, array( &$this, 'usort_reorder' ) );
        $this->items = $this->data;

        $this->process_bulk_action();

//        usort( $this->$columns, array( &$this, 'usort_reorder' ) );
//        $this->items = $this->$columns;
    }


    function column_default($item, $column_name)
    {
        return $item[$column_name];
    }

    function get_sortable_columns() {
        $sortable_columns = array(
            'name_form'  => array('name_form',false),
            'shortcode_form' => array('shortcode_form',false)
        );
        return $sortable_columns;
    }


    function usort_reorder( $a, $b ) {
        // Если не отсортировано, по умолчанию title
        $orderby = ( ! empty( $_GET['orderby'] ) ) ? $_GET['orderby'] : 'name_form';
        // Если не отсортировано, по умолчанию asc
        $order = ( ! empty($_GET['order'] ) ) ? $_GET['order'] : 'asc';
        // Определяем порядок сортировки
        $result = strcmp( $a[$orderby], $b[$orderby] );
        // Отправляем конечный порядок сортировки usort
        return ( $order === 'asc' ) ? $result : -$result;
    }


    function column_name_form($item) {
        $actions = array(
            'edit'      => sprintf('<a href="?page=%s&action=%s&form=%s">Edit</a>',$_REQUEST['page'],'edit',$item['id']),
            'delete'    => sprintf('<a href="?page=%s&action=%s&form=%s">Delete</a>',$_REQUEST['page'],'delete',$item['id']),
        );

        return sprintf('%1$s %2$s', $item['name_form'], $this->row_actions($actions) );
    }

    function column_cb($item) {
        return sprintf(
            '<input type="checkbox" name="book[]" value="%s" />', $item['id']
        );
    }


    function get_bulk_actions() {
        $actions = array(
            'delete'    => 'Видалити'
        );
        return $actions;
    }


    /*
     *   global $wpdb;
        $orderby = (!empty($_REQUEST['orderby'])) ? $_REQUEST['orderby'] : 'name'; //If no sort, default to title
        $sql = "SELECT * FROM wp_nc_location ORDER BY " . $orderby;
        $data = $wpdb->get_results($sql);
     * */


}


?>