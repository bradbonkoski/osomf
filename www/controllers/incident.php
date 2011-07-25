<?php

/**
 * Incident Controller
 *
 *
 * @category    Controller
 * @package     Incident
 * @author      Brad Bonkoski <brad.bonkoski@yahoo.com>
 * @copyright   Copyright (c) 2011 Fitzers House of Code
 */

use \osomf\models\IncidentModel;
use \osomf\models\Worklog;
use \osomf\models\UserModel;

/**
 * Incident Controller
 *
 *
 * @category    Controller
 * @package     Incident
 * @author      Brad Bonkoski <brad.bonkoski@yahoo.com>
 * @copyright   Copyright (c) 2011 Fitzers House of Code
 */

class incident extends ControllerBase
{
    private $_incidentId;
    private $_postedData;

    public function __construct($controller = '', $action = '') 
    { 
        parent::__construct("incident", $action);
    }

    public function home($params)
    {
        $this->setAction("home");
        $i = new IncidentModel(IncidentModel::RO);
        $this->data['incidents'] = $i->listHomeIncidents();

    }

    private function _newIncident()
    {
        $i = new IncidentModel(IncidentModel::RW);
        $i->setTitle($this->_postedData['title']);
        $i->setStatus($this->_postedData['incidentStatus']);
        $i->setSeverity($this->_postedData['incidentSev']);
        $i->setDescription($this->_postedData['desc']);
        $i->setImpact($this->_postedData['impact']);
        $i->setRevImpact($this->_postedData['revImpact']);
        $i->setDetectTime($this->_postedData['detectTime']);
        $i->setStartTime($this->_postedData['startTime']);
        $i->setCreatedBy($_COOKIE['userId']);
        $i->save();
        $incidentId = $i->getIncidentId();
        $this->redirect('incident', 'view', $incidentId);
    }

    public function add()
    {
        $this->setAction("add");
        if (isset($_POST['subIncident'])) {
            //create new incident
            $this->_postedData = $_POST;
            $this->_newIncident();
        }

        $stat = new \osomf\models\IncidentStatus();
        $this->data['statusVals'] = $stat->getAllStatus();

        $sev = new \osomf\models\IncidentSeverity();
        $this->data['sevVals'] = $sev->getAllSeverity();

    }

    public function view( $params ) 
    {
        $this->setAction("view");
        $params = $this->parseParams($params);
        //print_r($params);
        if (!array_key_exists(0, $params) || !is_numeric($params[0])) {
            error_log("Redirect here!");
            return $this->home($params);
        }
        $this->_incidentId = $params[0];

        $this->_loadIncidentData();

        if (count($this->data['err']) > 0 ) {
            if ($this->_test) {
                print_r($this->data['err']);
            }
            $this->redirect('incident', 'view');
        }

        $stat = new \osomf\models\IncidentStatus();
        $this->data['statusVals'] = $stat->getAllStatus();

        $sev = new \osomf\models\IncidentSeverity();
        $this->data['sevValues'] = $sev->getAllSeverity();
    }



    private function _IncidentUpdate()
    {
        $i = new IncidentModel(IncidentModel::RW);
        $i->loadIncident($this->_incidentId);
        $i->setTitle($this->_postedData['title']);
        $i->setDescription($this->_postedData['desc']);
        $i->setImpact($this->_postedData['impact']);
        $i->setRevImpact($this->_postedData['revImpact']);
        $i->setDetectTime($this->_postedData['detectTime']);
        $i->setStartTime($this->_postedData['startTime']);
        $i->setResolveTime($this->_postedData['resolveTime']);
        $i->setResolveSteps($this->_postedData['resolveSteps']);
        $i->setRespProjId($this->_postedData['projId']);
        $i->setSeverity($this->_postedData['severity']);
        $i->save();

    }



    public function edit($params)
    {
        $this->setAction("edit");
        $params = $this->parseParams($params);
        //print_r($params);
        if (!array_key_exists(0, $params) || !is_numeric($params[0])) {
            return $this->home($params);
        }
        $this->_incidentId = $params[0];
        
        if (isset($_POST['subIncident'])) {
            $this->_postedData = $_POST;
            $this->_IncidentUpdate();
            //echo "<pre>".print_r($_POST, true)."</pre>";

        }

        $this->_loadIncidentData();
        if (count($this->data['err']) > 0 ) {
            error_log(print_r($this->data['err'], true));
            $this->redirect('incident', 'view');
        }

        $sev = new \osomf\models\IncidentSeverity();
        $this->data['sevValues'] = $sev->getAllSeverity();
    }

    private function _loadIncidentData()
    {
        try {
            $i = new IncidentModel(IncidentModel::RO);
            $i->loadIncident($this->_incidentId);

            $this->data['incidentId'] = $i->getIncidentId();
            $this->data['incidentTitle'] = $i->getTitle();
            $this->data['status'] = $i->status->getStatusName();
            $this->data['severity'] = $i->severity->getSevName();
            $this->data['desc'] = $i->getDescription();
            $this->data['impact'] = $i->getImpact();
            $this->data['revImpact'] = $i->getRevImpact();

            $this->data['detectTime'] = $i->getDetectTime();
            $this->data['startTime'] = $i->getStartTime();
            $this->data['ctime'] = $i->getCtime();
            $this->data['mtime'] = $i->getMtime();

            $this->data['resolveTime'] = $i->getResolveTime();
            $this->data['resolveSteps'] = $i->getResolveSteps();
            if ($i->respProj instanceof \osomf\models\ProjectModel) {
                $this->data['respProjName'] = $i->respProj->projName;
            }
            $this->data['proj'] = $i->getRespProjId();
            $this->data['respProjLink'] =
                    "/osomf/project/view/".$i->getRespProjId();

            $this->data['worklogs'] = $i->getWorklogs();
            $this->data['impacts'] = $i->getImpacts();
            //echo "<pre>".print_r($this->data, true)."</pre>";
            
        } catch (Exception $e) {
            $this->_addError($e->getMessage());
        }
    }

    public function addWorkLog($params)
    {
        // workaround to disengage the templating
        $this->ac = true;
        $data = $this->parseGetParams($params);
        error_log(print_r($data, true));
        $userId = $_COOKIE['userId'];
        error_log("User id: $userId");
        $wl = new Worklog(Worklog::RW);
        $wl->newWorkLog(
            $data['id'],
            $userId,
            WorkLog::TYPE_WORKLOG,
            urldecode($data['text'])
        );
        $wl->save();
        echo "<tr>
            <td>bradb</td>
            <td>".date('Y-m-d H:m:s')."</td>
            <td>WORKLOG</td>
            <td>".urldecode($data['text'])."</td>
        </tr>";
    }

    public function addImpact($params)
    {
        $this->ac = true;
        $data = $this->parseGetParams($params);
        $userId = $_COOKIE['userId'];
        $i = new IncidentModel(IncidentModel::RW);
        $i->loadIncident($data['incident']);
        $vals = $i->addImpact(
            $userId,
            $data['type'],
            $data['entity'],
            urldecode($data['desc']),
            $data['sev']
        );
        $ret = "<tr>
            <td>{$data['type']}</td>
            <td>{$vals['name']}</td>
            <td>{$vals['sev']}</td>
            <td>".urldecode($data['desc'])."</td>
            <td>
                <img
                    src=\"/osomf/www/img/cancel.png\"
                    onclick=\"javascript:removeImpact('{$vals['impactId']}')\"
                />
            </td>
            </tr>";
        echo $ret;
    }

    public function removeImpact($params)
    {
        $this->ac = true;
        $data = $this->parseGetParams($params);
        $userId = $_COOKIE['userId'];
        $i = new IncidentModel(IncidentModel::RW);
        try {
            $i->loadIncident($data['incidentId']);
            $i->removeImpact($data['impactId'], $userId);
        } catch (\Exception $e) {
            error_log("Problem removing impact");
        }
    }

    public function statusChange( $params )
    {
        $this->ac = true;
        $data = $this->parseGetParams($params);
        $userId = $_COOKIE['userId'];
        $i = new \osomf\models\IncidentModel(IncidentModel::RW);
        $i->loadIncident($data['id']);
        $origStatus = $i->getStatusId();
        $i->setStatus($data['newStatus']);
        $wl = new Worklog(Worklog::RW);
        $wlData = array(
            'orig' => $origStatus,
            'new' => $data['newStatus'],
            'reason' => urldecode($data['reason'])
        );
        $wl->newWorkLog(
            $data['id'],
            $userId,
            Worklog::TYPE_STATUS,
            $wlData
        );
        $i->save();
        echo $i->status->getStatusName();
    }

    public function hist($params)
    {
        $this->setAction("hist");
        $params = $this->parseParams($params);
        //print_r($params);
        if (!array_key_exists(0, $params) || !is_numeric($params[0])) {
            return $this->home($params);
        }
        $this->_incidentId = $params[0];
        $this->data['pageTitle'] = "History for Incident: ".$this->_incidentId;
        $this->data['incidentId'] = $this->_incidentId;
        $i = new IncidentModel();
        $i->setIncidentId($this->_incidentId);
        $changes = $i->getHistory();
        $u = new UserModel();

        foreach ($changes as &$c) {
            $u->fetchUserInfo($c['user']);
            $c['username'] = $u->uname;
        }
        //echo "<pre>".print_r($changes, true)."</pre>";

        $this->data['changes'] = $changes;
    }

    public function search( $params )
    {
        $this->setAction("search");
    }
}
