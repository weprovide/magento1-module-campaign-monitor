<?php

/**
 * Class Weprovide_CampaignMonitor_Model_Api_General
 *
 * @author Lex Beelen <lex@weprovide.com>
 * @copyright Copyright (c) 2015, We/Provide http://www.weprovide.com
 */
class Weprovide_CampaignMonitor_Model_Api_General
{

    /**
     * @param string $apiKey
     * @param int $storeId
     * @return CS_REST_General
     */
    protected function _api($apiKey = '', $storeId = 0){
        return new CS_REST_General(array('api_key' => (!empty($apiKey) ? $apiKey : Mage::getModel('campaignmonitor/setting')->getApiKey($storeId))));
    }

    /**
     * @return mixed
     * @throws Exception
     */
    public function getClients()
    {
        $result = $this->_api()->get_clients();
        if($result->was_successful()) {
            return $result->response;
        } else {
            throw new Exception($result->response->Message);
        }
    }

    /**
     * @param string $apiKey
     * @return bool
     */
    public function checkKey($apiKey = '')
    {
        $result = $this->_api($apiKey)->get_clients();
        if($result->was_successful()) {
            return true;
        }
        return false;
    }

}