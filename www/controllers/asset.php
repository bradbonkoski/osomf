<?php

use \osomf\models\AssetModel;

class asset extends ControllerBase
{
    public function __construct($controller = '', $action = '') 
    { 
        parent::__construct("asset", $action);
    }

    public function view($params)
    {
        $this->setAction("view");
        $params = $this->parseParams($params);

        if (!array_key_exists(0, $params) || !is_numeric($params[0])) {
            error_log("Sending to display");
            return $this->display();
        }
        $assetId = $params[0];
        
        $a = new AssetModel(AssetModel::RO);
        $a->loadAsset($assetId);
        $this->data['ciName'] = $a->ciName;
        $this->data['ciDesc'] = $a->ciDesc;
        $this->data['ownerId'] = $a->getOwnerId();
        if ($a->getOwnerType() == AssetModel::OWNER_USER) {
            $ownerCont = 'user';
            $this->data['ownerName'] = $a->owner->uname;
        } else {
            $this->data['ownerName'] = $a->owner->groupName;
            $ownerCont = 'group';
        }
        $this->data['ownerLink'] = "/osomf/$ownerCont/view/".$a->getOwnerId();
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

    public function add( $params )
    { 
        $this->setAction("add");
        $params = $this->parseParams($params);
    }
}
