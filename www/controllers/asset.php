<?php

use \osomf\models\AssetModel;

class asset extends ControllerBase
{
    private $_ciid;
    

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
        $this->data['ownerId'] = $a->getOwnerId();
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
        $this->data['serial'] = $a->ciSerialNum;
        $this->data['acquired'] = $a->acquiredDate;
        $this->data['retired'] = $a->isRetired;
        $this->data['disposed'] = $a->disposalDate;

        // relations
        if ($a->netParent instanceof AssetModel) {
            $this->data['netParent'] = 1;
            $this->data['netParentName'] = $a->netParent->ciName;
            $this->data['netParentLink'] =
                "/osomf/asset/view/".$a->netParent->getAssetId();
        } else {
            $this->data['netParent'] = 0;
        }

        if ($a->phyParent instanceof AssetModel) {
            $this->data['phyParent'] = 1;
            $this->data['phyParentName'] = $a->phyParent->ciName;
            $this->data['phyParentLink'] =
                "/osomf/asset/view/".$a->phyParent->getAssetId();
        } else {
            $this->data['phyParent'] = 0;
        }

        // project info
        if ($a->project instanceof \osomf\models\ProjectModel) {
            $this->data['proj'] = 1;
            $this->data['projName'] = $a->project->projName;
            $this->data['projLink'] =
                "/osomf/project/view/".$a->project->getProjId();
        } else {
            error_log("No Project Defined?");
            $this->data['proj'] = 0;
        }

        //location info
        if ($a->loc instanceof \osomf\models\LocationModel) {
            $this->data['loc'] = 1;
            $this->data['locName'] = $a->loc->locName;
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

    public function edit( $params )
    { 
        $this->setAction("edit");
        $params = $this->parseParams($params);
        $assetId = -1;
        if (is_numeric($params[0])) {
            $assetId = $params[0];
        } 

        if ($assetId <= 0 ) {
            return $this->add($params);
        }
        $this->_ciid = $assetId;
        $this->_pullAssetInfo();
    }

    public function add( $params )
    {
        $this->setAction("edit");
        $this->data['pageTitle'] = "Add New CI to the system";
        foreach ($params as $k=>$v) {
            //echo "$k --> $v<br/>";
            $this->data[$k] = urldecode($v);
        }
    }

    public function autocomplete( $params )
    {
        $this->ac = true;
        $str = explode('=', $params);
        $ci = new AssetModel(AssetModel::RO);
        echo json_encode($ci->autocomplete("ciName", $str[1]));
    }
}
