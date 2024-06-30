<?php
if (!class_exists('WP_List_Table')) {
    require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}

class Chatbot_Manager_List_Table extends WP_List_Table {
    private $data;

    public function __construct($data) {
        parent::__construct(array(
            'singular' => __('Item', 'sp'),
            'plural' => __('Items', 'sp'),
            'ajax' => false
        ));
        $this->data = $data;
    }

    public function column_default($item, $column_name) {
        switch ($column_name) {
            case 'name':
                return $item[$column_name];
            default:
                return print_r($item, true);
        }
    }

    public function get_columns() {
        $columns = array(
            'name' => __('Name', 'sp'),
        );
        return $columns;
    }

    public function prepare_items() {
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = array();
        $this->_column_headers = array($columns, $hidden, $sortable);
        $this->items = $this->data;
    }
}
?>
