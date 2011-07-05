<?php

namespace osomf;

use \osomf\Conf;
use \osomf\ConfDB;

class DB extends \PDO
{

    const RO = "ro";
    const RW = "rw";

    const OWNER_USER = 'USER';
    const OWNER_GROUP = 'GROUP';

    const TYPE_CHANGE = "change";
    const TYPE_USER = "omf_users";
    const TYPE_PROBLEM = "problem";
    const TYPE_INCIDENT = "incident";
    const TYPE_ASSET = "omf_assets";
    
    private $_validTypes = array(
        self::TYPE_CHANGE,
        self::TYPE_PROBLEM,
        self::TYPE_INCIDENT,
        self::TYPE_ASSET,
        self::TYPE_USER,
    );

    /**
     * @var _table
     * Set by the inherited class as the name of the table
     */
    protected $_table;
    /**
     * @var _tableKey
     * Set by the inherited class as the primary key/index
     * on the table
     */
    protected $_tableKey;

    protected $_validConn = array(self::RO, self::RW);
    
    protected $_db;

    private function _buildDSN($dbname, $host, $port) 
    {
        $dsn = '';
        $dsn = "mysql:dbname=$dbname;host=$host;port=$port";
        return $dsn;
    }
 
    public function __construct($type, $conn)
    {
        if (!in_array($type, $this->_validTypes)) {
            throw new \Exception("Invalid DB Type");
        }

        $c = new ConfDB();

        $dsn = "";
        $user = "";
        $pass = "";
        $hostIdx = $conn."_host";
        $portIdx = $conn."_port";

        switch($type) 
        {
            case self::TYPE_CHANGE:
                $conf = $c->getAllChange();
                $dsn = "mysql:dbname={$conf['db_name']}";
                $dsn .= ";host={$conf[$hostIdx]}";
                $dsn .= ";port={$conf[$portIdx]}";
                $user = $conf[$conn."_user"];
                $pass = $conf[$conn."_pass"];
                break;
            case self::TYPE_PROBLEM:
                break;
            case self::TYPE_INCIDENT:
                break;
            case self::TYPE_ASSET:
                $conf = $c->getAllAsset();
                $dsn = $this->_buildDSN(
                    $conf['db_name'],
                    $conf[$hostIdx],
                    $conf[$portIdx]
                );
                $user = $conf[$conn."_user"];
                $pass = $conf[$conn."_pass"];
                break;    
            case self::TYPE_USER:
                $conf = $c->getAllUser();
                    //print_r($conf);
                $dsn = $this->_buildDSN(
                    $conf['db_name'], 
                    $conf[$hostIdx],
                    $conf[$portIdx]
                );
                $user = $conf[$conn."_user"];
                $pass = $conf[$conn."_pass"];
                break;
        }
        $this->_db = new \PDO($dsn, $user, $pass);
        $this->_db->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
    }

    /**
     * @param $colName
     * @param $queryStr
     * @return array
     */
    public function autocomplete($colName, $queryStr)
    {
        $sql = "select {$this->_tableKey}, $colName from {$this->_table}
            where upper($colName) like ?";
        error_log($sql);
        $stmt = $this->_db->prepare($sql);
        $stmt->execute(array('%'.strtoupper($queryStr).'%'));
        $stmt->execute();
        $rows = $stmt->fetchAll();
        $arr = array();
        foreach ($rows as $r) {
            $arr[] = array (
                'id' => $r[$this->_tableKey],
                'value' => $r[$colName],
            );

            //$arr[$r[$this->_tableKey]] = $r[$colName];
        }
        return $arr;
    }
    
}
