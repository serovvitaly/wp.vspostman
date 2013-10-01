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
    $mid = isset($_REQUEST['mid']) ? $_REQUEST['mid'] : 0;
    
    $_table_funnels = $wpdb->prefix . 'vspostman_funnels';    
    $_table_mails   = $wpdb->prefix . 'vspostman_mails';    
    
    switch ($act) {
        case 'add':
            
            $item = new stdClass();
            $item->id = 0;
            $item->title = 'Новая воронка';
            
            include  'templates/mails/funnel-form.php';
            break;
        case 'edit':
            if ($uid < 1) {
                //
            }
            $item = $wpdb->get_row("SELECT * FROM {$_table_funnels} WHERE id={$uid}");
            
            //$item->title = $item->name;
            $item->title = 'Редактирование';
            
            $mails = $wpdb->get_results("SELECT `id`,`level`,`title`,`left`,`bound_id` FROM {$_table_mails} WHERE funnel_id={$uid}");
            $ms = array();
            if (count($mails) > 0) {
                foreach ($mails AS $mail) {
                    $ms[$mail->level][] = $mail;
                }
            }
            $mails = $ms;
            
            $item->mails = json_encode($mails);
            
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
            
        case 'mail-add':
            $item = new stdClass();
            $item->id = 0;
            $item->title = 'Новое письмо';
            $item->funnel_id = $uid;
            $item->content = file_get_contents(dirname(__FILE__) . '/templates/mails/empty-mail.tpl');
            
            $funnels_list = $wpdb->get_results("SELECT `id`,`name` FROM {$_table_funnels}");
            
            include  'templates/mails/mail-form.php';
            break;
            
        case 'mail-save':
            
            $map = array(
                'funnel_id'                => array(1,3,4,5,6,7,8),
                'bound_id'                 => array(3,4,5),
                'mail_link_id'             => array(3),
                'order_id'                 => array(6),
                'data_modified_type'       => array(7),
                'data_modified_field'      => array(7),
                'date_field'               => array(8),
                'time_mailing_type'        => array(1,3,4,5,6,7,8),
                'time_mailing_delay_days'  => array(1,3,4,5,6,7,8),
                'time_mailing_delay_hours' => array(1,3,4,5,6,7,8),
                'time_mailing_hour'        => array(1,3,4,5,6,7,8),
                'time_mailing_weekday'     => array(1,3,4,5,6,7,8),
                'content'                  => array(1,2,3,4,5,6,7,8),
            );
            
            $mail_type = isset($_POST['mail_type']) ? $_POST['mail_type'] : 0;
            
            $data = array(
                'mail_type' => $mail_type,
                'title'    => isset($_POST['title']) ? $_POST['title'] : NULL,
                'time_mailing_type'    => isset($_POST['time_mailing_type']) ? $_POST['time_mailing_type'] : NULL,
                'time_mailing_weekday' => isset($_POST['time_mailing_weekday']) ? implode(',', $_POST['time_mailing_weekday']) : NULL
            );
                       
            if (count($_POST) > 0) {
                foreach ($_POST AS $data_key => $data_value) {
                    if (isset($map[$data_key]) AND in_array($mail_type, $map[$data_key])) {
                        $data[$data_key] = is_array($data_value) ? implode(',', $data_value) : $data_value;
                    }
                }
            }
        
            if ($mid > 0) {
                $wpdb->update($_table_mails, $data, array('id' => $mid));
            } else {
                $wpdb->insert($_table_mails, $data);
            }
            $redirect_to = $uid > 0 ? '/wp-admin/admin.php?page=vspostman-mails&act=edit&uid='.$uid : '/wp-admin/admin.php?page=vspostman-mails';
            include  'templates/redirect.php';
            break;
            
        default:            
            $items = $wpdb->get_results("SELECT * FROM {$_table_funnels} ORDER BY `created` DESC");
            
            include  'templates/mails/index.php';
    }
    
}
function vspostman_menu_clients() {
    echo 'CLIENTS';
}
function vspostman_menu_stats() {
    echo 'STATS';
}