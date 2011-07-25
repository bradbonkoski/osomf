<?php

/**
 * Incident Model
 *
 *
 * @category    Library
 * @package     Model
 * @author      Brad Bonkoski <brad.bonkoski@yahoo.com>
 * @copyright   Copyright (c) 2011 Fitzers House of Code
 */

 
namespace osomf\models;

use osomf\DB;
use osomf\Validator;
use \osomf\models\IncidentStatus;
use \osomf\models\IncidentSeverity;
use \osomf\models\ProjectModel;
use \osomf\models\UserModel;
use \osomf\models\Worklog;
use \osomf\models\IncidentImpact;

/**
 * Incident Model
 *
 *
 * @category    Library
 * @package     Model
 * @author      Brad Bonkoski <brad.bonkoski@yahoo.com>
 * @copyright   Copyright (c) 2011 Fitzers House of Code
 */

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
    private $_impacts;

    private $_history;
    private $_changes;

    /**
     * Incident Model Constructor
     * @throws \Exception
     * @param string $conn
     */
    public function __construct($conn = self::RO)
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
        $this->_impacts = array();
        $this->_history = array();
        $this->_changes = array();
        
        $this->_table = "incident";
        $this->_tableKey = "incidentId";
    }

    /**
     * Incident Model Validation
     * @throws \Exception
     * @return void
     */
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

    /**
     * @return int
     */
    public function getIncidentId()
    {
        return $this->_incidentId;
    }

    /**
     * @param $val
     * @return void
     */
    public function setIncidentId($val)
    {
        $this->_incidentId = $val;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->_title;
    }

    /**
     * @param $val
     * @return void
     */
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

    /**
     * @return int
     */
    public function getStatusId()
    {
        return $this->_statusId;
    }

    /**
     * @param $val
     * @return void
     */
    public function setStatus($val)
    {
        if (
            $this->_statusId == -1
            || $this->_statusId != $val
        ) {
            $this->_statusId = $val;
            if (!$this->status instanceof IncidentStatus) {
                $this->status = new IncidentStatus();
            }
            $this->status->loadStatus($val);
        }
    }

    /**
     * @return string
     */
    public function getStartTime()
    {
        //perhaps we should provide mapping to User Pref here?
        return $this->_startTime;
    }

    /**
     * @param $val
     * @return void
     */
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

    /**
     * @return int
     */
    public function getCreatedById()
    {
        return $this->_createdBy;
    }

    /**
     * @param $val
     * @return void
     */
    public function setCreatedBy($val)
    {
        // only update if there is no creator
        // i.e. a new incident
        if ($this->_createdBy == -1 ) {
            $this->_createdBy = $val;
        }
    }

    /**
     * @return int
     */
    public function getUpdatedById()
    {
        return $this->_updatedBy;
    }

    /**
     * @return int
     */
    public function getSeverityId()
    {
        return $this->_severityId;
    }

    /**
     * @param $val
     * @return void
     */
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

    /**
     * @return string
     */
    public function getImpact()
    {
        return $this->_impact;
    }

    /**
     * @param $val
     * @return void
     */
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

    /**
     * @return string
     */
    public function getRevImpact()
    {
        return $this->_revImpact;
    }

    /**
     * @param $val
     * @return void
     */
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

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->_desc;
    }

    /**
     * @param $val
     * @return void
     */
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

    /**
     * @return string
     */
    public function getResolveTime()
    {
        return $this->_resolveTime;
    }

    /**
     * @param $val
     * @return void
     */
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

    /**
     * @return string
     */
    public function getResolveSteps()
    {
        return $this->_resolveSteps;
    }

    /**
     * @param $val
     * @return void
     */
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

    /**
     * @return int
     */
    public function getRespProjId()
    {
        return $this->_respProjId;
    }

    /**
     * @param $val
     * @return void
     */
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

    /**
     * @return string
     */
    public function getDetectTime()
    {
        return $this->_detectTime;
    }

    /**
     * @param $val
     * @return void
     */
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

    /**
     * @return string
     */
    public function getCtime()
    {
        return $this->_ctime;
    }

    /**
     * @return string
     */
    public function getMtime()
    {
        return $this->_mtime;
    }

    /**
     * @return array
     */
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

    /**
     * @return array
     */
    public function getImpacts()
    {
        $arr = array();

        $s = new IncidentSeverity();
        foreach ($this->_impacts as $im) {
            $s->loadSeverity($im->getImpactSeverity());
            $arr[] = array(
                'id' => $im->getImpactId(),
                'type' => $im->getImpactType(),
                'item' => $im->getImpactedName(),
                'itemId' => $im->getImpactValId(),
                'desc' => $im->getImpactDesc(),
                'sev' => $s->getSevName(),
            );
        }
        return $arr;
    }

    /**
     * @throws \Exception
     * @param $user
     * @param $changes
     * @return void
     */
    private function _addHistItem($user, $changes)
    {
        $sql = "insert into incidentHistory set incidentId = ?,
                mUser = ?, changes = ?";
            $stmt = $this->_db->prepare($sql);
            if (!$stmt->execute(
                array(
                    $this->_incidentId,
                    $user,
                    serialize(array($changes))
                )
            )) {
                $err = print_r($stmt->errorInfo(), true);
                error_log($err);
                throw new \Exception("Troubles Adding history");
            }
    }

    /**
     * @throws \Exception
     * @param $id
     * @param $userId
     * @return void
     */
    public function removeImpact($id, $userId)
    {
        if (array_key_exists($id, $this->_impacts)) {
            $ii = new IncidentImpact();
            $ii->loadImpacted($id);
            $impacted = $ii->getImpactedName();
            $ii->remove();
            $arr = array(
                'col' => 'Removal of Impact',
                'orig' => $impacted,
                'new' => ''
            );
            $this->_addHistItem($userId, $arr);
        } else {
            throw new \Exception("Impact not tied to this incident");
        }
    }

    /**
     * @throws \Exception
     * @param $userId
     * @param $type
     * @param $val
     * @param $desc
     * @param $sev
     * @return array
     */
    public function addImpact($userId, $type, $val, $desc, $sev)
    {
        $ii = new IncidentImpact(IncidentImpact::RW);
        if (!in_array($type, $ii->validImpacts)) {
            throw new \Exception("Unknown impact type");
        }

        if (!is_numeric($val)) {
            throw new \Exception("Impact Value must be numeric");
        }

        if (!is_numeric($sev)) {
            throw new \Exception("Impact Severity must be numeric");
        }

        //TODO Check for Duplicate Impacts before Insertion
        $ii->setIncidentId($this->_incidentId);
        $ii->setImpactType($type);
        $ii->setImpactVal($val);
        $ii->setImpactDesc($desc);
        $ii->setImpactSeverity($sev);
        try {
            $ii->mapImpactedObject();
        } catch (\Exception $e) {
            throw $e;
        }
        $hist = array(
            'col' => 'New Impact',
            'orig' => '',
            'new' => $ii->getImpactedName()
        );
        try {
            $is = new IncidentSeverity();
            $is->loadSeverity($sev);
        } catch (\Exception $e) {
            throw $e;
        }
        $this->_addHistItem($userId, $hist);
        $ii->save();

        return array(
            'name' => $ii->getImpactedName(),
            'impactId' => $ii->getImpactId(),
            'sev' => $is->getSevName()
        );
    }


    /**
     * @throws \Exception
     * @param $incidentId
     * @return void
     */
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
        $this->loadImpacts();
    }

    /**
     * @throws \Exception
     * @return void
     */
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

    /**
     * @throws \Exception
     * @return void
     */
    public function loadImpacts()
    {
        $sql = "select impactId from impacted
            where incidentId = ?";
        $stmt = $this->_db->prepare($sql);
        if (!$stmt->execute(array($this->_incidentId))) {
            throw new \Exception("Troubles Loading Impacts");
        }

        $rows = $stmt->fetchAll();
        foreach ($rows as $r) {
            $im = new IncidentImpact();
            $im->loadImpacted($r['impactId']);
            $this->_impacts[$r['impactId']] = $im;
        }
    }

    /**
     * @throws \Exception
     * @return void
     */
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
            $this->_incidentId = $this->_db->lastInsertId();
        } else {
            // Update to an existing Incident
            $sql = "update incident set title = ?, statusId = ?,
              start_time = ?, updatedBy = ?, severity = ?,
              impact=?, revImpact=?, description=?, resolveTime=?,
              resolveSteps=?, respProject=?, detect_time=?,
              mtime=NOW() where incidentId = ?";

            //echo "$sql <br/>";

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

    /**
     * @return array
     */
    public function getHistory()
    {
        $sql = "select * from incidentHistory where incidentId = ? order by mtime";
        $stmt = $this->_db->prepare($sql);
        $stmt->execute(array($this->_incidentId));
        $rows = $stmt->fetchAll();
        foreach ($rows as $r) {
            $this->_changes[] = array(
                'time' => $r['mtime'],
                'user' => $r['mUser'],
                'deltas' => unserialize($r['changes'])
            );
        }
        return $this->_changes;
    }

    /**
     * List Home Page Incidents, Place holder to incorporate some data
     * @return array
     */
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

    public function search($cols, $crit)
    {
//        $cols = array('incidentId','title', 'impact');
//
//        $crit = array('title' => 'incident', 'resolveSteps' => 'some');
        $where = array();
        foreach ($crit as $k=>$v) {
                $where[] = "$k like '%$v%'";
        }

        $sql = "select ".implode(', ', $cols)." from incident";
        if(count($where > 0 )) {
                $sql .= " where ".implode(' AND ', $where);
        }

        $stmt = $this->_db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();

    }
}
