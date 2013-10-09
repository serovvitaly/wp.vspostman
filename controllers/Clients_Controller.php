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
        
        $funnel_id = isset($input['funnel_id']) ? trim($input['funnel_id']) : NULL;
        
        $this->db->insert(TABLE_CLIENTS_CONTACTS, array(
            'name'  => $name,
            'email' => $email,
        ));
        
        if ($funnel_id > 0) {
            
            $contact_id = $this->db->insert_id;
            
            $this->db->insert(TABLE_CONTACTS_FUNNELS, array(
                'contact_id' => $contact_id,
                'funnel_id'  => $funnel_id,
            ));
        }        
        
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
    
    public function action_importsave()
    {
        $success = false;
        $result  = '<span style="color:red">Не удалось импортировать контакты.</span>';
        
        $list = isset($_POST['contacts_list']) ? $_POST['contacts_list'] : NULL;
        
        if (!empty($list)) {
            $list = explode("\n", $list);
            if (is_array($list) AND count($list) > 0) {
                
                $total = count($list); // общее количество email
                $added = 0;            // добавлено в базу
                $novalid = 0;          // невалидных
                $skipped = 0;          // пропущено - уже существуют в базе
                
                
                foreach ($list AS $email) {
                    
                    $email = trim($email);
                    
                    $row = $this->db->get_var("SELECT `id` FROM " . TABLE_CLIENTS_CONTACTS . " WHERE `email` = '{$email}'");
            
                    $valid = preg_match('/([a-zA-Z0-9-_.]+)@([a-z0-9-]+)(\.)([a-z]{2,4})(\.?)([a-z]{0,4})+/', $email);
                    
                    if (!empty($email) AND $valid) {
                        if ($row === NULL) {
                            $this->db->insert(TABLE_CLIENTS_CONTACTS, array('email' => $email));
                            $added++;
                        } else {
                            $skipped++;
                        }
                    } else {
                        $novalid++;
                    }                    
                }
                
                if ($added > 0) {
                    $success = true;
                    $result  = '<span style="color:green">Передано контактов - '.$total.', неваледных - '.$novalid.', есть в базе - '.$skipped.'. Импортировано - '.$added.'.</span>';
                } else {
                    $result  = '<span style="color:red">Передано контактов - '.$total.', неваледных - '.$novalid.', есть в базе - '.$skipped.'. Импортировано - '.$added.'.</span>';
                }
            }
        }
        
        echo json_encode(array(
            'success' => $success,
            'result'  => $result
        ));
        
        return false;
    }
    
    
    public function action_importfile()
    {
        $this->action = 'import';
    }
    
    
    public function action_importfilesave()
    {
        $success = false;
        $result  = '<span style="color:red">Не удалось импортировать контакты.</span>';
        
        $file = isset($_FILES['contacts_file']) ? $_FILES['contacts_file'] : NULL;
        
        $total = 0; // общее количество email
        $added = 0;            // добавлено в базу
        $novalid = 0;          // невалидных
        $skipped = 0;          // пропущено - уже существуют в базе
        
        if ($file) {
            $file_lines = array();
            
            $f = fopen($file['tmp_name'], 'r+');
            if ($f) {
                while (($data = fgetcsv($f, 1000, ';')) !== FALSE) {
                    $total++;
                    $email = isset($data[0]) ? trim($data[0]) : NULL;
                    $name  = isset($data[1]) ? trim($data[1]) : '';
                    if (!empty($email)) {
                        $row = $this->db->get_var("SELECT `id` FROM " . TABLE_CLIENTS_CONTACTS . " WHERE `email` = '{$email}'");
                        $valid = preg_match('/([a-zA-Z0-9-_.]+)@([a-z0-9-]+)(\.)([a-z]{2,4})(\.?)([a-z]{0,4})+/', $email);
                        
                        if ($valid) {
                            if ($row === NULL) {
                                $this->db->insert(TABLE_CLIENTS_CONTACTS, array('email' => $email, 'name' => $name));
                                $added++;
                            } else {
                                $skipped++;
                            }
                        } else {
                            $novalid++;
                        } 
                    } else {
                        $novalid++;
                    }
                }
                
                if ($added > 0) {
                    $success = true;
                    $result  = '<span style="color:green">Передано контактов - '.$total.', неваледных - '.$novalid.', есть в базе - '.$skipped.'. Импортировано - '.$added.'.</span>';
                } else {
                    $result  = '<span style="color:red">Передано контактов - '.$total.', неваледных - '.$novalid.', есть в базе - '.$skipped.'. Импортировано - '.$added.'.</span>';
                }
                
            }
        }
        
        echo json_encode(array(
            'success' => $success,
            'result'  => $result
        ));
        
        return false;
    }
    
    
    public function action_importservices()
    {
        $this->action = 'import';
    }
    
    
    public function action_duplicate()
    {
        $this->funnels_list = $this->db->get_results("SELECT * FROM " . TABLE_FUNNELS);
    }
    
    
    public function action_duplicatesave()
    {
        $success = false;
        $result  = NULL;
        
        $operation = $this->_input('operation');
        $op_from   = $this->_input('op_from');
        $op_to     = $this->_input('op_to');
        
        if ($op_from > 0 AND $op_to > 0 AND $op_from != $op_to ) {
            switch ($operation) {
                case 'copy':
                    $sql = "INSERT INTO ".TABLE_CONTACTS_FUNNELS." (funnel_id,contact_id) SELECT {$op_to},contact_id FROM ".TABLE_CONTACTS_FUNNELS." WHERE `funnel_id` = {$op_from}";
                    $this->db->query($sql);
                    $success = true;
                    $result = '<span style="color: green">Контакты скопированы удачно.</span>';
                    break;
                case 'move':
                    $this->db->update(TABLE_CONTACTS_FUNNELS, array('funnel_id' => $op_to), array('funnel_id', $op_from));
                    $success = true;
                    $result = '<span style="color: green">Контакты перенесены удачно.</span>';
                    break;
                    
            }
        } else {
            $result = '<span style="color: red">Не удалось перенести контакты.</span>';
        }
        
        echo json_encode(array(
            'success' => $success,
            'result'  => $result
        ));
        
        return false;
    }
    
    
    public function action_removalgo()
    {
        $success = false;
        $result = '<span style="color: red">Не удалось отписать контакты.</span>';
        
        $removal_list = trim($this->_input('removal_list'));
        $funnel_id    = trim($this->_input('funnel_id'));
        $reason       = trim($this->_input('reason'));
        
        if (!empty($removal_list)) {
            
            $removal_list = explode("\n", $removal_list);
            if (count($removal_list) > 0) {
                $elist = array();
                foreach ($removal_list AS $contact) {
                    $elist[] = "'{$contact}'";
                }
                if (count($elist) > 0) {
                    $elist = implode(',', $elist);
                    
                    $removal_at = date('Y-m-d H:i:s');
                    
                    if ($funnel_id > 0) {
                        $this->db->query("INSERT INTO ".TABLE_CONTACTS_FUNNELS." (funnel_id,is_removal,removal_type,removal_at,contact_id) SELECT {$funnel_id},1,1,'{$removal_at}',id FROM ".TABLE_CLIENTS_CONTACTS." WHERE `email` IN({$elist})");
                    } else {
                        $this->db->query("UPDATE " . TABLE_CLIENTS_CONTACTS . " SET `is_removal` = 1, `removal_type` = 1, `removal_at` = '{$removal_at}', `removal_reason` = '{$reason}' WHERE `email` IN({$elist})");
                    }
                    
                    $result = '<span style="color: green">Контакты удачно отписаны.</span>';    
                } else {
                    $result = '<span style="color: red">Список контактов - пуст.</span>';
                }
                
            } else {
                $result = '<span style="color: red">Список контактов - пуст.</span>';
            }
            
        } else {
            $result = '<span style="color: red">Список контактов - пуст.</span>';
        }
        
        echo json_encode(array(
            'success' => $success,
            'result'  => $result
        ));
        
        return false;
    }
    
    
    protected function _get_removal_counts()
    {
        $total_direct = $this->db->get_results("SELECT `removal_type`, COUNT(`id`) as count FROM " . TABLE_CLIENTS_CONTACTS . " WHERE `is_removal` = 1 GROUP BY `removal_type` = 1");
    
        $totals = array();
        if ($total_direct AND count($total_direct) > 0) {
            foreach ($total_direct AS $_total) {
                $totals[$_total->removal_type] = $_total->count;
            }
        }
        
        $total_associated = $this->db->get_results("SELECT `removal_type`, COUNT(`id`) as count FROM " . TABLE_CONTACTS_FUNNELS . " WHERE `is_removal` = 1 GROUP BY `removal_type` = 1");
        
        if ($total_associated AND count($total_associated) > 0) {
            foreach ($total_associated AS $_total) {
                if (isset($totals[$_total->removal_type])) {
                    $totals[$_total->removal_type] += $_total->count;
                } else {
                    $totals[$_total->removal_type] = $_total->count;
                }
                
            }
        }
        
        $this->totals = $totals;
        
        return $totals;
    }
    
    
    public function action_removal()
    {
        $this->funnels_list = $this->db->get_results("SELECT * FROM " . TABLE_FUNNELS);
        
        $this->_get_removal_counts();        
        
        $this->list = $this->db->get_results("SELECT * FROM " . TABLE_CLIENTS_CONTACTS . " WHERE `is_removal` = 1 AND `removal_type` = 1");
    }
    
    
    public function action_removal2()
    {
        $this->funnels_list = $this->db->get_results("SELECT * FROM " . TABLE_FUNNELS);
        
        $this->action = 'removal';
        
        $this->_get_removal_counts();
        
        $this->list = $this->db->get_results("SELECT * FROM " . TABLE_CLIENTS_CONTACTS . " WHERE `is_removal` = 1 AND `removal_type` = 2");
    }
    
    
    public function action_removal3()
    {
        $this->funnels_list = $this->db->get_results("SELECT * FROM " . TABLE_FUNNELS);
        
        $this->action = 'removal';
        
        $this->_get_removal_counts();
        
        $this->list = $this->db->get_results("SELECT * FROM " . TABLE_CLIENTS_CONTACTS . " WHERE `is_removal` = 1 AND `removal_type` = 3");
    }
    
    
    public function action_undelivered()
    {
        //
    }
    
    
    public function action_blacklist()
    {
        $this->funnels_list = $this->db->get_results("SELECT * FROM " . TABLE_FUNNELS);
    }
    
    
    public function action_blacklistgo()
    {
        $success = false;
        $result = '<span style="color: red">Не удалось добавить контакты в черный список.</span>';
        
        $removal_list = trim($this->_input('removal_list'));
        $funnel_id    = trim($this->_input('funnel_id'));
        
        if (!empty($removal_list)) {
            
            $removal_list = explode("\n", $removal_list);
            if (count($removal_list) > 0) {
                $elist = array();
                foreach ($removal_list AS $contact) {
                    $elist[] = "'{$contact}'";
                }
                if (count($elist) > 0) {
                    $elist = implode(',', $elist);
                    
                    $blacklist_at = date('Y-m-d H:i:s');
                    
                    if ($funnel_id > 0) {
                        $this->db->query("INSERT INTO ".TABLE_CONTACTS_FUNNELS." (funnel_id,in_blacklist,blacklist_at,contact_id) SELECT {$funnel_id},1,'{$blacklist_at}',id FROM ".TABLE_CLIENTS_CONTACTS." WHERE `email` IN({$elist})");
                    } else {
                        $this->db->query("UPDATE " . TABLE_CLIENTS_CONTACTS . " SET `in_blacklist` = 1, `blacklist_at` = '{$blacklist_at}' WHERE `email` IN({$elist})");
                    }
                    
                    $result = '<span style="color: green">Контакты добавлены в черный список.</span>';    
                } else {
                    $result = '<span style="color: red">Список контактов - пуст.</span>';
                }
                
            } else {
                $result = '<span style="color: red">Список контактов - пуст.</span>';
            }
            
        } else {
            $result = '<span style="color: red">Список контактов - пуст.</span>';
        }
        
        echo json_encode(array(
            'success' => $success,
            'result'  => $result
        ));
        
        return false;
    }
    
}