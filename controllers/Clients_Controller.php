<?php

class Clients_Controller extends Base_Controller{
    
    public $title = 'Клиенты';
    
    public $top_menu = array(
        array(
            'text' => 'Поиск клиентов',
            'act'  => 'index',
            'href' => '/wp-admin/admin.php?page=vspostman-clients'
        ),
        array(
            'text' => 'Добавить клиента',
            'act'  => 'add',
            'href' => '/wp-admin/admin.php?page=vspostman-clients&act=add'
        ),
        array(
            'text' => 'Импорт клиентов',
            'act'  => 'import',
            'href' => '/wp-admin/admin.php?page=vspostman-clients&act=import'
        ),
        array(
            'text' => 'Копировать в воронку',
            'act'  => 'duplicate',
            'href' => '/wp-admin/admin.php?page=vspostman-clients&act=duplicate'
        ),
        array(
            'text' => 'Отписанные клиенты',
            'act'  => 'removal',
            'href' => '/wp-admin/admin.php?page=vspostman-clients&act=removal'
        ),
        array(
            'text' => 'Несуществующие email',
            'act'  => 'undelivered',
            'href' => '/wp-admin/admin.php?page=vspostman-clients&act=undelivered'
        ),
        array(
            'text' => 'Черный список',
            'act'  => 'blacklist',
            'href' => '/wp-admin/admin.php?page=vspostman-clients&act=blacklist'
        ),
    );
    
    public function action_index()
    {
        $this->funnels_list = $this->db->get_results("SELECT * FROM " . TABLE_FUNNELS);
        
        $this->filters = $this->db->get_results("SELECT `id`,`name`,`created` FROM " . TABLE_CLIENTS_FILTERS);
        
    }
    
    public function action_add()
    {
        $this->funnels_list = $this->db->get_results("SELECT * FROM " . TABLE_FUNNELS);        
    }
    
    
    public function action_filterlist()
    {
        $this->action = 'index';
        
        $this->filters = $this->db->get_results("SELECT `id`,`name`,`created` FROM " . TABLE_CLIENTS_FILTERS);
    }
    
    
    public function action_filtersave()
    {
        $input = $_POST;
        
        $id   = isset($input['id'])   ? $input['id'] : 0;
        $name = isset($input['filter_name']) ? $input['filter_name'] : NULL;
        
        $allowable_fields = array('contacts_type','funnels','dates_range','date_start','date_end','match');
        
        $mix = array();
        foreach ($input AS $key => $value) {
            if (in_array($key, $allowable_fields) AND is_array($value) AND count($value) > 0) {
                foreach ($value AS $unique_id => $val) {
                    $mix[$unique_id][$key] = $val;
                }
            }
        }
        
        $data = array(
            'name' => $name,
            'data' => json_encode($mix)
        );
        
        if ($id > 0) {
            $this->db->update(TABLE_CLIENTS_FILTERS, $data, array('id' => $id));
        } else {
            $this->db->insert(TABLE_CLIENTS_FILTERS, $data);
        }
        
        echo json_encode(array(
            'success' => true
        ));
        
        return false;
    }
    
    
    public function action_contactadd()
    {
        $input = $_POST;
        
        $name  = isset($input['first_name'])  ? trim($input['first_name'])  : NULL;
        $email = isset($input['email']) ? trim($input['email']) : NULL;
        
        $this->db->insert(TABLE_CLIENTS_CONTACTS, array(
            'name'  => $name,
            'email' => $email,
        ));
        
        
        echo json_encode(array(
            'success' => true
        ));
        
        return false;
    }
    
    
    public function action_validemail()
    {
        $input = $_POST;
        
        $result = NULL;
        
        $email = isset($_POST['email']) ? trim($_POST['email']) : NULL;
        
        if (!empty($email)) {
            $row = $this->db->get_var("SELECT `id` FROM " . TABLE_CLIENTS_CONTACTS . " WHERE `email` = '{$email}'");
            
            if ($row === NULL) {
                $result = 'validemail-ok';
            }
        }        
        
        echo json_encode(array(
            'success' => true,
            'result'  => $result
        ));
        
        return false;
    }
    
    
    public function action_import()
    {
        //
    }
    
    
    public function action_importfile()
    {
        $this->action = 'import';
    }
    
    
    public function action_importservices()
    {
        $this->action = 'import';
    }
    
}