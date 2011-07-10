<?php

use \osomf\models\IncidentModel;

class incident extends ControllerBase
{
    private $_incidentId;

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

    public function view( $params ) 
    {
        $this->setAction("view");
        $params = $this->parseParams($params);
        //print_r($params);
        if (!array_key_exists(0, $params) || !is_numeric($params[0])) {
            return $this->home($params);
        }
        $this->_incidentId = $params[0];

        $this->_loadIncidentData();
        if (count($this->data['err']) > 0 ) {
            $this->redirect('incident', 'view');
        }
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

        $this->_loadIncidentData();
        if (count($this->data['err']) > 0 ) {
            $this->redirect('incident', 'view');
        }
    }

    private function _loadIncidentData()
    {
        try {
            $i = new IncidentModel(IncidentModel::RO);
            $i->loadIncident($this->_incidentId);

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
            $this->data['respProjLink'] = "/osomf/project/view/".$i->getRespProjId();
            
        } catch (Exception $e) {
            $this->_addError($e->getMessage());
        }
    }

    public function search( $params )
    {
        $this->setAction("search");
    }
}
