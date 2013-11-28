<?php

class Mails_Controller extends Base_Controller{
    
    public $title = 'Воронки продаж';
    
    public $icon = '/wp-content/plugins/vspostman/img/blue-document-text-image.png';
    
    public $icon_id = NULL;
    
    public $top_menu = array(
        array(
            'text' => 'Список воронок',
            'act'  => 'index',
            'href' => '/wp-admin/admin.php?page=vspostman-mails'
        ),
        array(
            'text' => 'Добавить воронку',
            'act'  => 'add',
            'href' => '/wp-admin/admin.php?page=vspostman-mails&act=add'
        ),
    ); 
    
    public function __construct()
    {
        parent::__construct();
        
        //
    }    

    public function action_index()
    {   
        //$sql = "SELECT wvf.*, COUNT(wvcf.contact_id) AS subscribers FROM ".TABLE_FUNNELS." wvf LEFT JOIN ".TABLE_CONTACTS_FUNNELS." wvcf ON wvf.id = wvcf.funnel_id WHERE wvcf.is_removal = 0 AND wvcf.in_blacklist = 0 GROUP BY wvf.id ORDER BY wvf.`created` DESC";
        $sql = "SELECT wvf.*, COUNT(wvcf.contact_id) AS subscribers FROM ".TABLE_FUNNELS." wvf LEFT JOIN ".TABLE_CONTACTS_FUNNELS." wvcf ON wvf.id = wvcf.funnel_id GROUP BY wvf.id ORDER BY wvf.`created` DESC";
        
        $this->items = $this->db->get_results($sql);
    }
    
    
    public function action_add()
    {
        $this->title = 'Новая воронка';
        
        $this->template = 'edit';
    }
    
    
    public function action_edit()
    {   
        $this->title = 'Редактирование воронки';
        
        $fid  = $this->_input('fid');
        
        if ($fid > 0) {
            $this->item = $this->db->get_row("SELECT * FROM " . TABLE_FUNNELS . " WHERE `id` = {$fid}");
            $this->item->mails = $this->db->get_results("SELECT * FROM " . TABLE_MAILS . " WHERE `funnel_id` = {$fid}");
            
            $this->item->mails_json = json_encode(array(
                array(
                    'type' => 'draw2d.shape.node.Start',
                    'id' => '354fa3b9-a834-0221-2009-abc2d6bd852a',
                    'x' => 200,
                    'y' => 50,
                    'width' => 200,
                    'height' => 50,
                    'radius' => 1,
                    //'angle' => 90
                ),
                array(
                    'type' => 'draw2d.shape.node.Between',
                    'id' => '354fa3b9-a834-0221-2009-abc2d6bd852a',
                    'x' => 200,
                    'y' => 150,
                    'width' => 200,
                    'height' => 50,
                    'radius' => 1,
                    //'angle' => 90
                ),
            ));
        }
        
        
    }
    
    
    public function action_delete()
    {         
        $fid  = $this->_input('fid');
        
        if ($fid > 0) {
            $this->db->delete(TABLE_FUNNELS, array('id' => $fid));
        }
        
        $redirect_to = '/wp-admin/admin.php?page=vspostman-mails';
        include(VSP_DIR . '/templates/redirect.php');
        return false;
    }
    
    
    public function action_check_funnel()
    {
        $name = trim($this->_input('funnel_name'));
        
        $out = array(
            'success' => false,
            'result'  => NULL
        );
        
        $if = $this->db->get_var("SELECT `id` FROM " . TABLE_FUNNELS . " WHERE `name` = '{$name}'");
        
        if (!$if) {
            $out['success'] = true;
        }
        
        echo json_encode($out);
        return false;
    }
    
    
    public function action_save()
    {
        $fid  = trim($this->_input('funnel_id'));
        $name = trim($this->_input('funnel_name'));
        
        $out = array(
            'success' => false,
            'result' => NULL
        );
        
        if (!empty($name)) {
            
            $data = array(
                'name' => $name
            );
            
            if ($fid > 0) {
                
                $data['updated'] = date('Y-m-d H:i:s');
                
                $this->db->update(TABLE_FUNNELS, $data, array('id' => $fid));
                
                $out['success'] = true;
            } else {
                
                $if = $this->db->get_var("SELECT `id` FROM " . TABLE_FUNNELS . " WHERE `name` = '{$name}'");
                
                if ($if > 0) {
                    $out['result'] = "Невозможно добавить воронку, так как воронка с названием “{$name}” уже существует. <input class=\"button button-small\" type=\"submit\" onclick=\"clearForm(); return false;\" value=\"OK\">";
                } else {
                    $this->db->insert(TABLE_FUNNELS, $data);
                    $out['result'] = $this->db->insert_id;
                    $out['success'] = true;
                }
            }
        } else {
            //
        }
        
        echo json_encode($out);
        return false;
    }
        

    public function action_funnel_set_active()
    {
        $fid    = $this->_input('fid');
        $active = $this->_input('active');
        
        if ($fid > 0) {
            $this->db->update(TABLE_FUNNELS, array('active' => (int) $active), array('id' => $fid));
        }
        
        return false;
    }
    
    
    public function action_mail_add()
    {
        $fid = $this->_input('fid');
        
        $this->template = 'mail.edit';
        
        $this->funnels_list = $this->db->get_results("SELECT `id`,`name` FROM " . TABLE_FUNNELS);
    }
    
    
    public function action_mail_edit()
    {
        $mid = $this->_input('mid');
        
        $this->template = 'mail.edit';
        
        $this->item = $this->db->get_row("SELECT * FROM " . TABLE_MAILS . " WHERE `id` = {$mid}");
        
        $this->funnels_list = $this->db->get_results("SELECT `id`,`name` FROM " . TABLE_FUNNELS);
    }
    
    
    public function action_mail_delete()
    {
        $mid = $this->_input('mid');
        
        $this->db->delete(TABLE_MAILS, array('id' => $mid));
        
        return false;
    }
    
    
    public function action_mail_save()
    {
        $out = array(
            'success' => false,
            'result'  => NULL
        );
        
        $mid = $this->_input('mid');
        $fid = $this->_input('fid');
        
        $allowed_fields = array('funnel_id','level','bound_id','title','subject','content',
                                'mail_type','mail_link_id','order_id','data_modified_type',
                                'data_modified_field','date_fielddatetime','time_mailing_type',
                                'time_mailing_delay_days','time_mailing_delay_hours',
                                'time_mailing_hour','time_mailing_weekdays');
        
        $data = array();
        if (count($_POST) > 0) {
            foreach ($_POST AS $field_key => $field_value) {
                if (in_array($field_key, $allowed_fields)) {
                    $data[$field_key] = $field_value; 
                }
            }
        }
        
        
        if (isset($data['funnel_id']) AND (empty($data['funnel_id']) OR $data['funnel_id'] < 1 OR $data['mail_type'] == 'manuallyadd')) {
            unset($data['funnel_id']);
        }
        
        $out['data'] = $data;
        
        if (count($data) > 0) {
            if ($mid > 0) {
                $this->db->update(TABLE_MAILS, $data, array('id' => $mid));
                $out['result'] = $this->db->insert_id;
            } else {
                if (!isset($data['funnel_id']) AND $fid > 0) {
                    $data['funnel_id'] = $fid;
                }
                $this->db->insert(TABLE_MAILS, $data);
            }
            
            $out['success'] = true;
        }
        
        echo json_encode($out);
        return false;
    }
    
}