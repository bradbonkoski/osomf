<?php

require_once 'Twig/Autoloader.php';
use \Navigation;

class Template
{

    protected $_variables = array();
    private $_controller;
    private $_action;
    private $_twig;


    function __construct($controller, $action) 
    {
        $this->_controller = $controller;
        $this->_action = $action;
        Twig_Autoloader::register();
        error_log(BASE_PATH."/www/views");
        $loader = new Twig_Loader_Filesystem(BASE_PATH."/www/views");
        $this->_twig = new Twig_Environment(
            $loader, array(
                'cache' => '/tmp/compilation_cache',
                'auto_reload' => true,
                'debug' => true,
            )
        );

    }

    
    public function render() 
    {
//
//        extract($this->_variables);
//
//        if (file_exists(PATH."/www/views/header.twig")) {
//            include(PATH."/www/views/header.twig");
//        }
//
//        include("www/views/{$this->_controller}/{$this->_action}.phtml");
//
//        if (file_exists(PATH."/www/views/footer.twig")) {
//            include(PATH."/www/views/footer.twig");
//        }
        $nav = new Navigation();
        $this->_variables['nav'] = $nav->getMenu();

        //echo "<pre>".print_r($this->_variables, true)."</pre>";
        //echo $this->_controller."/".$this->_action.".phtml<br/>";
        $template = $this->_twig->loadTemplate(
            $this->_controller."/".$this->_action.".twig"
        );

        $template->display($this->_variables);

    }

    public function set($name, $value) 
    {
        $this->_variables[$name] = $value;
    }
}
