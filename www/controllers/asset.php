<?php

use \osomf\models\AssetModel;

class asset extends ControllerBase
{
    private $_ciid;

    private $_postedData;
    

    public function __construct($controller = '', $action = '') 
    { 
        parent::__construct("asset", $action);
        $this->ciid = 0;
    }

    private function _pullAssetInfo()
    {
        try {
        $a = new AssetModel(AssetModel::RO);
        $a->loadAsset($this->_ciid);
        $this->data['ciid'] = $a->getAssetId();
        $this->data['title'] = "Asset information for: ".$a->ciName;
        $this->data['ciName'] = $a->ciName;
        $this->data['ciDesc'] = $a->ciDesc;
        $this->data['status'] = $a->ciStatus->statusName;
        $this->data['statusId'] = $a->ciStatus->getStatusId();
        $this->data['ownerId'] = $a->getOwnerId();
        $this->data['ownerType'] = $a->getOwnerType();
        if ($a->getOwnerType() == AssetModel::OWNER_USER) {
            $ownerCont = 'user';
            $this->data['ownerName'] = $a->owner->uname;
        } else {
            $this->data['ownerName'] = $a->owner->groupName;
            $ownerCont = 'group';
        }
        $this->data['ownerLink'] = "/osomf/$ownerCont/view/".$a->getOwnerId();
        $times = $a->getAssetTimes();
        $this->data['ctime'] = $times['created'];
        $this->data['mtime'] = $times['modified'];
        $this->data['type'] = $a->ciType->typeName;
        $this->data['typeId'] = $a->ciType->getTypeId();
        $this->data['serial'] = $a->ciSerialNum;
        $this->data['acquired'] = $a->acquiredDate;
        $this->data['retired'] = $a->isRetired;
        $this->data['disposed'] = $a->disposalDate;

        // relations
        if ($a->netParent instanceof AssetModel) {
            $this->data['netParent'] = $a->netParent->getAssetId();
            $this->data['netParentName'] = $a->netParent->ciName;
            $this->data['netParentLink'] =
                "/osomf/asset/view/".$a->netParent->getAssetId();
        } else {
            $this->data['netParent'] = 0;
        }

        if ($a->phyParent instanceof AssetModel) {
            $this->data['phyParent'] = $a->phyParent->getAssetId();
            $this->data['phyParentName'] = $a->phyParent->ciName;
            $this->data['phyParentLink'] =
                "/osomf/asset/view/".$a->phyParent->getAssetId();
        } else {
            $this->data['phyParent'] = 0;
        }

        // project info
        if ($a->project instanceof \osomf\models\ProjectModel) {
            $this->data['proj'] = $a->project->getProjId();
            $this->data['projName'] = $a->project->projName;
            $this->data['projLink'] =
                "/osomf/project/view/".$a->project->getProjId();
        } else {
            error_log("No Project Defined?");
            $this->data['proj'] = 0;
        }

        //location info
        if ($a->loc instanceof \osomf\models\LocationModel) {
            $this->data['loc'] = $a->loc->getLocId();
            $this->data['locName'] = $a->loc->getLocName();
            $this->data['locLink'] =
                "/osomf/location/view/".$a->loc->getLocId();
        } else {
            $this->data['loc'] = 0;
        }
        } catch (Exception $e) {
            $this->_addError($e->getMessage());
        }
    }

    public function view($params)
    {
        $this->setAction("view");
        $params = $this->parseParams($params);

        if (!array_key_exists(0, $params) || !is_numeric($params[0])) {
            error_log("Sending to display");
            return $this->display();
        }
        
        $this->_ciid = $params[0];
        
        $this->_pullAssetInfo();
        if (count($this->data['err']) > 0 ) {
            $this->redirect('asset', 'view');
        }
        //echo "<pre>".print_r($this->data, true)."</pre>";
    }

    public function display()
    {
        $this->setAction("home");
        $a = new AssetModel(AssetModel::RO);
        $ret = $a->listAssets();
        foreach ($ret as $r) {
            $this->data['assets'][$r['ciid']] = $r;
        }
    }

    private function _assetNew()
    {
        $a = new AssetModel(AssetModel::RW);
        $a->ciName = $this->_postedData['ciName'];
        $a->ciDesc = $this->_postedData['ciDesc'];
        $a->updateOwner(
            $this->_postedData['ownerTypeVal'],
            $this->_postedData['ownerId']
        );
        $a->updateProject($this->_postedData['projId']);
        $a->updateStatus($this->_postedData['statusId']);
        $a->updateType($this->_postedData['typeId']);
        if (is_numeric($this->_postedData['phyId'])) {
            $a->updatePhyParent($this->_postedData['phyId']);
        }
        $a->updateNetParent($this->_postedData['netId']);
        $a->updateLoc($this->_postedData['locId']);
        $a->save();
    }

    private function _assetUpdate()
    {
        $a = new AssetModel(AssetModel::RW);
        $a->loadAsset($this->_ciid);
        $a->ciName = $this->_postedData['ciName'];
        $a->ciDesc = $this->_postedData['ciDesc'];
        $a->ciSerialNum = $this->_postedData['ciSerial'];
        $a->updateOwner(
            $this->_postedData['ownerTypeVal'],
            $this->_postedData['ownerId']
        );
        $a->updateProject($this->_postedData['projId']);
        $a->updateStatus($this->_postedData['statusId']);
        $a->updateType($this->_postedData['typeId']);
        if (
            is_numeric($this->_postedData['phyId'])
            && $this->_postedData['phyId'] > 0
        ) {
            $a->updatePhyParent($this->_postedData['phyId']);
        }
        if (
            is_numeric($this->_postedData['netId'])
            && $this->_postedData['netId'] > 0
        ) {
            $a->updateNetParent($this->_postedData['netId']);
        }
        $a->updateLoc($this->_postedData['locId']);
        $a->save();
    }

    public function edit( $params )
    {
        $this->setAction("edit");
        $params = $this->parseParams($params);
        $assetId = -1;
        if (is_numeric($params[0])) {
            $assetId = $params[0];
        }

        if ($_POST['assetSubmit']) {
            //echo "<pre>".print_r($_POST, true)."</pre>";
            $this->_postedData = $_POST;
            if ($assetId <= 0 ) {
                $this->_assetNew();
            } else {
                $this->_ciid = $assetId;
                $this->_assetUpdate();
            }
        }

        if ($assetId <= 0 ) {
            return $this->add($params);
        }
        $this->_ciid = $assetId;
        $this->_pullAssetInfo();
        $this->data['submitButtonText'] = "update";
        $this->data['pageTitle'] = "Edit Asset Information";
        $this->data['editAsset'] = true;
        $this->data['ciid'] = $assetId;
        //echo "<pre>".print_r($this->data, true)."</pre>";
    }

    public function add( $params )
    {
        $this->setAction("edit");
        $this->data['pageTitle'] = "Add New CI to the system";
        foreach ($params as $k=>$v) {
            //echo "$k --> $v<br/>";
            $this->data[$k] = urldecode($v);
        }
        $this->data['submitButtonText'] = "add";
    }

    public function autocomplete( $params )
    {
        $this->ac = true;
        $str = explode('=', $params);
        error_log(print_r($params, true));
        $ci = new AssetModel(AssetModel::RO);
        echo json_encode($ci->autocomplete("ciName", $str[1]));
    }
}
