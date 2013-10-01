<?php

if ( !isset($wp_did_header) ) {

    $wp_did_header = true;

    require_once( $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php' );

    wp();

} else die('FALSE');


function _post($field, $default = NULL){
    return isset($_POST[$field]) ? $_POST[$field] : $default; 
}

$_table_funnels = $wpdb->prefix . 'vspostman_funnels';    
$_table_mails   = $wpdb->prefix . 'vspostman_mails'; 


$action = isset($_REQUEST['act']) ? $_REQUEST['act'] : NULL;

$out = array(
    'success' => false,
    'result'  => NULL
);

switch ($action) {
    case 'mail-save':
        $mail_id   = _post('mid', 0);
        $mail_type = _post('mail_type', 0);
        $funnel_id = _post('funnel_id', 0);
        
        $data = array(
            'title'     => _post('title'),
            'mail_type' => _post('mail_type'),
            'funnel_id' => _post('funnel_id'),
        );
        
        if ($mail_id > 0) {
            $wpdb->update($_table_mails, $data, array('id' => $mail_id));
        } else {
            $wpdb->insert($_table_mails, $data);
        }
        
        $out['success'] = true;
        $out['result']  = 'mail-save-ok';
        $out['redirect_to']  = $funnel_id > 0 ? '/wp-admin/admin.php?page=vspostman-mails&act=edit&uid='.$funnel_id : '/wp-admin/admin.php?page=vspostman-mails';
        
        break;
    case '':
        //
        break;
}


echo json_encode($out);