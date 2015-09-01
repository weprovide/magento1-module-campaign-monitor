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
     * @param Varien_Event_Observer $observer
     */
    public function checkApiData(Varien_Event_Observer $observer)
    {
        if($observer->getEvent()->getObject()->getSection() && $observer->getEvent()->getObject()->getSection() == 'campaignmonitor') {
            $groups = $observer->getEvent()->getObject()->getGroups();
            $apiKey = $groups['configuration']['fields']['api_key']['value'];

            if(!Mage::getModel('campaignmonitor/api_general')->checkKey($apiKey)){
                $groups['configuration']['fields']['api_key']['value'] = '';
                $observer->getEvent()->getObject()->setGroups($groups);
                Mage::getSingleton('core/session')->addError('Wrong API Key :(');
            }

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