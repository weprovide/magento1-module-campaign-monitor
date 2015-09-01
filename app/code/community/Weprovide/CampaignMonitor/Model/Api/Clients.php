<?php

/**
 * Class Weprovide_CampaignMonitor_Model_Api_Clients
 *
 * @author Tim Neutkens <tim@weprovide.com>
 * @copyright Copyright (c) 2015, We/Provide http://www.weprovide.com
 */
class Weprovide_CampaignMonitor_Model_Api_Clients
{

    /**
     * @param int $storeId
     * @return CS_REST_Clients
     */
    protected function _api($storeId = 0, $clientId = '')
    {
        return new CS_REST_Clients((!empty($clientId) ? $clientId : Mage::getModel('campaignmonitor/setting')->getClientId($storeId)),  array('api_key' => Mage::getModel('campaignmonitor/setting')->getApiKey($storeId)));
    }

    /**
     * @param int $storeId
     * @param string $clientId
     * @return mixed
     * @throws Exception
     */
    public function getLists($storeId = 0, $clientId = '')
    {
        $result = $this->_api($storeId, $clientId)->get_lists();

        if($result->was_successful()) {
            return $result->response;
        } else {
            throw new Exception($result->response->Message);
        }
    }

}