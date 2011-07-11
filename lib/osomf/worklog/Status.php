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

class Status implements iWorklog
{
    private $_oldStaus;
    private $_newStatus;
    private $_reason;

    public function __construct()
    {
        $this->_oldStaus = '';
        $this->_newStatus = '';
        $this->reason = '';
    }

    public function encodeEntry($data)
    {
        /**
         * Data should look like:
         * array('orig' => 'something',
         * 'new' => 'something else',
         * 'reason' => 'some reason')
         */
        try {
            $this->_setStatus(
                $data['orig'],
                $data['new'],
                $data['reason']
            );
            $store = array(
                $this->_oldStaus,
                $this->_newStatus,
                $this->_reason
            );
            return serialize($store);
        } catch (Exception $e) {
            throw $e;
        }
    }

    private function _setStatus($old, $new, $reason)
    {
        $is = new IncidentStatus(IncidentStatus::RO);
        if (!$is->validateKey($old)) {
            throw new \Exception(
                "Old Status is not known"
            );
        }
        $is->loadStatus($old);
        $this->_oldStaus = $is->getStatusName();

        if (!$is->validateKey($new)) {
            throw new \Exception(
                "New Status is not known"
            );
        }
        $is->loadStatus($new);
        $this->_newStatus = $is->getStatusName();

        if (strlen($reason) <= 0 ) {
            throw new \Exception(
                "Need to provide some reason"
            );
        }
        $this->_reason = $reason;
    }


    public function decodeEntry($data)
    {
        return unserialize($data);
    }
}