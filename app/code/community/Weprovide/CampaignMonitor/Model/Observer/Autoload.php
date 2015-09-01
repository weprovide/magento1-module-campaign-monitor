<?php

/**
 * Class Weprovide_CampaignMonitor_Model_Observer_Autoload
 *
 * @author Lex Beelen <lex@weprovide.com>
 * @copyright Copyright (c) 2015, We/Provide http://www.weprovide.com
 */
class Weprovide_CampaignMonitor_Model_Observer_Autoload
{

    /**
     * autoLoad
     */
    public function autoLoad()
    {
        self::init();
    }

    /**
     * init
     */
    static function init()
    {
        $campaignMonitorLibrary = Mage::getBaseDir('lib') . '/CampaignMonitor/';
        require_once($campaignMonitorLibrary . 'csrest_general.php');
        require_once($campaignMonitorLibrary . 'csrest_subscribers.php');
        require_once($campaignMonitorLibrary . 'csrest_clients.php');
        require_once($campaignMonitorLibrary . 'csrest_lists.php');
    }

}