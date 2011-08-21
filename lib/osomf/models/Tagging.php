<?php

/**
 * CTagging Model
 *
 *
 * @category    Library
 * @package     Model
 * @author      Brad Bonkoski <brad.bonkoski@yahoo.com>
 * @copyright   Copyright (c) 2011 Fitzers House of Code
 *
 */

namespace osomf\models;

use osomf\DB;

/**
 * Tagging Model
 *
 *
 * @category    Library
 * @package     Model
 * @author      Brad Bonkoski <brad.bonkoski@yahoo.com>
 * @copyright   Copyright (c) 2011 Fitzers House of Code
 *
 */

class Tagging extends DB
{

    public $tags;

    public function __construct($conn = self::RO)
    {
        if (!in_array($conn, $this->_validConn)) {
            throw new \Exception("Invalid Connection");
        }
        parent::__construct("omf_tagging", $conn);

        $this->_table = "tagging";
        $this->_tableKey = "tagId";
    }

    public function getTagsByOwner($ownerId)
    {
        $sql = "select * from tags where ownerId = ?";
        $stmt = $this->_db->prepare($sql);
        $stmt->execute(array($ownerId));
        $res = $stmt->fetchAll();
        foreach($res as $r) {
            $this->tags[$r['tagId']] = $r['tagValue'];
        }
    }

    private function _tagExists($ownerId, $tagValue)
    {
        $sql = "select count(*) as cnt from tags where
            ownerId = ? and tagValue = ?";
        $stmt = $this->_db->prepare($sql);
        $stmt->execute(array($ownerId, $tagValue));
        $res = $stmt->fetch();
        if ($res['cnt'] >= 1 ) {
            return true;
        }
        return false;
    }

    public function createTag($ownerId, $tagValue)
    {
        if (!is_numeric($ownerId)) {
            throw new \Exception("Bad Owner Id");
        }

        if (strlen($tagValue) <= 0 || strlen($tagValue) > 64) {
            throw new \Exception("Tag Value too Long, or Too Short");
        }

        if ($this->_tagExists($ownerId, $tagValue)) {
            throw new \Exception("Tag for Owner already exists");
        }

        $sql = "insert into tags (tagValue, ownerId) values(?,?)";
        $stmt = $this->_db->prepare($sql);
        $stmt->execute(array($tagValue, $ownerId));
        return $this->_db->lastInsertId();
    }
}
