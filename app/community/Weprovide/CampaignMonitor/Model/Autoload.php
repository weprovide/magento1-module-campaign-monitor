<?php

/**
 * Class Weprovide_CampaignMonitor_Model_Autoload
 *
 * @author Lex Beelen <lex@weprovide.com>
 * @copyright Copyright (c) 2015, We/Provide http://www.weprovide.com
 */
class Weprovide_CampaignMonitor_Model_Autoload
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
        require_once(Mage::getBaseDir('lib') . '/CampaignMonitor/csrest_subscribers.php');
    }

}