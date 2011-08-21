<?php

namespace osomf;

/**
 * User: bradb
 * Date: 5/28/11
 * Time: 5:13 PM
 * Copyright: (c) 2011 FHC
 */

use \osomf\Conf;
 
class ConfDB extends Conf
{

    public function __construct()
    {
        parent::__construct("db.ini", self::CONFIG_DB);
    }


    public function dumpAllChange()
    {
        print_r($this->_data['change']);
    }

    public function dumpAll()
    {
        print_r($this->_data);
    }

    public function getAllChange()
    {
        return $this->_data['change'];
    }

    public function getAllUser()
    {
        return $this->_data['omf_users'];
    }

    public function getAllAsset()
    {
        return $this->_data['omf_assets'];
    }

    public function getAllIncident()
    {
        return $this->_data['omf_incident'];
    }

    public function getAllTagging()
    {
        return $this->_data['omf_tagging'];
    }

    public function getChangeValue($key)
    {
        return $this->_data['change'][$key];
    }

}
