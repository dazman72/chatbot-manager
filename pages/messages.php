<?php
function chatbot_manager_get_messages($group_id, $conversation_id) {
    $file_path = plugin_dir_path(__FILE__) . '../chatbot-conversations.json';
    if (file_exists($file_path)) {
        $json_data = json_decode(file_get_contents($file_path), true);
        if (isset($json_data[$group_id]['conversations'][$conversation_id]['messages'])) {
            $messages = array();
            foreach ($json_data[$group_id]['conversations'][$conversation_id]['messages'] as $message_id => $message) {
                $messages[] = array('name' => $message['text'], 'id' => $message_id);
            }
            return $messages;
        }
    }
    return array();
}

if (isset($_POST['save_message'])) {
    chatbot_manager_save_message();
}

function chatbot_manager_save_message() {
    $file_path = plugin_dir_path(__FILE__) . '../chatbot-conversations.json';
    $json_data = json_decode(file_get_contents($file_path), true);
    $group_id = $_POST['group_id_msg'];
    $conversation_id = $_POST['conversation_id_msg'];
    $message_id = $_POST['message_id'];
    $sender = $_POST['sender'];
    $text = $_POST['text'];

    if (!empty($group_id) && !empty($conversation_id) && !empty($message_id) && !empty($sender) && !empty($text)) {
        $json_data[$group_id]['conversations'][$conversation_id]['messages'][$message_id] = array(
            'message_id' => $message_id,
            'sender' => $sender,
            'text' => $text
        );
        file_put_contents($file_path, json_encode($json_data, JSON_PRETTY_PRINT));
        wp_redirect(admin_url('admin.php?page=chatbot-manager-messages&group_id=' . $group_id . '&conversation_id=' . $conversation_id));
        exit;
    }
}

function chatbot_manager_messages_page_content() {
    $group_id = isset($_GET['group_id']) ? $_GET['group_id'] : 0;
    $conversation_id = isset($_GET['conversation_id']) ? $_GET['conversation_id'] : 0;
    if (isset($_GET['action']) && $_GET['action'] == 'add') {
        ?>
        <div class="wrap">
            <h1>Add New Message</h1>
            <form method="post">
                <input type="hidden" name="group_id_msg" value="<?php echo $group_id; ?>">
                <input type="hidden" name="conversation_id_msg" value="<?php echo $conversation_id; ?>">
                <label for="message_id">Message ID:</label>
                <input type="text" id="message_id" name="message_id" required>
                <br>
                <label for="sender">Sender:</label>
                <input type="text" id="sender" name="sender" required>
                <br>
                <label for="text">Text:</label>
                <input type="text" id="text" name="text" required>
                <br>
                <input type="submit" name="save_message" value="Save Message">
            </form>
            <a href="?page=chatbot-manager-messages&group_id=<?php echo $group_id; ?>&conversation_id=<?php echo $conversation_id; ?>" class="button">Back to Messages</a>
        </div>
        <?php
    } else {
        $messages = chatbot_manager_get_messages($group_id, $conversation_id);
        $list_table = new Chatbot_Manager_List_Table($messages);
        $list_table->prepare_items();
        ?>
        <div class="wrap">
            <h1>Messages in Conversation <?php echo $conversation_id; ?></h1>
            <a href="?page=chatbot-manager-messages&group_id=<?php echo $group_id; ?>&conversation_id=<?php echo $conversation_id; ?>&action=add" class="button">Add New Message</a>
            <form method="post">
                <?php
                $list_table->display();
                ?>
            </form>
            <a href="?page=chatbot-manager-conversations&group_id=<?php echo $group_id; ?>" class="button">Back to Conversations</a>
        </div>
        <?php
    }
}

chatbot_manager_messages_page_content();
?>
