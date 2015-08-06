<?php

class Weprovide_CampaignMonitor_subscribersController extends Mage_Core_Controller_Front_Action
{
    public function unsubscribesuccessAction() {
        Mage::getSingleton('core/session')->addSuccess($this->__('You have been unsubscribed.'));
        Mage::app()->getResponse()->setRedirect(Mage::getBaseUrl());
    }
}