<?php
  
abstract class Base_Controller {
    
    const TPL_EXT = 'php';
    
    protected $layout = 'layout';
    
    protected $db = NULL;
    
    public $action = NULL;
    
    public $icon = ' ';
    
    public $icon_id = 'icon-edit';
    
    
    public function __construct()
    {
        global $wpdb;
        
        $this->db = $wpdb;
    }
    
    
    protected function _input($field, $default = NULL, $source = NULL)
    {
        switch ( strtoupper($source) ) {
            case 'GET':
                $source = $_GET;
                break;
            case 'POST':
                $source = $_POST;
                break;
            default:
                $source = $_REQUEST;
        }
        
        return isset($source[$field]) ? $source[$field] : $default;
    }
    
    
    public function action($action_name = NULL)
    {
        if (empty($action_name)) {
            $action_name = 'index';
        }
        
        $this->action = $action_name;
        
        $action = 'action_' . strtolower($action_name);
        
        if (method_exists($this, $action)) {
            $result = call_user_method($action, $this);
        } else {
            $result = "<p>Метод {$action} не найден в контроллере ".get_class($this)."</p>";
        }        
        
        if ($result !== false) {
            
            $controller = strtolower(get_class($this));
            $controller = explode('_', $controller);
            $controller = $controller[0];
            
            $include_tpl = VSP_DIR . "/templates/{$controller}/{$action_name}." . static::TPL_EXT;
            
            if (file_exists($include_tpl)) {
                ob_start();
                extract( get_object_vars($this) );
                include $include_tpl;
                $content = ob_get_contents();
                ob_end_clean();
            } else {
                $content = "<p>Шаблон {$include_tpl} не найден</p>";
            }            
            
            include VSP_DIR . "/templates/{$this->layout}." . static::TPL_EXT;
        }             
        
        return $result;
    }
    
}
