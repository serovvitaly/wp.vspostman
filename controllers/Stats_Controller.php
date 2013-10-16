<?php

class Stats_Controller extends Base_Controller{
    
    public $title = 'Статистика';
    
    
    public function __construct()
    {
        parent::__construct();
        
        $this->funnels_list = $this->db->get_results("SELECT * FROM " . TABLE_FUNNELS);
        
        $this->mails = $this->db->get_results("SELECT `id`,`title`,`funnel_id` FROM ".TABLE_MAILS." ORDER BY `created`");
    }
    
    
    public function action_give_mail_data()
    {
        $result = array(
            'opened'     => rand(0, 100),
            'clicked'    => rand(0, 100),
            'bounces'    => rand(0, 100),
            'errors'     => rand(0, 100),
            'complaints' => rand(0, 100),
        );
        
        echo json_encode(array('result' => $result));
        
        return false;
    }
    

    public function action_index()
    {
        //
    }

    public function action_opened()
    {
        //
    }

    public function action_clicked()
    {
        //
    }

    public function action_targets()
    {
        //
    }

    public function action_socials()
    {
        //
    }

    public function action_bounces()
    {
        //
    }

    public function action_errors()
    {
        //
    }

    public function action_complaints()
    {
        //
    }    
    
}