<?php

/**
 * Class Weprovide_CampaignMonitor_Model_Observer_Subscribe
 *
 * @author Lex Beelen <lex@weprovide.com>
 * @copyright Copyright (c) 2015, We/Provide http://www.weprovide.com
 */
class Weprovide_CampaignMonitor_Model_Observer_Subscribe
{
    /**
     * @param Varien_Event_Observer $observer
     */
    public function save(Varien_Event_Observer $observer)
    {
        $event = $observer->getEvent();
        $subscriber = $event->getDataObject();

        if(Mage::getModel('campaignmonitor/setting')->isEnabled($subscriber->getStoreId()))
        {
            switch ($subscriber->getSubscriberStatus()) {
                case 1:
                    try {
                        Mage::getModel('campaignmonitor/api_subscribers')->subscribe($subscriber->getSubscriberEmail(), $subscriber->getSubscriberId(), $subscriber->getSubscriberConfirmCode(), $subscriber->getCustomerId(), $subscriber->getStoreId());
                    } catch (Exception $e) {
                        Mage::log($e->getMessage(), NULL, 'campaignmonitor.log');
                    }
                    break;
                case 3:
                    try {
                        Mage::getModel('campaignmonitor/api_subscribers')->unsubscribe($subscriber->getSubscriberEmail(), $subscriber->getStoreId());
                    } catch (Exception $e) {
                        Mage::log($e->getMessage(), NULL, 'campaignmonitor.log');
                    }
                    break;
            }
        }
    }

    /**
     * @param Varien_Event_Observer $observer
     */
    public function delete(Varien_Event_Observer $observer)
    {
        $event = $observer->getEvent();
        $subscriber = $event->getDataObject();

        if(Mage::getModel('campaignmonitor/setting')->isEnabled($subscriber->getStoreId()))
        {
            try {
                Mage::getModel('campaignmonitor/api_subscribers')->delete($subscriber->getSubscriberEmail(), $subscriber->getStoreId());
            } catch (Exception $e) {
                Mage::log($e->getMessage(), NULL, 'campaignmonitor.log');
            }
        }
    }

}