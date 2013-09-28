<?php
/**
* Plugin Name: VsPostman
* Description: Почтовый рассыльщик
* Version: 1.0.0
* Author: Vitaly Serov
* Author URI: serovvitaly@gmail.com
*/

add_action('admin_menu', 'vspostman_admin_menu');



function vspostman_admin_menu() {
    add_menu_page('Почтовик', 'Почтовик', '', 'vspostman_admin', '', '/wp-content/plugins/vspostman/img/mail-air.png');
    
    add_submenu_page('vspostman_admin', 'Письма', 'Письма', 'manage_options', 'vspostman-mails', 'vspostman_menu_mails');
    add_submenu_page('vspostman_admin', 'Клиенты', 'Клиенты', 'manage_options', 'vspostman-clients', 'vspostman_menu_clients');
    add_submenu_page('vspostman_admin', 'Статистика', 'Статистика', 'manage_options', 'vspostman-stats', 'vspostman_menu_stats');
}


function vspostman_menu_mails() {
    
    global $wpdb;
    
    $act = isset($_REQUEST['act']) ? $_REQUEST['act'] : NULL;
    $uid = isset($_REQUEST['uid']) ? $_REQUEST['uid'] : 0;
    
    $_table_funnels = $wpdb->prefix . 'vspostman_funnels';    
    
    switch ($act) {
        case 'add':
            
            $item = new stdClass();
            $item->title = 'Новая воронка';
            
            include  'templates/mails/funnel-form.php';
            break;
        case 'edit':
            if ($uid < 1) {
                //
            }
            $item = $wpdb->get_row("SELECT * FROM {$_table_funnels} WHERE id={$uid}");
            
            $item->title = $item->name;
            
            include  'templates/mails/funnel-form.php';
            break;
        case 'save':
        
            $data = array(
                'name' => isset($_POST['name']) ? $_POST['name'] : '',
                'updated' => date('Y-m-d H:i:s'),
            );
        
            if ($uid > 0) {
                $wpdb->update($_table_funnels, $data, array('id' => $uid));
            } else {
                $wpdb->insert($_table_funnels, $data);
            }
            $redirect_to = '/wp-admin/admin.php?page=vspostman-mails';
            include  'templates/redirect.php';
            break;
        case 'duplicate':
            if ($uid < 1) {
                //
            }
            //
            break;
        case 'stat':
            if ($uid < 1) {
                //
            }
            //
            break;
        case 'delete':
            if ($uid < 1) {
                //
            }
            $wpdb->delete($_table_funnels, array('id' => $uid));
            $redirect_to = '/wp-admin/admin.php?page=vspostman-mails';
            include  'templates/redirect.php';
            break;
        default:
            $items = $wpdb->get_results("SELECT * FROM {$_table_funnels}");
            
            include  'templates/mails/index.php';
    }
    
}
function vspostman_menu_clients() {
    echo 'CLIENTS';
}
function vspostman_menu_stats() {
    echo 'STATS';
}