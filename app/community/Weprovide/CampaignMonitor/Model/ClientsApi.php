<?php

/**
 * Load subscriber CampaignMonitor Library
 */
require_once(Mage::getBaseDir('lib') . '/CampaignMonitor/csrest_clients.php');

/**
 * Class Weprovide_CampaignMonitor_Model_ClientsApi
 *
 * @author Tim Neutkens <tim@weprovide.com>
 * @copyright Copyright (c) 2015, We/Provide http://www.weprovide.com
 */
class Weprovide_CampaignMonitor_Model_ClientsApi
{
    /**
     * @param int $storeId
     * @return CS_REST_Clients
     */
    protected function _api($storeId = 0)
    {
        return new CS_REST_Clients(Mage::getModel('campaignmonitor/setting')->getClientId($storeId),  array('api_key' => Mage::getModel('campaignmonitor/setting')->getApiKey($storeId)));
    }

    /**
     * @param int $storeId
     * @return bool
     */
    public function issetConfig($storeId = 0)
    {
        $model = Mage::getModel('campaignmonitor/setting');
        return $model->getApiKey($storeId) && $model->getApiKey($storeId);
    }

    /**
     * @return CS_REST_Wrapper_Result
     * @throws Exception
     */
    public function getLists($storeId = 0)
    {
        if($this->issetConfig($storeId)) {

            $result = $this->_api($storeId)->get_lists();

            if($result->was_successful()) {
                return $result->response;
            } else {
                throw new Exception($result->response->Message);
            }

        } else {
            return false;
        }
    }
}