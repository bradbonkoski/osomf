<?php

class change extends ControllerBase
{

    public function __construct( $controller = "", $action = "") 
    {
        parent::__construct("change", $action);
    }

    public function view( $params ) 
    {
        //require('lib/Template.php');
        $this->setAction("view");
        //echo "I'm in the Change View!<br/>";
        $parms = $this->parseParams($params);
        //echo "<pre>".print_r($this->parseParams($params), true)."</pre>";
        if (array_key_exists("format", $parms)) {
            if ($parms['format'] == 'xml') {
                //swap out the view for an XML one...
            }
        }

        $this->data['title'] = "Here is a variable set title";


        $this->data['testArray'] = array('some', 'one', 'is','here');

    }

    public function add( $params ) 
    {
        echo "I'm in the change add action<br/>";
        echo "<pre>".print_r($params, true)."</pre>";
    }
}
