<?php
function chatbot_manager_get_conversations($group_id) {
    $file_path = plugin_dir_path(__FILE__) . '../chatbot-conversations.json';
    if (file_exists($file_path)) {
        $json_data = json_decode(file_get_contents($file_path), true);
        if (isset($json_data[$group_id]['conversations'])) {
            $conversations = array();
            foreach ($json_data[$group_id]['conversations'] as $conversation_id => $conversation) {
                $conversations[] = array('name' => $conversation['conversation_name'], 'id' => $conversation_id);
            }
            return $conversations;
        }
    }
    return array();
}

if (isset($_POST['save_conversation'])) {
    chatbot_manager_save_conversation();
}

function chatbot_manager_save_conversation() {
    $file_path = plugin_dir_path(__FILE__) . '../chatbot-conversations.json';
    $json_data = json_decode(file_get_contents($file_path), true);
    $group_id = $_POST['group_id_conv'];
    $conversation_id = $_POST['conversation_id'];
    $conversation_name = $_POST['conversation_name'];

    if (!empty($group_id) && !empty($conversation_id) && !empty($conversation_name)) {
        $json_data[$group_id]['conversations'][$conversation_id] = array(
            'conversation_id' => $conversation_id,
            'conversation_name' => $conversation_name,
            'messages' => array()
        );
        file_put_contents($file_path, json_encode($json_data, JSON_PRETTY_PRINT));
        wp_redirect(admin_url('admin.php?page=chatbot-manager-conversations&group_id=' . $group_id));
        exit;
    }
}

function chatbot_manager_conversations_page_content() {
    $group_id = isset($_GET['group_id']) ? $_GET['group_id'] : 0;
    if (isset($_GET['action']) && $_GET['action'] == 'add') {
        ?>
        <div class="wrap">
            <h1>Add New Conversation</h1>
            <form method="post">
                <input type="hidden" name="group_id_conv" value="<?php echo $group_id; ?>">
                <label for="conversation_id">Conversation ID:</label>
                <input type="text" id="conversation_id" name="conversation_id" required>
                <br>
                <label for="conversation_name">Conversation Name:</label>
                <input type="text" id="conversation_name" name="conversation_name" required>
                <br>
                <input type="submit" name="save_conversation" value="Save Conversation">
            </form>
            <a href="?page=chatbot-manager-conversations&group_id=<?php echo $group_id; ?>" class="button">Back to Conversations</a>
        </div>
        <?php
    } else {
        $conversations = chatbot_manager_get_conversations($group_id);
        $list_table = new Chatbot_Manager_List_Table($conversations);
        $list_table->prepare_items();
        ?>
        <div class="wrap">
            <h1>Conversations in Group <?php echo $group_id; ?></h1>
            <a href="?page=chatbot-manager-conversations&group_id=<?php echo $group_id; ?>&action=add" class="button">Add New Conversation</a>
            <form method="post">
                <?php
                $list_table->display();
                ?>
            </form>
            <a href="?page=chatbot-manager" class="button">Back to Groups</a>
        </div>
        <?php
    }
}

chatbot_manager_conversations_page_content();
?>
