<?php
/**
* Plugin Name: VsPostman
* Description: Почтовый рассыльщик
* Version: 1.0.0
* Author: Vitaly Serov
* Author URI: serovvitaly@gmail.com
*/

define('VSP_DIR', dirname(__FILE__));

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
    
    add_submenu_page('vspostman_admin', 'Письма', 'Письма', 'manage_options', 'vspostman-mails', 'vspostman_menu_mails');
    add_submenu_page('vspostman_admin', 'Клиенты', 'Клиенты', 'manage_options', 'vspostman-clients', 'vspostman_menu_clients');
    add_submenu_page('vspostman_admin', 'Статистика', 'Статистика', 'manage_options', 'vspostman-stats', 'vspostman_menu_stats');
}


function vspostman_menu_mails() {
    
    global $wpdb;
    
    $act = isset($_REQUEST['act']) ? $_REQUEST['act'] : 'index';
    $uid = isset($_REQUEST['uid']) ? $_REQUEST['uid'] : 0;
    $mid = isset($_REQUEST['mid']) ? $_REQUEST['mid'] : 0;
    
    $_table_funnels    = $wpdb->prefix . 'vspostman_funnels';    
    $_table_mails      = $wpdb->prefix . 'vspostman_mails';    
    $_table_mail_links = $wpdb->prefix . 'vspostman_mail_links';    
    
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
            
            $mails = $wpdb->get_results("SELECT `id`,`level`,`title`,`left`,`bound_id`,`mail_type`,`created` FROM {$_table_mails} WHERE funnel_id={$uid} ORDER BY `created`");
            $item->mails = $mails;
            $ms = array();
            if (count($mails) > 0) {
                foreach ($mails AS $mail) {
                    $ms[$mail->level][] = $mail;
                }
            }
            $mails = $ms;
             
            $item->mails_json = json_encode($mails); 
            
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
            if ($uid > 0) {
                $funnel = $wpdb->get_row("SELECT * FROM {$_table_funnels} WHERE `id`={$uid}");
                unset($funnel->id);
                $funnel->name = 'Копия: ' . $funnel->name;
                $wpdb->insert($_table_funnels, (array) $funnel);
                $funnel_id = $wpdb->insert_id;
                
                $mails  = $wpdb->get_results("SELECT * FROM {$_table_mails} WHERE `funnel_id`={$uid}");
                if (count($mails) > 0) {
                    $mids = array();
                    $mmix = array();
                    foreach ($mails AS $mail) {
                        $_mid = $mail->id;
                        unset($mail->id);
                        $mids[] = $_mid;
                        $mail->funnel_id = $funnel_id;
                        $wpdb->insert($_table_mails, (array) $mail);
                        $mmix[$_mid] = $wpdb->insert_id;
                    }
                    
                    $mids = implode(',', $mids);
                    $links  = $wpdb->get_results("SELECT * FROM {$_table_mail_links} WHERE `mail_id` IN ({$mids})");
                    if (count($links) > 0) {
                        foreach ($links AS $link) {
                            unset($link->id);
                            $link->mail_id = $mmix[$link->mail_id];
                            $wpdb->insert($_table_mail_links, (array) $link);
                        }
                    }
                }
                
                $redirect_to = '/wp-admin/admin.php?page=vspostman-mails&act=edit&uid=' . $funnel_id;
                include  'templates/redirect.php';
                
            } else {
                echo '<h4>Не удалось дублировать воронку.</h4>';
            }
            
            break;
        case 'stat':
            if ($uid < 1) {
                //
            }
            //
            break;
        case 'delete':
            if ($uid > 0) {
                $mails = $wpdb->get_results("SELECT `id` FROM {$_table_mails} WHERE `funnel_id`={$uid}");
                if (count($mails) > 0) {
                    $mids = array();
                    foreach ($mails AS $mail) {
                        $mids[] = $mail->id;
                    }
                    $mids = implode(',', $mids);
                    $wpdb->query("DELETE FROM {$_table_mail_links} WHERE `mail_id` IN ({$mids})");
                    $wpdb->delete($_table_mails, array('funnel_id' => $uid));
                }
                
                $wpdb->delete($_table_funnels, array('id' => $uid));
                $redirect_to = '/wp-admin/admin.php?page=vspostman-mails';
                include  'templates/redirect.php';
            } else {
                echo '<h4>Не удалось удалить воронку.</h4>';
            }
            
            break;
            
        case 'mail-add':
            $item = new stdClass();
            $item->id = 0;
            $item->page_title = 'Новое письмо';
            $item->funnel_id = $uid;
            $item->content = file_get_contents(dirname(__FILE__) . '/templates/mails/empty-mail.tpl');
            
            $funnels_list = $wpdb->get_results("SELECT `id`,`name` FROM {$_table_funnels}");
            
            include  'templates/mails/mail-form.php';
            break;
            
        case 'mail-edit':
            $mid = _get('mid');
            $item = $wpdb->get_row("SELECT * FROM {$_table_mails} WHERE `id`={$mid}");
            $item->page_title = 'Редактирование';
            
            $funnels_list = $wpdb->get_results("SELECT `id`,`name` FROM {$_table_funnels}");
            
            include  'templates/mails/mail-form.php';
            break;
            
        case 'mail-stat':
            
            break;
            
        case 'mail-duplicate':
            $mid = _get('mid');
            if ($mid > 0) {
                $mail = $wpdb->get_row("SELECT * FROM {$_table_mails} WHERE `id`={$mid}");
                unset($mail->id);
                $mail->title = 'Копия: ' . $mail->title;
                $wpdb->insert($_table_mails, (array) $mail);
                $mail_id = $wpdb->insert_id;
                
                $links  = $wpdb->get_results("SELECT * FROM {$_table_mail_links} WHERE `mail_id`={$mail_id}");
                if (count($links) > 0) {
                    foreach ($links AS $link) {
                        unset($link->id);
                        $link->mail_id = $mail_id;
                        $wpdb->insert($_table_mail_links, (array) $link);
                    }
                }
                
                $redirect_to = $_SERVER['HTTP_REFERER'];
                include  'templates/redirect.php';
                
            } else {
                echo '<h4>Не удалось дублировать письмо.</h4>';
            }
            break;
            
        case 'mail-delete':
            $mid = _get('mid');
            if ($mid > 0) {
                $wpdb->delete($_table_mail_links, array('mail_id' => $mid));
                $wpdb->delete($_table_mails, array('id' => $mid));
                $redirect_to = $_SERVER['HTTP_REFERER'];
                include  'templates/redirect.php';
            } else {
                echo '<h4>Не удалось удалить письмо.</h4>';
            }
            break;
            
        default:            
            $items = $wpdb->get_results("SELECT * FROM {$_table_funnels} ORDER BY `created` DESC");
            
            include  'templates/mails/index.php';
    }
    
}
function vspostman_menu_clients() {
    echo _controller('Clients_Controller')->action($_REQUEST['act']);
}
function vspostman_menu_stats() {
    echo _controller('Stats_Controller')->action($_REQUEST['act']);
}