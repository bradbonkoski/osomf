<?php
/**
 * Created by JetBrains PhpStorm.
 * User: bradb
 * Date: 7/13/11
 * Time: 4:16 PM
 * To change this template use File | Settings | File Templates.
 */
 
class search extends ControllerBase
{
    public function __construct($controller = '', $action = '')
    {
        parent::__construct("search", $action);
    }

    public function search()
    {
        return $this->incident();
    }

    public function incident($params = '')
    {
        $this->setAction("incident");
    }

    public function asset($params = '')
    {
        $this->setAction('asset');
    }

    public function quick($params)
    {
        $this->ac = true;
        $params = $this->parseGetParams($params);
        //echo "<pre>".print_r($params, true)."</pre>";
        list($cont, $id) = explode(":", urldecode($params['topSearchBox']));
        //echo "Controller: $cont and ID: $id<br/>";
        $this->redirect($cont, 'view', $id);
    }
}