<?php

/*
 * Plugin name: OWT List Table
 * Description: This is simple plugin for WP_List_Table learning
 * Author: Online Web Tutor
 */

add_action("admin_menu", "wpl_owt_list_table_menu");

function wpl_owt_list_table_menu()
{
    add_menu_page("OWT List Table", "OWT List Table", "manage_options", "owt-list-table", "wpl_owt_list_table_fn");
}
function wpl_owt_list_table_fn()
{
    $action = isset($_GET['action']) ? trim($_GET['action']) : '';
    if ($action == 'owt-edit') {
        $post_id = isset($_GET['post_id']) ? intval($_GET['post_id']) : '';
        ob_start();
        include_once plugin_dir_path(__FILE__) . 'views/owt-edit-fn.php';
        $template = ob_get_contents();
        ob_end_clean();
        echo $template;
    } else {
        ob_start();
        include_once plugin_dir_path(__FILE__) . 'views/owt-table-list.php';
        $template = ob_get_contents();
        ob_end_clean();
        echo $template;
    }

}