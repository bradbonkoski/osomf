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

    private $_history;
    private $_changes;


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
        $this->_history = array();
        $this->_changes = array();
        
        $this->_table = "incident";
        $this->_tableKey = "incidentId";
    }

    private function _validate()
    {
        $validators = array(
            '_incidentId' => array(
                Validator::IS_NUM => true,
            ),
            '_title' => array(
                Validator::IS_STRING => true,
                Validator::STRLEN => array('min' => 1, 'max' => 128),
            ),
            '_statusId' => array(
                Validator::IS_NUM => true,
            ),
            '_desc' => array(
                Validator::IS_STRING => true,
                Validator::STRLEN => array('min' => 0, 'max' => 2056),
            ),
        );

        foreach ($validators as $key => $val) {
            //echo "Validating: $key [{$this->$key}]\n";
            $v = new Validator($val);
            $v->validate($this->$key);
            if ($v->errNo > 0 ) {
                $errs = $v->getErrors();
                throw new \Exception($errs[0]);
            }
        }
    }

    public function getIncidentId()
    {
        return $this->_incidentId;
    }

    public function getTitle()
    {
        return $this->_title;
    }

    public function setTitle($val)
    {
        if (
            strlen($this->_title) > 0
            && $this->_title != $val
        ) {
            $this->_history[] = array(
                'col' => 'Title',
                'orig' => $this->_title,
                'new' => $val,
            );
        }
        $this->_title = $val;
    }

    public function getStatusId()
    {
        return $this->_statusId;
    }

    public function setStatus($val)
    {
        // only do this if there is no status
        // otherwise it will be handled elsewhere
        if ($this->_statusId == -1 ) {
            $this->_statusId = $val;
        }
    }

    public function getStartTime()
    {
        //perhaps we should provide mapping to User Pref here?
        return $this->_startTime;
    }

    public function setStartTime($val)
    {
        if (
            strlen($this->_startTime) > 0
            && $this->_startTime != $val
        ) {
            $this->_history[] = array(
                'col' => 'Start Time',
                'orig' => $this->_startTime,
                'new' => $val,
            );
        }
        $this->_startTime = $val;
    }

    public function getCreatedById()
    {
        return $this->_createdBy;
    }

    public function setCreatedBy($val)
    {
        // only update if there is no creator
        // i.e. a new incident
        if ($this->_createdBy == -1 ) {
            $this->_createdBy = $val;
        }
    }

    public function getUpdatedById()
    {
        return $this->_updatedBy;
    }

    public function setUpdatedBy($val)
    {
        $this->_updatedBy = $val;
    }

    public function getSeverityId()
    {
        return $this->_severityId;
    }

    public function setSeverity($val)
    {
        if (
            $this->_severityId > 0
            && $this->_severityId != $val
        ) {

            $arr =  array(
                'col' => 'Severity',
                'orig' => $this->severity->getSevName(),
                //'new' => $val,
            );
            $this->severity->loadSeverity($val);
            $arr['new'] = $this->severity->getSevName();
            $this->_history[] = $arr;
        }
        $this->_severityId = $val;
    }

    public function getImpact()
    {
        return $this->_impact;
    }

    public function setImpact($val)
    {
        if (
            strlen($this->_impact) > 0
            && $this->_impact != $val
        ) {
            $this->_history[] = array(
                'col' => 'Impact',
                'orig' => $this->_impact,
                'new' => $val,
            );
        }
        $this->_impact = $val;
    }

    public function getRevImpact()
    {
        return $this->_revImpact;
    }

    public function setRevImpact($val)
    {
        if (
            strlen($this->_revImpact) > 0
            && $this->_revImpact != $val
        ) {
            $this->_history[] = array(
                'col' => 'Revenue Impact',
                'orig' => $this->_revImpact,
                'new' => $val,
            );
        }
        $this->_revImpact = $val;
    }

    public function getDescription()
    {
        return $this->_desc;
    }

    public function setDescription($val)
    {
        if (
            strlen($this->_desc) > 0
            && $this->_desc != $val
        ) {
            $this->_history[] = array(
                'col' => 'Description',
                'orig' => $this->_desc,
                'new' => $val,
            );
        }
        $this->_desc = $val;
    }

    public function getResolveTime()
    {
        return $this->_resolveTime;
    }

    public function setResolveTime($val)
    {
        if (
            strlen($this->_resolveTime) > 0
            && $this->_resolveTime != $val
        ) {
            $this->_history[] = array(
                'col' => 'Resolve Time',
                'orig' => $this->_resolveTime,
                'new' => $val,
            );
        }
        $this->_resolveTime = $val;
    }

    public function getResolveSteps()
    {
        return $this->_resolveSteps;
    }

    public function setResolveSteps($val)
    {
        if (
            strlen($this->_resolveSteps) > 0
            && $this->_resolveSteps != $val
        ) {
            $this->_history[] = array(
                'col' => 'Resolve Steps',
                'orig' => $this->_resolveSteps,
                'new' => $val,
            );
        }
        $this->_resolveSteps = $val;
    }

    public function getRespProjId()
    {
        return $this->_respProjId;
    }

    public function setRespProjId($val)
    {
        if (
            $this->_respProjId > 0
            && $this->_respProjId != $val
        ) {

            $arr = array(
                'col' => 'Responsible Project',
                'orig' => $this->respProj->getProjName(),
                //'new' => $val,
            );
            $this->respProj->fetchProjInfo($val);
            $arr['new'] = $this->respProj->getProjName();
            $this->_history[] = $arr;
        }
        $this->_respProjId = $val;
    }

    public function getDetectTime()
    {
        return $this->_detectTime;
    }

    public function setDetectTime($val)
    {
        if (
            strlen($this->_detectTime) > 0
            && $this->_detectTime != $val
        ) {
            $this->_history[] = array(
                'col' => 'Detect Time',
                'orig' => $this->_detectTime,
                'new' => $val,
            );
        }
        $this->_detectTime = $val;
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

    public function save()
    {
        try {
            $this->_validate();
        } catch (\Exception $e) {
            //echo "Validation Exception!\n";
            throw $e;
        }

        if ($this->_incidentId < 0 ) {
            // new record
            $sql = "insert into incident (title, statusId, start_time,
                createdBy, severity, impact, revImpact,
                description, resolveTime, resolveSteps, respProject,
                detect_time)
                values (?,?,?,?,?,?,?,?,?,?,?,?)";
            $stmt = $this->_db->prepare($sql);
            if (!$stmt->execute(
                array(
                    $this->_title,
                    $this->_statusId,
                    $this->_startTime,
                    $this->_createdBy,
                    $this->_severityId,
                    $this->_impact,
                    $this->_revImpact,
                    $this->_desc,
                    $this->_resolveTime,
                    $this->_resolveSteps,
                    $this->_respProjId,
                    $this->_detectTime
                )
            )) {
                print_r($stmt->errorInfo());
            }
        } else {
            // Update to an existing Incident
            $sql = "update incident set title = ?, statusId = ?,
              start_time = ?, updatedBy = ?, severity = ?,
              impact=?, revImpact=?, description=?, resolveTime=?,
              resolveSteps=?, respProject=?, detect_time=?,
              mtime=NOW() where incidentId = ?";
            $stmt = $this->_db->prepare($sql);
            if (!$stmt->execute(
                array(
                    $this->_title,
                    $this->_statusId,
                    $this->_startTime,
                    $this->_updatedBy,
                    $this->_severityId,
                    $this->_impact,
                    $this->_revImpact,
                    $this->_desc,
                    $this->_resolveTime,
                    $this->_resolveSteps,
                    $this->_respProjId,
                    $this->_detectTime,
                    $this->_incidentId
                )
            )) {
                print_r($stmt->errorInfo());
            }
            if (count($this->_history) > 0 ) {
                $sql = "insert into incidentHistory set incidentId = ?,
                mUser = ?, changes = ?";
                $stmt = $this->_db->prepare($sql);
                $stmt->execute(
                    array(
                        $this->_incidentId,
                        1,
                        serialize($this->_history),
                    )
                );
            }
            //echo "<pre>".print_r($this->_history, true)."</pre>";

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
