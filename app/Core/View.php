<?php

namespace Core;

use Core\Auth;

class View {

    public function __construct() {
        
    }

    public function render($template, $data = null, $layout = null)
    {        
        $content = $this->getTemplatePath($template);
        
        if ($data) {
            extract($data);
        }

        if ($layout) {
            require $this->getTemplatePath($layout);
        } else {
            require $content;
        }
    }
    
    public function getTemplatePath($template)
    {
        return APP_DIR . '/../src/views/'.$template.'.php';
    }
    
    public static function renderController($action, $data = [])
    {
        list($class, $action) = explode(':', $action, 2);
        $class = 'Controller\\' . $class . 'Controller';
        call_user_func_array(array(new $class(), $action), $data);
    }
    
    public function path($name, array $parameters = [])
    {
        global $router;
        return $router->generate($name, $parameters);
    }
    
    public function url($name, array $parameters = [])
    {
        global $router;
        return $router->generate($name, $parameters, true);
    }
    
    public function getUser()
    {
        return Auth::user();
    }
    
    public function hasFlash($alias)
    {
        $flashBag = isset($_SESSION['flashBag']) ? $_SESSION['flashBag'] : [];
        return isset($flashBag[$alias]) ? true : false;
    }
    
    public function getFlash($alias)
    {
        $flashBag = isset($_SESSION['flashBag']) ? $_SESSION['flashBag'] : [];
        if (isset($flashBag[$alias])) {
            $message = $flashBag[$alias];
            unset($flashBag[$alias]);
            $_SESSION['flashBag'] = $flashBag;
            return $message;
        }
    }
}