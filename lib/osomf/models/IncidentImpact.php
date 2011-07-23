<?php

/**
 * Incident Impact Model
 *
 *
 * @category    Library
 * @package     Model
 * @author      Brad Bonkoski <brad.bonkoski@yahoo.com>
 * @copyright   Copyright (c) 2011 Fitzers House of Code
 *
 */
 
namespace osomf\models;

use osomf\DB;
use \osomf\models\ProjectModel;
use \osomf\models\AssetModel;

/**
 * Incident Impact Model
 *
 *
 * @category    Library
 * @package     Model
 * @author      Brad Bonkoski <brad.bonkoski@yahoo.com>
 * @copyright   Copyright (c) 2011 Fitzers House of Code
 *
 */

class IncidentImpact extends DB
{

    const IMPACT_CI = 'asset';
    const IMPACT_PROJ = 'project';

    public $validImpacts = array(
        self::IMPACT_CI,
        self::IMPACT_PROJ,
    );

    private $_incidentId;
    private $_impactId;
    private $_impactType;
    private $_impactVal;
    private $_impactDesc;
    private $_impactSeverity;
    public $impacted; //object ref based on type/val

    private $_history;

    public function __construct($conn = self::RO)
    {
        if (!in_array($conn, $this->_validConn)) {
            throw new \Exception("Invalid Connection");
        }
        parent::__construct("omf_incident", $conn);

        $this->_incidentId = -1;
        $this->_impactId = -1;
        $this->_impactType = '';
        $this->_impactVal = -1;
        $this->_impactDesc = '';
        $this->_impactSeverity = -1;
        $this->impacted = null;

        $this->_history = array();
    }

    public function getImpactId()
    {
        return $this->_impactId;
    }
    
    public function getIncidentId()
    {
        return $this->_incidentId;
    }

    public function setIncidentId($val)
    {
        // new impact, hist handled on save
        // only allow for incident id to be set
        // for a new impact
        if ($this->_impactId <= 0 ) {
            $this->_incidentId = $val;
            return;
        }
    }

    public function getImpactType()
    {
        return $this->_impactType;
    }

    public function setImpactType($val)
    {
        if ($this->_impactId <= 0 ) {
            if (in_array($val, $this->validImpacts)) {
                $this->_impactType = $val;
            }
        }
    }

    public function getImpactValId()
    {
        return $this->_impactVal;
    }

    public function setImpactVal($val)
    {
        if ($this->_impactId <= 0 ) {
            $this->_impactVal = $val;
        }
    }

    public function getImpactDesc()
    {
        return $this->_impactDesc;
    }

    public function setImpactDesc($val)
    {
        if ($this->_impactId <= 0 ) {
            $this->_impactDesc = $val;
        } else {
            if (
                strlen($this->_impactDesc) > 0
                && $this->_impactDesc != $val
            ) {
                $this->_history[] = array(
                    'col' => "Impact Description",
                    'orig' => $this->_impactDesc,
                    'new' => $val
                );
                $this->_impactDesc = $val;
            }
        }
    }

    public function getImpactSeverity()
    {
        return $this->_impactSeverity;
    }

    public function setImpactSeverity($val)
    {
        if ($this->_impactId <= 0 ) {
            $this->_impactSeverity = $val;
        } else {
            if (
                is_numeric($val)
                && $this->_impactSeverity != $val
            ) {
                $this->_history[] = array(
                    'col' => 'Impact Severity',
                    'orig' => $this->_impactSeverity,
                    'new' => $val
                );
                $this->_impactSeverity = $val;
            }
        }
    }

    public function mapImpactedObject()
    {
        if (!in_array($this->_impactType, $this->validImpacts)) {
            throw new \Exception("Invalid Impact Type");
        }

        if (
            !is_numeric($this->_impactVal)
            || $this->_impactVal <= 0
        ) {
            throw new \Exception("Impacted Value is not set");
        }

        switch ($this->_impactType) {
            case self::IMPACT_CI:
                if (!$this->impacted instanceof AssetModel) {
                    $this->impacted = new AssetModel();
                }
                if ( $this->impacted->validateKey($this->_impactVal)) {
                        $this->impacted->loadAsset($this->_impactVal);
                } else {
                   throw new \Exception(
                       "Unable to Load Impacted Asset, not found"
                   );
                }
                break;

            case self::IMPACT_PROJ:
                if (!$this->impacted instanceof ProjectModel) {
                    $this->impacted = new ProjectModel();
                }
                if ($this->impacted->validateKey($this->_impactVal)) {
                    $this->impacted->fetchProjInfo($this->_impactVal);
                } else {
                    throw new \Exception(
                        "Unable to Load Impacted Project, not found"
                    );
                }
                break;
        }
    }

    public function getImpactedName()
    {
        if ($this->impacted instanceof ProjectModel) {
            return $this->impacted->getProjName();
        } else if ($this->impacted instanceof AssetModel) {
            return $this->impacted->getAssetName();
        } else {
            throw new \Exception("Impacted Not fully Set");
        }
    }


    public function loadImpacted($impactId)
    {
        if (($impactId <= 0 ) || !is_numeric($impactId)) {
            throw new \Exception(
                "Invalid Impact Id - ".__FILE__." : ".__LINE__
            );
        }

        $sql = "select * from impacted where impactId = ?";
        $stmt = $this->_db->prepare($sql);
        $stmt->execute(array($impactId));
        $row = $stmt->fetch();

        $this->_impactId = $impactId;
        $this->_impactDesc = $row['impactDesc'];
        $this->_incidentId = $row['incidentId'];
        $this->_impactType = $row['impactType'];
        $this->_impactVal = $row['impactValue'];
        $this->_impactSeverity = $row['impactSeverity'];
        $this->mapImpactedObject();
    }

    public function save()
    {
        if ($this->_impactId <= 0 ) {
            //new impact
            $sql = "insert into impacted set incidentId = ?,
                impactType=?, impactValue=?, impactDesc=?,
                impactSeverity=?";
            $stmt = $this->_db->prepare($sql);
            if (!$stmt->execute(
                array(
                    $this->_incidentId,
                    $this->_impactType,
                    $this->_impactVal,
                    $this->_impactDesc,
                    $this->_impactSeverity
                )
            )) {
                print_r($stmt->errorInfo());
            }
            $this->_impactId = $this->_db->lastInsertId();
            return array('col' => 'New Impact', 'orig' => '', 'new'=>'added');

        } else {
            // impact update
            $sql = "update impacted set impactDesc=?, impactSeverity=?
                where impactId = ?";
            $stmt = $this->_db->prepare($sql);
            $stmt->execute(
                array(
                     $this->_impactDesc,
                     $this->_impactSeverity,
                     $this->_impactId
                )
            );
            return $this->_history;
        }
    }

    public function remove()
    {
        if ($this->_impactId <= 0 ) {
            throw new \Exception(
                "Impact needed to be loaded before it can be removed"
            );
        }

        $sql = "delete from impacted where impactId = ?";
        $stmt = $this->_db->prepare($sql);
        if (!$stmt->execute(array($this->_impactId))) {
            $err = print_r($stmt->errorInfo(), true);
            error_log($err);
            throw new \Exception("Unable to remove impacted");
        }
    }

}