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
    
    
    public function action_search()
    {
        $success = false;
        $result  = NULL;
        
        $filter = $this->_get_filter();
        
        $convert_date = function($date){
            $date = explode('.', $date);
            $date = array_reverse($date);
            $date = implode('-', $date);
            return $date;
        };
        
        $prepare_group = function($group) use ($convert_date){
            
            $conditions = array();
            
            $funnels_condition = NULL;
            if (isset($group['funnels']) AND is_array($group['funnels']) AND count($group['funnels']) > 0) {
                $funnels = implode(',', $group['funnels']);
                $conditions[] = "`id` IN(SELECT `contact_id` FROM `".TABLE_CONTACTS_FUNNELS."` WHERE `funnel_id` IN({$funnels}))";
            }
            
            $dates_range = NULL;
            if (isset($group['dates_range']) AND $group['dates_range'] != 'all_time') {
                switch ($group['dates_range']) {
                    case 'today':
                        $date_start = date('Y-m-d 00:00:00');
                        $date_end   = date('Y-m-d 23:59:59');
                        break;
                    case 'yesterday':
                        $date_start = date('Y-m-d 00:00:00', time() - 3600*24);
                        $date_end   = date('Y-m-d 00:00:00', time());
                        break;
                    case 'this_week':
                        $date_start = date('Y-m-d 00:00:00');
                        $date_end   = date('Y-m-d 23:59:59');
                        break;
                    case 'last_week':
                        // TODO: сделать !!!
                        break;
                    case 'last_7_days':
                        $date_start = date('Y-m-d 00:00:00', time() - 3600*24*7);
                        $date_end   = date('Y-m-d 00:00:00', time());
                        break;
                    case 'last_30_days':
                        $date_start = date('Y-m-d 00:00:00', time() - 3600*24*30);
                        $date_end   = date('Y-m-d 00:00:00', time());
                        break;
                    case 'this_month':
                        $date_start = date('Y-m-1 00:00:00');
                        $date_end   = date('Y-m-d 23:59:59');
                        break;
                    case 'last_month':
                        $this_month = date('n');
                        $this_year  = date('Y');
                        $last_year  = date('Y');
                        if ($this_month > 1) {
                            $last_month = $this_month - 1;
                        } else {
                            $last_month = 12;
                            $last_year = $last_year - 1;
                        }
                        $date_start = date("{$last_year}-{$last_month}-1 00:00:00");
                        $date_end   = date("{$this_year}-{$this_month}-1 00:00:00");
                        break;
                    case 'last_2_months':
                        $this_month = date('n');
                        $this_year  = date('Y');
                        $last_year  = date('Y');
                        if ($this_month > 2) {
                            $last_month = $this_month - 2;
                        } else {
                            $last_month = 10 + $this_month;
                            $last_year = $last_year - 1;
                        }
                        $date_start = date("{$last_year}-{$last_month}-1 00:00:00");
                        $date_end   = date("{$this_year}-{$this_month}-1 00:00:00");
                        break;
                    case 'custom':
                        $date_start = (isset($group['date_start']) AND !empty($group['date_start'])) ? $convert_date($group['date_start']) : NULL;
                        $date_end   = (isset($group['date_end'])   AND !empty($group['date_end']))   ? $convert_date($group['date_end'])   : NULL;
                        break;
                }
                
                $dates_start_stop = array();
                if (!empty($date_start)) {
                    $dates_start_stop[] = "`created` >= '{$date_start}'";
                }
                if (!empty($date_end)) {
                    $dates_start_stop[] = "`created` <= '{$date_end}'";
                }
                
                if (count($dates_start_stop) > 0) {
                    $conditions[] = '(' . implode(' AND ', $dates_start_stop) . ')';
                }
            }
            
            
            $match = ' AND ';
            if (isset($group['match']) AND $group['match'] == 'or') {
                $match = ' OR ';
            }
            $fields = array();
            if (isset($group['fields']) AND is_array($group['fields']) AND count($group['fields']) > 0) {
                foreach ($group['fields'] AS $field) {
                    $value = trim($field['value']);
                    if (!empty($value)) {
                        switch ($field['exp']) {
                            case 'eq':
                                $expression = "= '{$value}'";
                                break;
                            case 'not_eq':
                                $expression = "!= '{$value}'";
                                break;
                            case 'co':
                                $expression = "LIKE '%{$value}%'";
                                break;
                            case 'not_co':
                                $expression = "NOT LIKE '%{$value}%'";
                                break;
                            case 'start':
                                $expression = "LIKE '{$value}%'";
                                break;
                            case 'end':
                                $expression = "LIKE '%{$value}'";
                                break;
                            case 'not_start':
                                $expression = "NOT LIKE '{$value}%'";
                                break;
                            case 'not_end':
                                $expression = "NOT LIKE '%{$value}'";
                                break;
                            default:
                                $expression = NULL;
                        }
                        
                        if ($expression !== NULL) {
                            $fields[] = "`{$field['name']}` {$expression}";
                        }
                    }
                }
            
                if (count($fields) > 0) {
                    $conditions[] = '(' . implode($match, $fields) . ')';
                }
            }          
            
            return implode(' AND ', $conditions);
        };
        
        $re = $this->db->query('SELECT * as count FROM ' . TABLE_CLIENTS_CONTACTS . " WHERE `deleted` = 0");
        
        //print_r($filter);
        $where_sql = '';
        if (count($filter) > 0) {
            foreach ($filter AS $group) {
                $condition = ( isset($group['condition']) AND strtolower($group['condition']) == 'or' ) ? ' OR ' : ' AND '; 
                $where_sql .= $prepare_group($group) . $condition;
            }
        }
        
        $where_sql = rtrim($where_sql, ' AND ');
        $where_sql = rtrim($where_sql, ' OR ');
        
        if (!empty($where_sql)) {
            $where_sql = " AND ({$where_sql})";
        }
        
        $sql = 'SELECT COUNT(id) as count FROM ' . TABLE_CLIENTS_CONTACTS . " WHERE `deleted` = 0{$where_sql}";
        //echo $sql . "\n\n";
        $total = $this->db->get_var($sql);
        
        $limit = (int) $this->_input('limit', 20);
        if ($limit < 1) $limit = 1;
        
        $pages = ceil($total / $limit);
        
        $page = (int) $this->_input('page', 1);
        if ($page < 1) $page = 1;
        if ($page > $pages) $page = $pages;
        
        $start = $limit * ($page - 1);        
        
        $success = true;
        $result = $this->db->get_results('SELECT * FROM ' . TABLE_CLIENTS_CONTACTS . " WHERE `deleted` = 0{$where_sql} LIMIT {$start},{$limit}");
        
        echo json_encode(array(
            'success' => $success,
            'result'  => $result,
            'total'   => $total,
            'limit'   => $limit,
            'page'    => $page,
            'pages'   => $pages,
        ));
        
        return false;
    }
    
    
    public function action_clientcard()
    {
        $contact_id = $this->_input('cid');
        
        if ($contact_id > 0) {
            $contact = $this->db->get_row("SELECT * FROM " . TABLE_CLIENTS_CONTACTS . " WHERE `id` = {$contact_id}");
            
            if ($contact AND count($contact) > 0) {
                foreach ($contact AS $field_name => $field_val) {
                    $this->$field_name = $field_val;
                }
            }
            
            $this->comments = $this->db->get_results("SELECT *, 'Администратор' as `user_name` FROM " . TABLE_CONTACTS_COMMENTS . " WHERE `contact_id` = {$contact_id} ORDER BY created DESC LIMIT 2");
            
            $this->funnels = $this->db->get_results("SELECT cont.updated_at,cont.status,funn.name FROM " . TABLE_CONTACTS_FUNNELS . " as cont JOIN " . TABLE_FUNNELS . " as funn ON cont.funnel_id = funn.id WHERE cont.contact_id = {$contact_id}");
            
            //print_r($this->funnels);
        }
        
    }
    
    
    public function action_clientcard_mails()
    {
        $contact_id = $this->_input('cid');
        
        $this->id = $contact_id;
        
        if ($contact_id > 0) {
            //
        }
    }
    
    
    public function action_clientcard_comments()
    {
        $contact_id = $this->_input('cid');
        
        $this->id = $contact_id;
        
        $this->comments = array();
        
        if ($contact_id > 0) {
            $this->comments = $this->db->get_results("SELECT *, 'Администратор' as `user_name` FROM " . TABLE_CONTACTS_COMMENTS . " WHERE `contact_id` = {$contact_id} ORDER BY created DESC");
        }
    }
    
    
    public function action_savecontact()
    {
        $success = false;
        $result  = NULL;
        
        $allowable_fields = array('first_name','email','country','city','address','phone','skype','icq','facebook','vk','google','web','birthdate','information');
        
        $input = $_POST;
        
        $id = $this->_input('cid');
        
        if ($id > 0 AND count($input) > 0) {
            $data = array();
            foreach ($input AS $key => $value) {
                if (in_array($key, $allowable_fields)) {
                    $data[$key] = $value;
                }
            }
            
            $cost_fields = $this->_input('cost_fields');
            if ($cost_fields AND count($cost_fields) > 0) {
                $data['cost_fields'] = json_encode($cost_fields);
            }
            
            if (count($data) > 0) {
                $this->db->update(TABLE_CLIENTS_CONTACTS, $data, array('id' => $id));
                
                $success = true;
            } else {
                $result = 'Не удалось сохранить данные.';
            }
        } else {
            $result = 'Не удалось сохранить данные.';
        }
        
        echo json_encode(array(
            'success' => $success,
            'result'  => $result
        ));
        
        return false;
    }
    
    
    public function action_loadfilter()
    {
        $filter_id = $this->_input('filter_id');
        
        $success = false;
        $result  = NULL;
        
        if ($filter_id > 0) {
            
            $filter = $this->db->get_row("SELECT * FROM ".TABLE_CLIENTS_FILTERS." WHERE `id` = {$filter_id}");
            
            if ($filter AND $filter->id > 0) {
                
                $data = json_decode($filter->data);
                
                $result = array(
                    'id'   => $filter->id,
                    'name' => $filter->name,
                    'data' => $data
                );
                
                $success = true;
            }
            
        }
        
        echo json_encode(array(
            'success' => $success,
            'result'  => $result
        ));
        
        return false;
    }
    
    
    public function action_index()
    {
        $this->current_filter = $this->_input('filter', 0);
        
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
    
    
    public function action_filteredit()
    {
        $id = $this->_input('uid');
        if ($id > 0) {
            //
        }
        $redirect_to = '/wp-admin/admin.php?page=vspostman-clients&filter=' . $id;
        include(VSP_DIR . '/templates/redirect.php');
        return false;
    }
    
    
    public function action_filterdelete()
    {
        $id = $this->_input('uid');
        if ($id > 0) {
            $this->db->delete(TABLE_CLIENTS_FILTERS, array('id' => $id));
        }
        $redirect_to = '/wp-admin/admin.php?page=vspostman-clients&act=filterlist';
        include(VSP_DIR . '/templates/redirect.php');
        return false;
    }
    
    
    protected function _get_filter()
    {
        $input = $_POST;
        
        $allowable_fields = array('contacts_type','funnels','dates_range','date_start','date_end','match','fields','condition');
        
        $mix = array();
        if (count($input) > 0) {
            foreach ($input AS $key => $value) {
                if (in_array($key, $allowable_fields) AND is_array($value) AND count($value) > 0) {
                    foreach ($value AS $unique_id => $val) {
                        if ($key == 'funnels') {
                            $val = array_keys($val);
                        }
                        $mix[$unique_id][$key] = $val;
                    }
                }
            }    
        }
        
        return $mix;
    }
    
    
    public function action_filtersave()
    {        
        $id   = $this->_input('id', 0);
        $name = $this->_input('filter_name');
        
        $data = array(
            'name' => $name,
            'data' => json_encode($this->_get_filter())
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
        
        $first_name  = isset($input['first_name'])  ? trim($input['first_name'])  : NULL;
        $email = isset($input['email']) ? trim($input['email']) : NULL;
        
        $funnel_id = isset($input['funnel_id']) ? trim($input['funnel_id']) : NULL;
        
        $this->db->insert(TABLE_CLIENTS_CONTACTS, array(
            'first_name'  => $first_name,
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
                    $result  = '<span style="color:green">Передано контактов - '.$total.', невалидных - '.$novalid.', есть в базе - '.$skipped.'. Импортировано - '.$added.'.</span>';
                } else {
                    $result  = '<span style="color:red">Передано контактов - '.$total.', невалидных - '.$novalid.', есть в базе - '.$skipped.'. Импортировано - '.$added.'.</span>';
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
                                $this->db->insert(TABLE_CLIENTS_CONTACTS, array('email' => $email, 'first_name' => $name));
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
                    $result  = '<span style="color:green">Передано контактов - '.$total.', невалидных - '.$novalid.', есть в базе - '.$skipped.'. Импортировано - '.$added.'.</span>';
                } else {
                    $result  = '<span style="color:red">Передано контактов - '.$total.', невалидных - '.$novalid.', есть в базе - '.$skipped.'. Импортировано - '.$added.'.</span>';
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
        $code = $this->_input('code');
        
        if ($code) {
            include(VSP_DIR . "/google-api-php-client/src/Google_Client.php");
            
            $client = new Google_Client();
        }
          
        $this->action = 'import';
    }
    
    
    public function action_googledrive_code()
    {
        $code = $this->_input('code');
        
        $redirect_to = '/wp-admin/admin.php?page=vspostman-clients&act=importservices&code='.$code;
        include(VSP_DIR . '/templates/redirect.php');
        return false;
    }
        
    
    public function action_google_drive_auth()
    {
        include(VSP_DIR . "/google-api-php-client/src/Google_Client.php");
        
        $client = new Google_Client();
        $client->setClientId('333945996610.apps.googleusercontent.com');
        $client->setClientSecret('6m2TEqufA-iJ2yAu4Ac3KZjc');
        $client->setRedirectUri('http://wordpress.appros.ru/wp-admin/admin.php?page=vspostman-clients&act=googledrive_code');
        $client->setScopes(array('https://www.googleapis.com/auth/drive.readonly'));
        $client->setUseObjects(true);
        
        $code = $this->_input('code');

        $client->authenticate();
        $tokens = $client->getAccessToken();
        
        echo 'TOKENS = ' . $tokens;
    }
    
    
    public function action_load_google_drive()
    {
        $fileId = $this->_input('fileId');
        $url = $this->_input('url');
        
        if (!empty($url) AND !empty($fileId)) {
            $target_fale = VSP_DIR . '/data/google_drive/' . $fileId . '.dat';
            echo "DATA TO: {$target_fale}\n";
            $doc = file_get_contents($url);
            var_dump($doc);
            file_put_contents($target_fale, $doc);
        }
        
        return false;
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
        $this->list = $this->db->get_results("SELECT * FROM " . TABLE_CLIENTS_CONTACTS . " WHERE `deleted` = 1 ORDER BY `deleted_at` DESC");
    }
    
    
    public function action_blacklist()
    {
        $this->funnels_list = $this->db->get_results("SELECT * FROM " . TABLE_FUNNELS);
        
        $this->list = $this->db->get_results("SELECT * FROM " . TABLE_CLIENTS_CONTACTS . " WHERE `in_blacklist` = 1 AND id IN(SELECT contact_id FROM ".TABLE_CONTACTS_FUNNELS." WHERE `in_blacklist` = 1)");
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