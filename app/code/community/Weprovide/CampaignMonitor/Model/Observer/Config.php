<?php

/**
 * Class Weprovide_CampaignMonitor_Model_Observer_Config
 *
 * @author Lex Beelen <lex@weprovide.com>
 * @copyright Copyright (c) 2015, We/Provide http://www.weprovide.com
 */
class Weprovide_CampaignMonitor_Model_Observer_Config
{

    /**
     * initDefaultCustomFields
     */
    public function initDefaultCustomFields(Varien_Event_Observer $observer)
    {
        $storeId = Mage::getModel('core/store')->load($observer->getEvent()->getStore())->getId();
        $apiLists = Mage::getModel('campaignmonitor/api_lists');

        try {
            $apiLists->createCustomField(Weprovide_CampaignMonitor_Model_Setting::CUSTOM_FIELD_SUBSCRIBER_ID, CS_REST_CUSTOM_FIELD_TYPE_TEXT, $storeId);
        } catch (Exception $e) {
            Mage::log($e->getMessage(), NULL, 'campaignmonitor.log');
        }

        try {
            $apiLists->createCustomField(Weprovide_CampaignMonitor_Model_Setting::CUSTOM_FIELD_SUBSCRIBER_CONFIRM_CODE, CS_REST_CUSTOM_FIELD_TYPE_TEXT, $storeId);
        } catch (Exception $e) {
            Mage::log($e->getMessage(), NULL, 'campaignmonitor.log');
        }

    }

    /**
     * Set unsubscribe url on section save
     * @param Varien_Event_Observer $observer
     */
    public function setUnsubscribeUrl(Varien_Event_Observer $observer)
    {
        $storeId = Mage::getModel('core/store')->load($observer->getEvent()->getStore())->getId();
        $listId = Mage::getModel('campaignmonitor/setting')->getSubscribeListApiKey($storeId);
        $apiLists = Mage::getModel('campaignmonitor/api_lists');
        if ($listId) {
            try {
                $apiLists->setUnsubscribePage($listId, $storeId);
            } catch (Exception $e) {
                Mage::log($e->getMessage(), NULL, 'campaignmonitor.log');
            }
        } else {
            Mage::Log('No list ID', null, 'campaignmonitor.log');
        }
    }

    /**
     * Create webhook on section save
     * @param Varien_Event_Observer $observer
     */
    public function createWebhook(Varien_Event_Observer $observer)
    {
        $storeId = Mage::getModel('core/store')->load($observer->getEvent()->getStore())->getId();
        $listId = Mage::getModel('campaignmonitor/setting')->getSubscribeListApiKey($storeId);
        $apiWebhooks = Mage::getModel('campaignmonitor/api_webhooks');
        if ($listId) {
            try {
                $url = Mage::getModel('campaignmonitor/setting')->getWebhookBaseUrl($storeId);
                if(isset($url) && $url != '') {
                    $url .= '/campaignmonitor/webhook';
                } else {
                    $url = Mage::getUrl('campaignmonitor/webhook');
                }

                $types = array('subscribe', 'update', 'deactivate');

                if(!Mage::helper('campaignmonitor')->webhookExists($url, $listId, $storeId)) {
                    $apiWebhooks->createWebhook($listId, $url, $storeId, $types, 'json');
                }
            } catch (Exception $e) {
                Mage::log($e->getMessage(), NULL, 'campaignmonitor.log');
            }
        }
    }
}