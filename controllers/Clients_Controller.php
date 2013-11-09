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
        array(
            'text' => 'Настраиваемые поля',
            'act'  => 'custom_fields',
            'href' => '/wp-admin/admin.php?page=vspostman-clients&act=custom_fields'
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
        
        $where_sql = '';
        
        $funnel_id = $this->_input('funnel_id', 0);
        if ($funnel_id > 0) {
            $where_sql .= "`id` IN(SELECT `contact_id` FROM `".TABLE_CONTACTS_FUNNELS."` WHERE `funnel_id` = {$funnel_id}) AND ";
        }
        
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
        
        $this->contact_id = $contact_id;
        
        if ($contact_id > 0) {
            $contact = $this->db->get_row("SELECT * FROM " . TABLE_CLIENTS_CONTACTS . " WHERE `id` = {$contact_id}");
            
            if ($contact AND count($contact) > 0) {
                foreach ($contact AS $field_name => $field_val) {
                    $this->$field_name = $field_val;
                }
            }
            
            $this->comments = $this->db->get_results("SELECT comment.*, u.user_nicename AS `user_name` FROM " . TABLE_CONTACTS_COMMENTS . " AS comment JOIN " . TABLE_WP_USERS . " u ON comment.user_id = u.ID WHERE comment.`contact_id` = {$contact_id} ORDER BY comment.created DESC LIMIT 3");
            
            $this->comments_count = (int) $this->db->get_var("SELECT COUNT(id) AS `count` FROM " . TABLE_CONTACTS_COMMENTS . " WHERE `contact_id` = {$contact_id}");
            
            $this->funnels  = $this->db->get_results("SELECT cont.*,funn.name FROM " . TABLE_CONTACTS_FUNNELS . " as cont JOIN " . TABLE_FUNNELS . " as funn ON cont.funnel_id = funn.id WHERE cont.contact_id = {$contact_id}");
            
            $this->flist    = $this->db->get_results("SELECT * FROM " . TABLE_FUNNELS);
            
            $this->cost_fields = $this->db->get_results('SELECT * FROM ' . TABLE_CLIENTS_CUSTOM_FIELDS . ' AS f LEFT JOIN ' . TABLE_CLIENTS_CUSTOM_FIELDS_VALUES . ' AS v ON f.id = v.field_id AND v.contact_id = ' . $contact_id);
            
        }
        
    }
    
    
    public function action_client_remove()
    {
        $cid = $this->_input('cid');
        
        if ($cid > 0) {
            $this->db->delete(TABLE_CLIENTS_CONTACTS,  array('id' => $cid));
            $this->db->delete(TABLE_CONTACTS_FUNNELS,  array('contact_id' => $cid));
            $this->db->delete(TABLE_CONTACTS_COMMENTS, array('contact_id' => $cid));
        }
        
        $redirect_to = '/wp-admin/admin.php?page=vspostman-clients';
        include(VSP_DIR . '/templates/redirect.php');
        return false;
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
        
        $this->contact_id = $contact_id;
        
        $this->comments = array();
        
        if ($contact_id > 0) {        
            $this->comments = $this->db->get_results("SELECT comment.*, u.user_nicename as `user_name` FROM " . TABLE_CONTACTS_COMMENTS . " AS comment JOIN " . TABLE_WP_USERS . " u ON comment.user_id = u.ID WHERE comment.`contact_id` = {$contact_id} ORDER BY comment.created DESC");
        }
    }
    
    
    public function action_add_comment()
    {
        $comment    = $this->_input('comment', NULL);
        $contact_id = $this->_input('contact_id', 0);
        
        $out = array(
            'success' => false,
            'result'  => NULL
        );
        
        if (!empty($comment) AND $contact_id > 0) {
            $this->db->insert(TABLE_CONTACTS_COMMENTS, array(
                'user_id'    => wp_get_current_user()->ID,
                'contact_id' => $contact_id,
                'content'    => $comment,
            ));
            
            $insert_id = $this->db->insert_id;
            
            if ($insert_id > 0) {
                $out = array(
                    'success' => true,
                    'result'  => $this->db->get_row("SELECT * FROM " . TABLE_CONTACTS_COMMENTS . " WHERE `id` = {$insert_id}")
                );
            }
        }
        
        echo json_encode($out);
        
        return false;
    }
    
    public function action_remove_comment()
    {
        $contact_id = $this->_input('contact_id', 0);
        
        $out = array(
            'success' => true,
            'result'  => NULL
        );
        
        if ($contact_id > 0) {
            $this->db->delete(TABLE_CONTACTS_COMMENTS, array('id' => $contact_id));
        }
        
        echo json_encode($out);
        
        return false;
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
            
            $recs_values = $this->db->get_results("SELECT `contact_id`, `field_id` FROM " . TABLE_CLIENTS_CUSTOM_FIELDS_VALUES . " WHERE `contact_id` = {$id} AND `field_id` IN (".implode(',', array_keys($cost_fields)).")");
           
            array_walk_recursive($recs_values, function(&$item, $key){
                $item = (array) $item;
            });
            
            if ($cost_fields AND count($cost_fields) > 0) {
                foreach ($cost_fields AS $field_id => $field_value) {
                    
                    if (is_array($field_value)) {
                        $field_value = implode("\n", $field_value);
                    }
                    
                    if (in_array(array('contact_id' => $id, 'field_id' => $field_id), $recs_values)) {
                        $this->db->update(TABLE_CLIENTS_CUSTOM_FIELDS_VALUES, array(
                            'value' => $field_value
                        ), array(
                            'contact_id' => $id, 
                            'field_id'   => $field_id, 
                        ));
                    } else {
                        $this->db->insert(TABLE_CLIENTS_CUSTOM_FIELDS_VALUES, array(
                            'contact_id' => $id, 
                            'field_id'   => $field_id,
                            'value' => $field_value
                        ));
                    }
                }
                
                //$data['cost_fields'] = json_encode($cost_fields);
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
        $this->funnel_id = $this->_input('funnel_id', 0);
        
        $this->current_filter = $this->_input('filter', 0);
        
        $this->funnels_list = $this->db->get_results("SELECT * FROM " . TABLE_FUNNELS);
        
        $this->filters = $this->db->get_results("SELECT `id`,`name`,`created` FROM " . TABLE_CLIENTS_FILTERS);
        
        $this->custom_fields = $this->db->get_results("SELECT * FROM " . TABLE_CLIENTS_CUSTOM_FIELDS . " ORDER BY `sort`");
        
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
        
        $contact_id = $this->db->insert_id;
        
        if ($funnel_id > 0) {            
            $this->db->insert(TABLE_CONTACTS_FUNNELS, array(
                'contact_id' => $contact_id,
                'funnel_id'  => $funnel_id,
            ));
        }        
        
        echo json_encode(array(
            'success' => true,
            'result' => $contact_id
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
    
    
    /**
    * Импортирует список контактов в БД
    * 
    * @param mixed $list
    */
    protected function _import(array $list)
    {
        $_perse_contact = function($contact){
            
            $name  = '';
            $email = NULL;
            
            if (is_array($contact)) {
                $contact = trim($contact['email']);
                $name    = trim($contact['name']); 
            } else {
                $contact = trim($contact);
            }
            
            $valid1 = preg_match('/^([a-zA-Z0-9-_.]+)@([a-zA-Z0-9-]+)(\.)([a-z]{2,4})(\.?)([a-z]{0,4})+$/', $contact, $matches1);
            $valid2 = preg_match('/^(.+),[\s]{0,}(([a-zA-Z0-9-_.]+)@([a-zA-Z0-9-]+)(\.)([a-z]{2,4})(\.?)([a-z]{0,4})+)$/', $contact, $matches2);
            $valid3 = preg_match('/^(.+)\<(([a-zA-Z0-9-_.]+)@([a-zA-Z0-9-]+)(\.)([a-z]{2,4})(\.?)([a-z]{0,4})+)\>$/', $contact, $matches3);
            
            if ($valid1) {
                $email = $matches1[0];
            } elseif ($valid2) {
                $name  = $matches2[1];
                $email = $matches2[2];
            } elseif ($valid3) {
                $name  = $matches3[1];
                $email = $matches3[2];
            }
            
            return array(
                'name'  => $name,
                'email' => $email,
            );
        };
        
        $total   = count($list); // общее количество email
        $added   = 0;            // добавлено в базу
        $novalid = 0;            // невалидных
        $skipped = 0;            // пропущено - уже существуют в базе
        
        $inserted = array();
        
        if (count($list) > 0) {
            foreach ($list AS $contact) {
                $contact = $_perse_contact($contact);
                
                $name  = $contact['name'];
                $email = $contact['email'];
                
                if ($email) {
                    $evalid = preg_match('/([a-zA-Z0-9-_.]+)@([a-zA-Z0-9-]+)(\.)([a-z]{2,4})(\.?)([a-z]{0,4})+/', $email);
                    
                    if ($evalid) {
                        $erow = $this->db->get_var("SELECT `id` FROM " . TABLE_CLIENTS_CONTACTS . " WHERE `email` = '{$email}'");
                        if ($erow === NULL) {
                            $this->db->insert(TABLE_CLIENTS_CONTACTS, array('email' => $email, 'first_name' => $name));
                            $inserted[] = $this->db->insert_id;
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
        }
        
        return array(
            'total'    => $total,
            'added'    => $added,
            'novalid'  => $novalid,
            'skipped'  => $skipped,
            'inserted' => $inserted,
        );
    }
    
    
    public function action_import_list()
    {
        $this->action = 'import';
        
        $list = $this->_input('list');
        
        if (!empty($list)) {
            $this->list = $this->db->get_results("SELECT * FROM " . TABLE_CLIENTS_CONTACTS . " WHERE `id` IN ({$list})");
        }
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
                
                extract($this->_import($list));
                
                if ($added > 0) {
                    $success = true;
                    $added = '<a href="/wp-admin/admin.php?page=vspostman-clients&act=import_list&list=' . implode(',', $inserted) . '">' . $added . '</a>';
                    $result  = '<span style="color:green">Передано контактов - '.$total.', невалидных - '.$novalid.', есть в базе - '.$skipped.'. Импортировано - '.$added.'.</span>';
                } else {
                    $result  = '<span style="color:red">Передано контактов - '.$total.', невалидных - '.$novalid.', есть в базе - '.$skipped.'. Импортировано - 0.</span>';
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
        
        $total = 0;   // общее количество email
        $added = 0;   // добавлено в базу
        $novalid = 0; // невалидных
        $skipped = 0; // пропущено - уже существуют в базе
        
        if ($file) {
            $file_lines = array();
            
            $f = fopen($file['tmp_name'], 'r+');
            if ($f) {
                
                $list = array();
                
                while (($data = fgetcsv($f, 1000, ';')) !== FALSE) {
                    $total++;
                    
                    $name  = isset($data[0]) ? trim($data[0]) : '';
                    $email = isset($data[1]) ? trim($data[1]) : NULL;
                    
                    $list[] = array(
                        'name'  => $name,
                        'email' => $email,
                    );
                }
                
                extract($this->_import($list));
                
                if ($added > 0) {
                    $success = true;
                    $added = '<a href="/wp-admin/admin.php?page=vspostman-clients&act=import_list&list=' . implode(',', $inserted) . '">' . $added . '</a>';
                    $result  = '<span style="color:green">Передано контактов - '.$total.', невалидных - '.$novalid.', есть в базе - '.$skipped.'. Импортировано - '.$added.'.</span>';
                } else {
                    $result  = '<span style="color:red">Передано контактов - '.$total.', невалидных - '.$novalid.', есть в базе - '.$skipped.'. Импортировано - 0.</span>';
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
        $funnels_counts = $this->db->get_results("SELECT `funnel_id`, COUNT(`contact_id`) AS `count` FROM " . TABLE_CONTACTS_FUNNELS . " GROUP BY `funnel_id`");
                
        $op_nums = array();
        
        if ($funnels_counts AND count($funnels_counts) > 0) {
            foreach ($funnels_counts AS $fcount) {
                $op_nums[$fcount->funnel_id] = $fcount->count;
            }
        }
        
        $this->op_nums = $op_nums;
        
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
                    $sql = "INSERT IGNORE INTO ".TABLE_CONTACTS_FUNNELS." (funnel_id,contact_id) SELECT {$op_to},contact_id FROM ".TABLE_CONTACTS_FUNNELS." WHERE `funnel_id` = {$op_from}";
                    $this->db->query($sql);
                    $success = true;
                    $affected_rows = mysql_affected_rows();
                    if ($affected_rows > 0) {
                        $result = '<span style="color: green">Скопировано клиентов - '.$affected_rows.'.</span>';
                    } else {
                        $result = '<span style="color: blue">Скопировано клиентов - 0.</span>'; 
                    }
                    
                    break;
                case 'move':
                    $this->db->update(TABLE_CONTACTS_FUNNELS, array('funnel_id' => $op_to), array('funnel_id', $op_from));
                    $success = true;
                    $affected_rows = mysql_affected_rows();
                    if ($affected_rows > 0) {
                        $result = '<span style="color: green">Перенесено клиентов - '.$affected_rows.'.</span>';
                    } else {
                        $result = '<span style="color: blue">Перенесено клиентов - 0.</span>'; 
                    }
                    
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
                    
                    $funnels = array();
                    if ($funnel_id < 1) {
                        $_tmp_funnels = $this->db->get_results("SELECT `id` FROM " . TABLE_FUNNELS);
                        if ($_tmp_funnels AND count($_tmp_funnels) > 0) {
                            foreach ($_tmp_funnels AS $funn) {
                                $funnels[] = $funn->id;
                            }
                        }
                    } else {
                        $funnels[] = $funnel_id;
                    }
                    
                    if (count($funnels)) {
                        foreach ($funnels AS $funn) {
                            $this->db->query("INSERT INTO ".TABLE_CONTACTS_FUNNELS." (funnel_id,is_removal,removal_type,removal_at,contact_id) SELECT {$funn},1,1,'{$removal_at}',id FROM ".TABLE_CLIENTS_CONTACTS." WHERE `email` IN({$elist})");
                        }
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
    
    
    /**
    * Возвращает массив с количеством отписанных клиентов по категориям отписания
    * 
    */
    protected function _get_removal_counts()
    {
        $total_direct = $this->db->get_results("SELECT `removal_type`, COUNT(`id`) as count FROM " . TABLE_CLIENTS_CONTACTS . " WHERE `is_removal` = 1 GROUP BY `removal_type` = 1");
    
        $totals = array();
        if ($total_direct AND count($total_direct) > 0) {
            foreach ($total_direct AS $_total) {
                $totals[$_total->removal_type] = $_total->count;
            }
        }
        
        $sql = "SELECT `removal_type`, COUNT(`contact_id`) as count FROM " . TABLE_CONTACTS_FUNNELS . " WHERE `is_removal` = 1 GROUP BY `removal_type`";
        
        
        $total_associated = $this->db->get_results($sql);
        
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
        
        $sql = "SELECT f.contact_id, f.funnel_id, wvf.name, f.removal_at, f.removal_reason, c.email, c.first_name FROM ".TABLE_CONTACTS_FUNNELS." f JOIN ".TABLE_CLIENTS_CONTACTS." c ON f.contact_id = c.id JOIN ".TABLE_FUNNELS." wvf ON wvf.id = f.funnel_id  WHERE f.`is_removal` = 1 AND f.`removal_type` = 1";      
        
        $this->list = $this->db->get_results($sql);
    }
    
    
    public function action_removal2()
    {
        $this->action = 'removal';
        
        $this->funnels_list = $this->db->get_results("SELECT * FROM " . TABLE_FUNNELS);
        
        $this->_get_removal_counts();
        
        $sql = "SELECT f.contact_id, f.funnel_id, wvf.name, f.removal_at, f.removal_reason, c.email, c.first_name FROM ".TABLE_CONTACTS_FUNNELS." f JOIN ".TABLE_CLIENTS_CONTACTS." c ON f.contact_id = c.id JOIN ".TABLE_FUNNELS." wvf ON wvf.id = f.funnel_id  WHERE f.`is_removal` = 1 AND f.`removal_type` = 2";      
        
        $this->list = $this->db->get_results($sql);
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
    
    
    public function action_unsubscribe_contact(){
        $contact_id = $this->_input('contact_id');
        $funnel_id  = $this->_input('funnel_id');
        
        $out = array(
            'success' => false,
            'result'  => NULL
        );
        
        if ($contact_id > 0 AND $funnel_id > 0) {
            $this->db->update(TABLE_CONTACTS_FUNNELS, array(
                'is_removal'   => 1,
                'removal_type' => 1,
                'removal_at'   => date('Y-m-d H:i:s'),
                'updated_at'   => date('Y-m-d H:i:s')
            ), array('contact_id' => $contact_id, 'funnel_id' => $funnel_id));
            
            $out = array(
                'success' => true,
                'result'  => $this->db->get_row("SELECT `contact_id`,`removal_at` FROM " . TABLE_CONTACTS_FUNNELS . " WHERE `contact_id`={$contact_id} AND `funnel_id`={$funnel_id}")
            );
        }
        
        echo json_encode($out);
        
        return false;
    }
    
    
    public function action_blacklist()
    {
        $cid = $this->_input('cid');
        $fid = $this->_input('fid');
        
        if ($cid > 0 AND $fid > 0) {            
            $this->db->query("UPDATE " . TABLE_CONTACTS_FUNNELS . " SET `in_blacklist` = 0 WHERE `contact_id` = {$cid} AND `funnel_id` = {$fid}");
        }
        
        $this->funnels_list = $this->db->get_results("SELECT * FROM " . TABLE_FUNNELS);
        
        $this->list = $this->db->get_results("SELECT f.*, u.first_name, u.email, fun.name AS funnel_name FROM " . TABLE_CONTACTS_FUNNELS . " f JOIN " . TABLE_CLIENTS_CONTACTS . " u ON f.contact_id = u.id JOIN " . TABLE_FUNNELS . " fun ON f.funnel_id = fun.id WHERE f.`in_blacklist` = 1");
    
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
                    
                    $elist_ids = $this->db->get_results("SELECT `id` FROM " . TABLE_CLIENTS_CONTACTS . " WHERE `email` IN({$elist})");
                    
                    array_walk($elist_ids, function(&$item){
                        $item = $item->id;
                    });
                    
                    if (count($elist_ids) > 0) {
                        
                        $blacklist_at = date('Y-m-d H:i:s');
                        
                        // существующие связи воронок с контактами
                        $existants_relations = $this->db->get_results("SELECT `contact_id`, `funnel_id` FROM " . TABLE_CONTACTS_FUNNELS . " WHERE `contact_id` IN(" . implode(',', $elist_ids) . ")");
                        
                        if (!$existants_relations) {
                            $existants_relations = array();
                        }
                        
                        if (count($existants_relations) > 0) {
                            $_temp_existants_relations = array();
                            foreach ($existants_relations AS $existant_relation) {
                                $_temp_existants_relations[$existant_relation->contact_id][] = $existant_relation->funnel_id;
                            }
                            $existants_relations = $_temp_existants_relations; 
                        }
                        
                        if ($funnel_id > 0) {
                            
                            foreach ($elist_ids AS $contact_id) {
                                
                                if (isset($existants_relations[$contact_id])) {
                                    $this->db->update(TABLE_CONTACTS_FUNNELS, array(
                                        'in_blacklist' => 1,
                                        'blacklist_at' => $blacklist_at,
                                    ), array(
                                        'funnel_id'  => $funnel_id,
                                        'contact_id' => $contact_id,
                                    ));
                                } else {
                                    $this->db->insert(TABLE_CONTACTS_FUNNELS, array(
                                        'in_blacklist' => 1,
                                        'blacklist_at' => $blacklist_at,
                                        'funnel_id'  => $funnel_id,
                                        'contact_id' => $contact_id,
                                    ));
                                }
                            }
                            
                        } else {
                            
                            $funnels_ids = $this->db->get_results("SELECT `id` FROM " . TABLE_FUNNELS);
                            array_walk($funnels_ids, function(&$item){
                                $item = $item->id;
                            });
                            
                            if (count($funnels_ids) > 0) {
                                foreach ($elist_ids AS $contact_id) {
                                    
                                    foreach ($funnels_ids AS $funnel_id) {
                                        
                                        if (isset($existants_relations[$contact_id]) AND is_array($existants_relations[$contact_id]) AND in_array($funnel_id, $existants_relations[$contact_id])) {
                                            $this->db->update(TABLE_CONTACTS_FUNNELS, array(
                                                'in_blacklist' => 1,
                                                'blacklist_at' => $blacklist_at,
                                            ), array(
                                                'funnel_id'    => $funnel_id,
                                                'contact_id'   => $contact_id,
                                            ));
                                        } else {
                                            $this->db->insert(TABLE_CONTACTS_FUNNELS, array(
                                                'in_blacklist' => 1,
                                                'blacklist_at' => $blacklist_at,
                                                'funnel_id'    => $funnel_id,
                                                'contact_id'   => $contact_id,
                                            ));
                                        }
                                        
                                    }
                                    
                                }
                            }                            
                        }
                        
                        $result = '<span style="color: green">Контакты добавлены в черный список.</span>'; 
                    }
   
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
    
    
    public function action_custom_fields()
    {
        $edit   = $this->_input('edit');
        $remove = $this->_input('remove');
        
        $this->field_edit = NULL;
        
        if ($remove > 0) {
            $this->db->delete(TABLE_CLIENTS_CUSTOM_FIELDS, array('id' => $remove));
        } elseif ($edit > 0) {
            $this->field_edit = $this->db->get_row("SELECT * FROM " . TABLE_CLIENTS_CUSTOM_FIELDS . " WHERE `id` = {$edit}");
        }
        
        $this->custom_fields = $this->db->get_results("SELECT * FROM " . TABLE_CLIENTS_CUSTOM_FIELDS);
    }
    
    
    public function action_save_custom_field()
    {
        $fid          = $this->_input('fid', 0);
        $field_label  = $this->_input('field_label');
        $field_type   = $this->_input('field_type');
        $field_value  = $this->_input('field_value', NULL);
        
        if ($field_value) {
            $field_value = json_encode($field_value);
        }
        
        $out = array(
            'success' => false,
            'result'  => NULL,
        );
        
        if (!empty($field_label) AND !empty($field_type)) {
            
            $data = array(
                'field_label' => $field_label,
                'field_type'  => $field_type,
                'field_value' => $field_value,
            );
            
            if ($fid > 0) {
                $this->db->update(TABLE_CLIENTS_CUSTOM_FIELDS, $data, array('id' => $fid));
            } else {
                $this->db->insert(TABLE_CLIENTS_CUSTOM_FIELDS, $data);
                
                $fid = $this->db->insert_id;
            }
            
            $out['success'] = true;            
            $out['result']  = $this->db->get_row("SELECT * FROM " . TABLE_CLIENTS_CUSTOM_FIELDS . " WHERE `id` = {$fid}");            
        }
        
        
        echo json_encode($out);
        
        return false;
    }
    
    
    public function action_add_contact_to_funnel()
    {
        $funnel_id  = $this->_input('funnel_id');
        $contact_id = $this->_input('contact_id');
        
        $out = array(
            'success' => false,
            'result'  => 'Не удалось привязать контакт к выбранной воронке.'
        );
        
        if ($funnel_id > 0 AND $contact_id > 0) {
            
            $rols = $this->db->get_row("SELECT `is_removal`, `in_blacklist` FROM " . TABLE_CONTACTS_FUNNELS . " WHERE `funnel_id` = {$funnel_id} AND `contact_id` = {$contact_id}");
            
            if ($rols === NULL) {
                $this->db->insert(TABLE_CONTACTS_FUNNELS, array(
                    'funnel_id'  => $funnel_id,
                    'contact_id' => $contact_id,
                ));
                $out['success'] = true;
                $out['result']  = $this->db->get_row("SELECT `updated_at`, `is_removal` FROM " . TABLE_CONTACTS_FUNNELS . " WHERE `funnel_id` = {$funnel_id} AND `contact_id` = {$contact_id}");
                $out['result']->insert = 1;
                
            }
            elseif ($rols->is_removal != 0) {
                $this->db->update(TABLE_CONTACTS_FUNNELS, array(
                    'is_removal' => 0,
                    'removal_type' => 0,
                    'updated_at' => date('Y-m-d H:i:s')
                ), array(
                    'funnel_id'  => $funnel_id,
                    'contact_id' => $contact_id,
                ));
                $out['success'] = true;
                $out['result']  = $this->db->get_row("SELECT `updated_at`, `is_removal` FROM " . TABLE_CONTACTS_FUNNELS . " WHERE `funnel_id` = {$funnel_id} AND `contact_id` = {$contact_id}");
                $out['result']->insert = 0;
                                
            }
            elseif ($rols->in_blacklist != 0) {
                
                $out['result']  = 'Данный контакт находится в черном списке для этой воронки.';
               
            }
            else {
                
                $out['result']  = 'Данный контакт уже привязан к выбранной воронке.';
                
            }
        }
        
        echo json_encode($out);
        
        return false;
    }
    
}