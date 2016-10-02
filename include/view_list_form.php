<?php
/**
 * Created by PhpStorm.
 * User: varenik
 * Date: 02.10.16
 * Time: 23:40
 */


if (!class_exists('WP_List_Table')) {
    require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}


function my_add_menu_items(){
        add_menu_page( 'VP FORM', 'VP FORM', 8, 'url_list_form', 'my_render_list_page' );
}
add_action( 'admin_menu', 'my_add_menu_items' );

function my_render_list_page(){

    $myListTable = new My_List_Table();

    echo '<div class="wrap"><h2>My List Table Test</h2>';
    $myListTable->prepare_items();
    $myListTable->display();
    echo '</div>';
}


class My_List_Table extends WP_List_Table
{

    function get_columns()
    {
        $columns = array(
            'cb'        => '<input type="checkbox" />',
            'name_form' => 'NAME FORM',
            'shortcode' => 'SHORTCODE',
        );
        return $columns;
    }

    function prepare_items()
    {
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        $this->_column_headers = array($columns, $hidden, $sortable);
        $this->items = $this->example_data;

        usort( $this->example_data, array( &$this, 'usort_reorder' ) );
        $this->items = $this->example_data;
    }

    function column_default($item, $column_name)
    {
        switch ($column_name) {
            case 'name_form':
            case 'shortcode':
                return $item[$column_name];
            default:
                return print_r($item, true); //Мы отображаем целый массив во избежание проблем
        }
    }

    function get_sortable_columns() {
        $sortable_columns = array(
            'name_form'  => array('name_form',false),
            'shortcode' => array('shortcode',false)
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
            'edit'      => sprintf('<a href="?page=%s&action=%s&book=%s">Edit</a>',$_REQUEST['page'],'edit',$item['ID']),
            'delete'    => sprintf('<a href="?page=%s&action=%s&book=%s">Delete</a>',$_REQUEST['page'],'delete',$item['ID']),
        );

        return sprintf('%1$s %2$s', $item['booktitle'], $this->row_actions($actions) );
    }


    function get_bulk_actions() {
        $actions = array(
            'delete'    => 'Delete'
        );
        return $actions;
    }

    function column_cb($item) {
        return sprintf(
            '<input type="checkbox" name="book[]" value="%s" />', $item['ID']
        );
    }

}


?>
