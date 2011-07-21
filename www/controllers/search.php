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
}