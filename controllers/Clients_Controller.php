<?php

class Clients_Controller extends Base_Controller{
    
    public $title = 'Клиенты';
    
    public $top_menu = array(
        array(
            'text' => 'Поиск клиентов',
            'act'  => 'index',
            'href' => '#'
        ),
        array(
            'text' => 'Добавить клиента',
            'act'  => 'add',
            'href' => '#'
        ),
        array(
            'text' => 'Импорт клиентов',
            'act'  => 'import',
            'href' => '#'
        ),
        array(
            'text' => 'Копировать в воронку',
            'act'  => 'duplicate',
            'href' => '#'
        ),
        array(
            'text' => 'Отписанные клиенты',
            'act'  => 'removal',
            'href' => '#'
        ),
        array(
            'text' => 'Несуществующие email',
            'act'  => 'undelivered',
            'href' => '#'
        ),
        array(
            'text' => 'Черный список',
            'act'  => 'blacklist',
            'href' => '#'
        ),
    );
    
    public function action_index()
    {
        
        $this->hello = 'WORLD';
    }
    
}