<?php
/**
 * Created by JetBrains PhpStorm.
 * User: bradb
 * Date: 7/11/11
 * Time: 9:31 AM
 * To change this template use File | Settings | File Templates.
 */

namespace osomf\worklog;

/**
 * Worklog interface
 * Any Additions or extensions to the incident worklog
 * should implement this interface to maintain
 * consitency.
 */

interface iWorklog {

    /**
     * @abstract
     * @param $data
     * @return void
     */
    public function encodeEntry($data);

    /**
     * @abstract
     * @param $data
     * @return void
     */
    public function decodeEntry($data);
    
}