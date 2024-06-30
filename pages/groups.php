<?php
function chatbot_manager_get_groups() {
    $file_path = plugin_dir_path(__FILE__) . '../chatbot-conversations.json';
    if (file_exists($file_path)) {
        $json_data = json_decode(file_get_contents($file_path), true);
        $groups = array();
        foreach ($json_data as $group_id => $group) {
            $groups[] = array('name' => $group['group_name'], 'id' => $group_id);
        }
        return $groups;
    }
    return array();
}

if (isset($_POST['save_group'])) {
    chatbot_manager_save_group();
}

function chatbot_manager_save_group() {
    $file_path = plugin_dir_path(__FILE__) . '../chatbot-conversations.json';
    $json_data = json_decode(file_get_contents($file_path), true);
    $group_id = $_POST['group_id'];
    $group_name = $_POST['group_name'];

    if (!empty($group_id) && !empty($group_name)) {
        $json_data[$group_id]['group_name'] = $group_name;
        file_put_contents($file_path, json_encode($json_data, JSON_PRETTY_PRINT));
        wp_redirect(admin_url('admin.php?page=chatbot-manager'));
        exit;
    }
}

function chatbot_manager_groups_page_content() {
    if (isset($_GET['action']) && $_GET['action'] == 'add') {
        ?>
        <div class="wrap">
            <h1>Add New Group</h1>
            <form method="post">
                <label for="group_id">Group ID:</label>
                <input type="text" id="group_id" name="group_id" required>
                <br>
                <label for="group_name">Group Name:</label>
                <input type="text" id="group_name" name="group_name" required>
                <br>
                <input type="submit" name="save_group" value="Save Group">
            </form>
            <a href="?page=chatbot-manager" class="button">Back to Groups</a>
        </div>
        <?php
    } else {
        $groups = chatbot_manager_get_groups();
        $list_table = new Chatbot_Manager_List_Table($groups);
        $list_table->prepare_items();
        ?>
        <div class="wrap">
            <h1>Chat Groups</h1>
            <a href="?page=chatbot-manager&action=add" class="button">Add New Group</a>
            <form method="post">
                <?php
                $list_table->display();
                ?>
            </form>
        </div>
        <?php
    }
}

chatbot_manager_groups_page_content();
?>
