<?php

/**
 * Class Weprovide_CampaignMonitor_Model_Setting
 *
 * @author Lex Beelen <lex@weprovide.com>
 * @copyright Copyright (c) 2015, We/Provide http://www.weprovide.com
 */
class Weprovide_CampaignMonitor_Model_Setting
{
    const XML_ENABLED = 'campaignmonitor/configuration/enabled';
    const XML_API_KEY = 'campaignmonitor/configuration/api_key';
    const XML_CLIENT_ID = 'campaignmonitor/configuration/client_id';
    const XML_SUBSCRIBE_LIST_API_KEY = 'campaignmonitor/configuration/subscribe_list_api_key';
    const CUSTOM_FIELD_SUBSCRIBER_ID = 'MagentoSubscriberId';
    const CUSTOM_FIELD_SUBSCRIBER_CONFIRM_CODE = 'MagentoSubscriberConfirmCode';
    const IMPORT_SUBSCRIBER_LIMIT = 1000;
    const MEMORY_LIMIT = 2048;
    
    /**
     * @param int $storeId
     * @return mixed
     */
    public function isEnabled($storeId = 0)
    {
        return Mage::getStoreConfig(self::XML_ENABLED, $storeId);
    }

    /**
     * @param int $storeId
     * @return mixed
     */
    public function getApiKey($storeId = 0)
    {
        return Mage::getStoreConfig(self::XML_API_KEY, $storeId);
    }

    /**
     * @param int $storeId
     * @return mixed
     */
    public function getClientId($storeId = 0)
    {
        return Mage::getStoreConfig(self::XML_CLIENT_ID, $storeId);
    }

    /**
     * @param int $storeId
     * @return mixed
     */
    public function getSubscribeListApiKey($storeId = 0)
    {
        return Mage::getStoreConfig(self::XML_SUBSCRIBE_LIST_API_KEY, $storeId);
    }

}

