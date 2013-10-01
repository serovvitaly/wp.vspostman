<?php

if ( !isset($wp_did_header) ) {
    $wp_did_header = true;
    require_once( $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php' );
    wp();
} else die('FALSE');


function _post($field, $default = NULL){
    return isset($_POST[$field]) ? $_POST[$field] : $default; 
}

$_table_funnels    = $wpdb->prefix . 'vspostman_funnels';    
$_table_mails      = $wpdb->prefix . 'vspostman_mails'; 
$_table_mail_links = $wpdb->prefix . 'vspostman_mail_links'; 


$action = isset($_REQUEST['act']) ? $_REQUEST['act'] : NULL;

$out = array(
    'success' => false,
    'result'  => NULL
);

switch ($action) {
    case 'mail-save':
        $mail_id   = _post('mid', 0);
        $mail_type = _post('mail_type', 0);
        $funnel_id = _post('funnel_id', _post('uid'));
        
        $data = array(
            'title'     => _post('title'),
            'mail_type' => _post('mail_type'),
            'funnel_id' => $funnel_id,
            'content'   => _post('content'),
        );
        
        // если тип письма не "По ручному добавлению" - 2, то добавляем время отправления
        if (in_array($mail_type, array(1,3,4,5,6,7,8))) {
            
            $data['time_mailing_type']     = _post('time_mailing_type');
            $data['time_mailing_weekdays'] = _post('time_mailing_weekdays');
            switch ($data['time_mailing_type']) {
                case 2:
                    $data['time_mailing_delay_days']  = _post('time_mailing_delay_days');
                    $data['time_mailing_delay_hours'] = _post('time_mailing_delay_hours');
                    break;
                case 3:
                    $data['time_mailing_hour'] = _post('time_mailing_hour');
                    break;
            }
        }
        
        // если тип письма предусматирвает зависимость от другого письма
        if (in_array($mail_type, array(3,4,5))) {
            $data['bound_id'] = _post('bound_id');
        }
        
        // обрабатываем остальные поля для некоторых типов письма
        switch ($mail_type) {
            case 3:
                $data['mail_link_id'] = _post('mail_link_id');
                break;
            case 6:
                $data['order_id'] = _post('order_id');
                break;
            case 7:
                $data['data_modified_type'] = _post('data_modified_type');
                $data['data_modified_field'] = _post('data_modified_field');
                break;
            case 8:
                $data['date_field'] = _post('date_field');
                break;
        }
        
        
        if ($mail_id > 0) {
            $wpdb->update($_table_mails, $data, array('id' => $mail_id));
        } else {
            $wpdb->insert($_table_mails, $data);
        }
        
        $out['success'] = true;
        $out['result']  = 'mail-save-ok';
        $out['redirect_to']  = $funnel_id > 0 ? '/wp-admin/admin.php?page=vspostman-mails&act=edit&uid='.$funnel_id : '/wp-admin/admin.php?page=vspostman-mails';
        
        break;
    case 'mails-list':
        $funnel_id = _post('funnel_id');
        if ($funnel_id > 0) {
            $out['result'] = $wpdb->get_results("SELECT `id`,`title` FROM {$_table_mails} WHERE `funnel_id`={$funnel_id}");
            if (is_array($out['result']) AND count($out['result']) > 0) {
                $out['success'] = true;
            }
        }
        break;
    case 'mail-links-list':
        $mail_id = _post('mail_id');
        if ($mail_id > 0) {
            $out['result'] = $wpdb->get_results("SELECT `id`,`link` FROM {$_table_mail_links} WHERE `mail_id`={$mail_id}");
            if (is_array($out['result']) AND count($out['result']) > 0) {
                $out['success'] = true;
            }
        }
        break;
    case 'set-param':
        $source = _post('source');
        $param  = _post('param');
        $value  = _post('value');
        switch ($source) {
            case 'maile':
                $mid = _post('mid');
                if ($mid > 0) {
                    $wpdb->update($_table_mails, array($param => $value), array('id' => $mid));
                }
                break;
        }
        break;
    case '':
        //
        break;
}


echo json_encode($out);