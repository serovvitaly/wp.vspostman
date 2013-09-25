<?php
/**
* Plugin Name: VsPostman
* Description: Почтовый рассыльщик
* Version: 1.0.0
* Author: Vitaly Serov
* Author URI: serovvitaly@gmail.com
*/

add_action('admin_menu', 'vspostman_admin_menu');

do_action('vspostman_go');

function vspostman_admin_menu() {
    add_menu_page('Почтовик', 'Почтовик', '', 'vspostman_admin', '', '/wp-content/plugins/vspostman/img/mail-air.png');
    
    add_submenu_page('vspostman_admin', 'Письма', 'Письма', 'manage_options', 'vspostman-mails', 'vspostman_menu_mails');
    add_submenu_page('vspostman_admin', 'Клиенты', 'Клиенты', 'manage_options', 'vspostman-clients', 'vspostman_menu_clients');
    add_submenu_page('vspostman_admin', 'Статистика', 'Статистика', 'manage_options', 'vspostman-stats', 'vspostman_menu_stats');
}


function vspostman_menu_mails() {
    
    $act = isset($_GET['act']) ? $_GET['act'] : NULL;
    
    switch ($act) {
        case 'add':
            $_ = array(
                'title' => 'Новая воронка'
            );
            
            include  'templates/mails/funnel-form.php';
            break;
        case 'edit':
            //
            break;
        default:
            $items = array(
                array(
                    'id' => 1,
                    'title' => 'Первая воронка',
                    'date' => '12/12/1223',
                    'readers' => 12
                )
            );
            
            include  'templates/mails/index.php';
    }
    
}
function vspostman_menu_clients() {
    echo 'CLIENTS';
}
function vspostman_menu_stats() {
    echo 'STATS';
}