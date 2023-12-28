<?php

require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';

class OWTTableList extends WP_List_Table
{

    public function prepare_items()
    {

        $orderby = isset($_GET['orderby']) ? trim($_GET['orderby']) : "";
        $order = isset($_GET['order']) ? trim($_GET['order']) : "";

        $search_term = isset($_POST['s']) ? trim($_POST['s']) : "";
        $datas = $this->wp_list_table_data($orderby, $order, $search_term);
        $per_page = 3;
        $current_page = $this->get_pagenum();
        $total_items = count($datas);
        $this->set_pagination_args(array(
            "total_items" => $total_items,
            "per_page" => $per_page,
        ));

        $this->items = array_slice($datas, (($current_page - 1) * $per_page), $per_page);

        $columns = $this->get_columns();
        $hidden = $this->get_hidden_columns();
        $sortable = $this->get_sortable_columns();

        $this->_column_headers = array($columns, $hidden, $sortable);
    }

    public function wp_list_table_data($orderby = '', $order = '', $search_term = '')
    {
        global $wpdb;
        if (!empty($search_term)) {
            //wp_posts
            $all_posts = $wpdb->get_results(
                "SELECT * from " . $wpdb->posts . " WHERE post_type = 'post' AND post_status = 'publish' AND (post_title LIKE '%$search_term%' OR post_content LIKE '%$search_term%')"
            );
        } else {
            if ($orderby == "title" && $order == "desc") {
                // wp_posts
                $all_posts = $wpdb->get_results(
                    "SELECT * from " . $wpdb->posts . " WHERE post_type = 'post' AND post_status = 'publish' ORDER BY post_title DESC"
                );
            } else {
                $all_posts = $wpdb->get_results(
                    "SELECT * from " . $wpdb->posts . " WHERE post_type = 'post' AND post_status = 'publish' ORDER BY id desc"
                );
                // $all_posts = get_posts(array(
                //     "post_type" => "post",
                //     "post_status" => "publish",
                // ));
            }
        }

        $posts_array = array();
        if (count($all_posts) > 0) {
            foreach ($all_posts as $index => $post) {
                $posts_array[] = array(
                    "id" => $post->ID,
                    "title" => $post->post_title,
                    // "content" => $post->post_content,
                    "content" => substr($post->post_content, 0, 50),
                    "created_at" => date_i18n('F j, Y', $post->post_date_gmt),
                    "slug" => $post->post_name,
                );
            }
        }
        return $posts_array;
    }

    public function get_hidden_columns()
    {
        return array();

        //return array("id", "name");
    }

    public function get_sortable_columns()
    {

        return array(
            "title" => array("title", true),
            //"email" => array("email", false)
        );
    }
    public function get_bulk_actions()
    {
        return array(
            "delete" => "Delete",
            "edit" => "Edit",
        );
    }

    public function get_columns()
    {

        $columns = array(
            "cb" => "<input type='checkbox' />",
            "id" => "ID",
            "title" => "Title",
            "content" => "Content",
            "created_at" => "Created At",
            "slug" => "Post Slug",
            "action" => "Action",
        );

        return $columns;
    }
    public function column_cb($item)
    {
        return sprintf('<input type="checkbox" name="post[]" value="%s" />', $item['id']);
    }

    public function column_default($item, $column_name)
    {

        switch ($column_name) {
            case 'id':
            case 'title':
            case 'content':
            case 'created_at':
            case 'slug':
                return $item[$column_name];
            case 'action':
                return sprintf('<a href="?page=%s&action=%s&post_id=%s">Edit</a>', $_GET['page'], 'owt-edit', $item['id']) . " | " . sprintf('<a href="?page=%s&action=%s&post=%s">Delete</a>', $_GET['page'], 'owt-delete', $item['id']);

            default:
                return "no value";
        }
    }
    public function column_title($item)
    {
        $action = array(
            "edit" => sprintf('<a href="?page=%s&action=%s&post_id=%s">Edit</a>', $_GET['page'], 'owt-edit', $item['id']),
            "delete" => sprintf('<a href="?page=%s&action=%s&post_id=%s">Delete</a>', $_GET['page'], 'owt-delete', $item['id']),
        );
        return sprintf('%1$s %2$s', $item['title'], $this->row_actions($action));
    }
}

function owt_show_data_list_table()
{

    $owt_table = new OWTTableList();

    $owt_table->prepare_items();
    echo '<h3>This is List</h3>';
    echo "<form method='post' name='frm_search_post' action='" . $_SERVER['PHP_SELF'] . "?page=owt-list-table'>";
    $owt_table->search_box("Search Post(s)", "search_post_id");
    echo "</form>";
    $owt_table->display();
}

owt_show_data_list_table();