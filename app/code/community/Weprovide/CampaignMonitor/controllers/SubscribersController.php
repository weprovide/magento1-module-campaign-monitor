<?php

/**
 * Class Weprovide_CampaignMonitor_SubscribersController
 *
 * @author Tim Neutkens <tim@weprovide.com>
 * @copyright Copyright (c) 2015, We/Provide http://www.weprovide.com
 */
class Weprovide_CampaignMonitor_SubscribersController extends Mage_Core_Controller_Front_Action
{
    /**
     * Set unsubscribe message
     */
    public function unsubscribesuccessAction() {
        Mage::getSingleton('core/session')->addSuccess($this->__('You have been unsubscribed.'));
        Mage::app()->getResponse()->setRedirect(Mage::getBaseUrl());
    }
}