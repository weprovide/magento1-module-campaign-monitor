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
     * controllerFrontInitBefore
     */
    public function controllerFrontInitBefore()
    {
        self::init();
    }

    /**
     * init
     */
    static function init()
    {
        $campaignmonitorLibrary = Mage::getBaseDir('lib') . '/CampaignMonitor/';
        require_once($campaignmonitorLibrary . 'csrest_subscribers.php');
        require_once($campaignmonitorLibrary . 'csrest_clients.php');
        require_once($campaignmonitorLibrary . 'csrest_lists.php');
    }

}