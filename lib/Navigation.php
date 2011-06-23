<?php
/**
 * User: bradb
 * Date: 6/18/11
 * Time: 2:51 PM
 * Copyright: (c) 2011 FHC
 */
 
class Navigation
{

    protected $_menu = array(
        'home' => array(
            'label' => 'Home',
            'controller' => 'home',
            'action' => 'view',
        ),
        'incident' => array(
            'label' => 'Incident',
            'controller' => 'incident',
            'action' => 'view',
            'submenu' => array(
                'add_incident' => array(
                    'label' => 'Add Incident',
                    'controller' => 'incident',
                    'action' => 'add',
                )
            ),
        ),
        'asset' => array(
            'label' => 'Assets',
            'controller' => 'asset',
            'action' => 'view',
        ),
        'search' => array(
            'label' => 'Search',
            'controller' => 'incident',
            'action' => 'search',
            'submenu' => array(
                'searchIncident' => array(
                    'label' => 'Incident Search',
                ),
                'searchAssets' => array(
                    'label' => 'Asset Search',
                ),
                'searchGroups' => array(
                    'label' => 'User Groups',
                )
            )
        ),
    );

    public function getMenu()
    {
        return $this->_menu;
    }
}
