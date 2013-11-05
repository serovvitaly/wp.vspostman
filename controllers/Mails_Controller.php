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
        $this->items = $this->db->get_results("SELECT wvf.*, COUNT(wvcf.contact_id) AS subscribers FROM ".TABLE_FUNNELS." wvf JOIN ".TABLE_CONTACTS_FUNNELS." wvcf ON wvf.id = wvcf.funnel_id WHERE wvcf.is_removal = 0 AND wvcf.in_blacklist = 0 GROUP BY wvf.id ORDER BY wvf.`created` DESC");
    }
    
    
    public function action_add()
    {
        $this->title = 'Новая воронка';
    }
    
    
    public function action_edit()
    {   
        $this->title = 'Редактирование воронки';
        
        $fid  = $this->_input('fid');
        
        if ($fid > 0) {
            $this->item = $this->db->get_row("SELECT * FROM " . TABLE_FUNNELS . " WHERE id = {$fid}");
        }
        
        
    }
    
    
    public function action_save()
    {
        $fid  = $this->_input('fid');
        
        $name = $this->_input('name');
        
        if ($fid > 0) {
            //
        } else {
            
            if (!empty($name)) {
                //
            }
            
            $redirect_to = '/wp-admin/admin.php?page=vspostman-mails&act=edit';
            include(VSP_DIR . '/templates/redirect.php');
        }
        
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
    
}