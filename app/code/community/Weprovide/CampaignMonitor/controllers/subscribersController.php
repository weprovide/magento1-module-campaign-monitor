<?php

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