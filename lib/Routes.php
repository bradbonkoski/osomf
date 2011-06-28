<?php

//namespace osomf;

class Routes
{

    private $_controller;
    private $_action;
    private $_params;



    public function __construct($url = "") 
    {
        $this->_controller = "";
        $this->_action = "";
        $this->_params = array();
        if (strlen($url) > 0 ) {
            $routeUri = rtrim($url, '/');
            $data = explode('/', $routeUri);
            //print_r($data);
            if (isset($data[2])) {
                $this->_controller = $data[2];
            }
            if (isset($data[3])) {
                // For autocomplete, catch here!
                if (strpos($data[3], "?") !== false) {
                    $dataNew = explode('?', $data[3]);
                    $this->_action = $dataNew[0];
                    $this->_params[] = $dataNew[1];
                } else {
                    $this->_action = $data[3];
                }
            }
            if (count($data) >= 5) {
                    for ($i=4; $i<count($data); $i++) {
                            $this->_params[] = $data[$i];
                    }
            }
        }
    }

    public function getController() 
    {
        if (strlen($this->_controller) > 0 ) {
            return $this->_controller;
        } else {
            //some error handling here
        }
    }

    public function getAction() 
    {
        if (strlen($this->_action) > 0 ) {
            return $this->_action;
        } else {
            //some error handling here
        }
    }

    public function getParams() 
    {
        return implode("/", $this->_params);
    }
}
