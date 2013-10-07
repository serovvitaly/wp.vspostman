<?php
  
abstract class Base_Controller {
    
    const TPL_EXT = 'php';
    
    protected $layout = 'layout';
    
    public $action = NULL;
    
    public function action($action_name = NULL)
    {
        if (empty($action_name)) {
            $action_name = 'index';
        }
        
        $this->action = $action_name;
        
        $action = 'action_' . strtolower($action_name);
        
        $result = call_user_method($action, $this);
        
        if ($result !== false) {
            
            $controller = strtolower(get_class($this));
            $controller = explode('_', $controller);
            $controller = $controller[0];
            
            ob_start();
            
            extract( get_object_vars($this) );
            
            include VSP_DIR . "/templates/{$controller}/{$action_name}." . static::TPL_EXT;
            
            $content = ob_get_contents();
            
            ob_end_clean();
            
            include VSP_DIR . "/templates/{$this->layout}." . static::TPL_EXT;
        }             
        
        return $result;
    }
    
}
