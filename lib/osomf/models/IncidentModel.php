<?php
/**
 * Created by JetBrains PhpStorm.
 * User: bradb
 * Date: 7/7/11
 * Time: 2:22 PM
 * To change this template use File | Settings | File Templates.
 */
 
namespace osomf\models;

use osomf\DB;
use osomf\Validator;
use \osomf\models\IncidentStatus;
use \osomf\models\IncidentSeverity;
use \osomf\models\ProjectModel;
use \osomf\models\UserModel;
use \osomf\models\Worklog;

class IncidentModel extends DB
{
    private $_incidentId;
    private $_title;
    private $_statusId;
    public $status;
    private $_startTime;
    private $_createdBy;
    public $createdByUser;
    private $_updatedBy;
    public $updatedByUser;
    private $_severityId;
    public $severity;
    private $_impact;
    private $_revImpact;
    private $_desc;
    private $_resolveTime;
    private $_resolveSteps;
    private $_respProjId;
    public $respProj;
    private $_detectTime;
    private $_ctime;
    private $_mtime;
    private $_worklogs;


    public function __construct($conn)
    {
        if (!in_array($conn, $this->_validConn)) {
            throw new \Exception("Invalid Connection");
        }
        parent::__construct("omf_incident", $conn);

        $this->_incidentId = -1;
        $this->_title = '';
        $this->_statusId = -1;
        $this->status = NULL;
        $this->_startTime = '';
        $this->_createdBy = -1;
        $this->createdByUser = NULL;
        $this->_updatedBy = -1;
        $this->updatedByUser = NULL;
        $this->_severityId = -1;
        $this->severity = NULL;
        $this->_impact = '';
        $this->_revImpact = '';
        $this->_desc = '';
        $this->_resolveTime = '';
        $this->_resolveSteps = '';
        $this->_respProjId = -1;
        $this->respProj = NULL;
        $this->_detectTime = '';
        $this->_ctime = '';
        $this->_mtime = '';
        $this->_worklogs = array();
        
        $this->_table = "incident";
        $this->_tableKey = "incidentId";
    }

    public function getIncidentId()
    {
        return $this->_incidentId;
    }

    public function getTitle()
    {
        return $this->_title;
    }

    public function getStatusId()
    {
        return $this->_statusId;
    }

    public function getStartTime()
    {
        //perhaps we should provide mapping to User Pref here?
        return $this->_startTime;
    }

    public function getCreatedById()
    {
        return $this->_createdBy;
    }

    public function getUpdatedById()
    {
        return $this->_updatedBy;
    }

    public function getSeverityId()
    {
        return $this->_severityId;
    }

    public function getImpact()
    {
        return $this->_impact;
    }

    public function getRevImpact()
    {
        return $this->_revImpact;
    }

    public function getDescription()
    {
        return $this->_desc;
    }

    public function getResolveTime()
    {
        return $this->_resolveTime;
    }

    public function getResolveSteps()
    {
        return $this->_resolveSteps;
    }

    public function getRespProjId()
    {
        return $this->_respProjId;
    }

    public function getDetectTime()
    {
        return $this->_detectTime;
    }

    public function getCtime()
    {
        return $this->_ctime;
    }

    public function getMtime()
    {
        return $this->_mtime;
    }

    public function getWorklogs()
    {
        // might need something a little better here!
        $arr = array();

        $u = new UserModel(self::RO);

        foreach ($this->_worklogs as $wl) {
            $u->fetchUserInfo($wl->getUserId());
            $arr[] = array(
                'user' => $u->uname,
                'mtime' => $wl->getMtime(),
                'type' => $wl->getType(),
                'data' => $wl->getData(),
            );
        }
        return $arr;
    }

    public function loadIncident($incidentId)
    {
        if (!is_numeric($incidentId) || $incidentId <= 0 ) {
            throw new \Exception("Bad incident Id");
        }

        $sql = "select * from incident where incidentId = ?";
        $stmt = $this->_db->prepare($sql);
        $stmt->execute(array($incidentId));
        $row = $stmt->fetch();
        if ($row === false) {
            throw new \Exception("No CI for id: {$incidentId} found");
        }

        $this->_incidentId = $incidentId;
        $this->_title = $row['title'];
        $this->_statusId = $row['statusId'];
        $this->status = new IncidentStatus(self::RO);
        $this->status->loadStatus($this->_statusId);
        $this->_startTime = $row['start_time'];
        $this->_createdBy = $row['createdBy'];
        $this->createdByUser = new UserModel(self::RO);
        $this->createdByUser->fetchUserInfo($this->_createdBy);
        $this->_updatedBy = $row['updatedBy'];
        if ($this->_updatedBy > 0 ) {
            $this->updatedByUser = new UserModel(self::RO);
            $this->updatedByUser->fetchUserInfo($this->_updatedBy);
        }
        $this->_severityId = $row['severity'];
        $this->severity = new IncidentSeverity(self::RO);
        $this->severity->loadSeverity($this->_severityId);
        $this->_impact = $row['impact'];
        $this->_revImpact = $row['revImpact'];
        $this->_desc = $row['description'];
        $this->_resolveTime = $row['resolveTime'];
        $this->_resolveSteps = $row['resolveSteps'];
        $this->_respProjId = $row['respProject'];
        if ($this->_respProjId > 0 ) {
            $this->respProj = new ProjectModel(self::RO);
            $this->respProj->fetchProjInfo($this->_respProjId);
        }
        $this->_detectTime = $row['detect_time'];
        $this->_ctime = $row['ctime'];
        $this->_mtime = $row['mtime'];
        $this->loadWorkLogs();
    }

    public function loadWorklogs()
    {
        $sql = "select worklogId from worklog
            where incidentId = ? order by mtime";
        $stmt = $this->_db->prepare($sql);
        if (!$stmt->execute(array($this->_incidentId))) {
            error_log(print_r($stmt->errorInfo(), true));
            throw new \Exception(
                "Troubles Fetching Worklog Info"
            );
        }

        $rows = $stmt->fetchAll();
        foreach ($rows as $r) {
            $wl = new Worklog(self::RO);
            $wl->getWlEntry($r['worklogId']);
            $this->_worklogs[] = $wl;
        }
    }

    public function listHomeIncidents()
    {
        $sql = "select incidentId, title, st.statusName as status,
            se.sevName as severity, start_time
            from incident i join status st on st.statusId = i.statusId
            join severity se on se.sevId = i.severity
            where i.statusId in (1,2,3)";
        $stmt = $this->_db->prepare($sql);
        $stmt->execute();
        $rows = $stmt->fetchAll();
        return $rows;
    }
}
