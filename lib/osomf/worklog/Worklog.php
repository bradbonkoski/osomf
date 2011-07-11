<?php
/**
 * Created by JetBrains PhpStorm.
 * User: bradb
 * Date: 7/10/11
 * Time: 7:36 AM
 * To change this template use File | Settings | File Templates.
 */
 
namespace osomf\worklog;

use \osomf\worklog\iWorklog;
use \osomf\models\IncidentStatus;

class Worklog implements iWorklog
{
    public function encodeEntry($data)
    {
        return $data;
    }

    public function decodeEntry($data)
    {
        return $data;
    }

}