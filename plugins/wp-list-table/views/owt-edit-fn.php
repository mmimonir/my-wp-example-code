<h3>Post Data</h3>
<?php
$post_detail = get_post($post_id);
global $wpdb;
// $wpdb->update(
//     $wpdb->posts,
//     array(
//         "post_title" => $_POST['txt_name'],
//         "post_content" => $_POST['txt_content'],
//         "post_slug" => $_POST['txt_slug'],
//     ),
//     array("id" => $post_id)
// );

?>
<form method="post" name="frm_post_data">
    <p>
        <label>Title</label>
        <input type="text" name="txt_name" value="<?php echo $post_detail->post_title; ?>">
    </p>
    <p>
        <label>Content</label>
        <input type="text" name="txt_content" value="<?php echo $post_detail->post_content; ?>">
    </p>
    <p>
        <label>Post Slug</label>
        <input type="text" name="txt_slug" value="<?php echo $post_detail->post_name; ?>">
    </p>
    <p>
        <button type="submit" name="btnsubmit">Submit Details</button>
    </p>
</form>