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

}