<?php

/**
 * Class Weprovide_CampaignMonitor_Model_Api
 *
 * @author Lex Beelen <lex@weprovide.com>
 * @copyright Copyright (c) 2015, We/Provide http://www.weprovide.com
 */
class Weprovide_CampaignMonitor_Model_Api_Lists
{
    /**
     * @param int $storeId
     * @return CS_REST_Lists
     */
    protected function _api($storeId = 0){
        return new CS_REST_Lists(Mage::getModel('campaignmonitor/setting')->getSubscribeListApiKey($storeId),  array('api_key' => Mage::getModel('campaignmonitor/setting')->getApiKey($storeId)));
    }

    /**
     * @param $fieldName
     * @param $dataType
     * @param int $storeId
     * @param array $options
     * @return bool
     * @throws Exception
     */
    public function createCustomField($fieldName, $dataType, $storeId = 0, $options = array())
    {
        if(!$fieldName || !$dataType || !is_array($options)) return false;
        $data = array();
        $data['FieldName'] = $fieldName;
        $data['DataType'] = $dataType;
        $data['Options'] = $options;

        $result = $this->_api($storeId)->create_custom_field($data);
        if(!$result->was_successful()) {
            throw new Exception($result->response->Message);
        }
    }

}