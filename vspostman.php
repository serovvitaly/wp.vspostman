<?php
/**
* Plugin Name: VsPostman
* Description: Почтовый рассыльщик
* Version: 1.0.0
* Author: Vitaly Serov
* Author URI: serovvitaly@gmail.com
*/

define('VSP_DIR', dirname(__FILE__));


define('TABLE_WP_USERS', $wpdb->prefix . 'users');
define('TABLE_FUNNELS', $wpdb->prefix . 'vspostman_funnels');
define('TABLE_MAILS', $wpdb->prefix . 'vspostman_mails');
define('TABLE_MAIL_LINKS', $wpdb->prefix . 'vspostman_mail_links');
define('TABLE_CLIENTS_FILTERS', $wpdb->prefix . 'vspostman_clients_filters');
define('TABLE_CLIENTS_CONTACTS', $wpdb->prefix . 'vspostman_clients_contacts');
define('TABLE_CONTACTS_FUNNELS', $wpdb->prefix . 'vspostman_contacts_funnels');
define('TABLE_CONTACTS_COMMENTS', $wpdb->prefix . 'vspostman_clients_comments');
define('TABLE_CLIENTS_CUSTOM_FIELDS', $wpdb->prefix . 'vspostman_clients_custom_fields');
define('TABLE_CLIENTS_CUSTOM_FIELDS_VALUES', $wpdb->prefix . 'vspostman_clients_custom_fields_values');


add_action('admin_menu', 'vspostman_admin_menu');


function _get($field, $default = NULL){
    return isset($_GET[$field]) ? $_GET[$field] : $default; 
}

function _post($field, $default = NULL){
    return isset($_POST[$field]) ? $_POST[$field] : $default; 
}

function _controller($controller){
    if (!class_exists($controller)) {
        include_once "controllers/Base_Controller.php";
        include_once "controllers/{$controller}.php";
    }
    
    return new $controller;
}


function vspostman_admin_menu() {
    add_menu_page('Почтовик', 'Почтовик', '', 'vspostman_admin', '', '/wp-content/plugins/vspostman/img/mail-air.png');
    
    add_submenu_page('vspostman_admin', 'Воронки', 'Воронки', 'manage_options', 'vspostman-mails', 'vspostman_menu_mails');
    add_submenu_page('vspostman_admin', 'Клиенты', 'Клиенты', 'manage_options', 'vspostman-clients', 'vspostman_menu_clients');
    add_submenu_page('vspostman_admin', 'Статистика', 'Статистика', 'manage_options', 'vspostman-stats', 'vspostman_menu_stats');
}


function vspostman_menu_mails() {
    echo _controller('Mails_Controller')->action($_REQUEST['act']);    
}
function vspostman_menu_clients() {
    echo _controller('Clients_Controller')->action($_REQUEST['act']);
}
function vspostman_menu_stats() {
    echo _controller('Stats_Controller')->action($_REQUEST['act']);
}