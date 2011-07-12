<?php

use \osomf\models\IncidentModel;
use \osomf\models\Worklog;

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
        $this->redirect('incident','view', $incidentId);
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
            //exit;
            return $this->home($params);
        }
        $this->_incidentId = $params[0];

        $this->_loadIncidentData();
        if (count($this->data['err']) > 0 ) {
            $this->redirect('incident', 'view');
        }
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
            $this->data['respProjName'] = $i->respProj->projName;
            $this->data['proj'] = $i->getRespProjId();
            $this->data['respProjLink'] =
                    "/osomf/project/view/".$i->getRespProjId();

            $this->data['worklogs'] = $i->getWorklogs();
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

    public function search( $params )
    {
        $this->setAction("search");
    }
}
