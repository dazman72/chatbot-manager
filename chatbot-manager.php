<?php
/*
Plugin Name: Chatbot Manager
Description: A plugin to manage chat groups, conversations, and messages for the chatbot simulator.
Version: 1.1
Author: Your Name
*/

// Hook for adding admin menus
add_action('admin_menu', 'chatbot_manager_menu');

function chatbot_manager_menu() {
    add_menu_page('Chatbot Manager', 'Chatbot Manager', 'manage_options', 'chatbot-manager', 'chatbot_manager_groups_page');
    add_submenu_page('chatbot-manager', 'Groups', 'Groups', 'manage_options', 'chatbot-manager', 'chatbot_manager_groups_page');
    add_submenu_page('chatbot-manager', 'Conversations', 'Conversations', 'manage_options', 'chatbot-manager-conversations', 'chatbot_manager_conversations_page');
    add_submenu_page('chatbot-manager', 'Messages', 'Messages', 'manage_options', 'chatbot-manager-messages', 'chatbot_manager_messages_page');
}

function chatbot_manager_groups_page() {
    include_once(plugin_dir_path(__FILE__) . 'pages/groups.php');
}

function chatbot_manager_conversations_page() {
    include_once(plugin_dir_path(__FILE__) . 'pages/conversations.php');
}

function chatbot_manager_messages_page() {
    include_once(plugin_dir_path(__FILE__) . 'pages/messages.php');
}
?>

?>
