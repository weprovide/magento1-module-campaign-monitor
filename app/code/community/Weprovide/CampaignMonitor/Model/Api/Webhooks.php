<?php

/**
 * Class Weprovide_CampaignMonitor_Model_Api_Webhooks
 *
 * @author Tim Neutkens <tim@weprovide.com>
 * @copyright Copyright (c) 2015, We/Provide http://www.weprovide.com
 */
class Weprovide_CampaignMonitor_Model_Api_Webhooks extends Weprovide_CampaignMonitor_Model_Api_Lists
{
    /**
     * Set list id for object
     * @param $listId
     * @param int $storeId
     * @return CS_REST_Lists
     */
    protected function _listApi($listId, $storeId = 0) {
        $api = $this->_api($storeId);
        $api->set_list_id($listId);

        return $api;
    }

    /**
     * Check if result was successful else throw exception
     * @param CS_REST_Wrapper_Result $result
     * @return CS_REST_Wrapper_Result
     * @throws Exception
     */
    protected function _validateResult(\CS_REST_Wrapper_Result $result)
    {
        if( $result->was_successful() ) {
            return $result;
        } else {
            throw new Exception($result->response->Message);
        }
    }

    /**
     * Create a new webhook
     * @param $listId       |   Campaign monitor list id
     * @param $url          |   Url that Campaign monitor POST's to
     * @param int $storeId  |   Magento store ID to get the api configuration
     * @param array $types  |   Hooks types. Should be a combination of 'subscribe', 'update' and 'deactivate'
     * @return CS_REST_Wrapper_Result
     * @throws Exception    |   Exception if api request fails
     */
    public function createWebhook($listId, $url, $storeId = 0, $types = array('subscribe', 'update', 'deactivate'), $payload = 'json')
    {
        $_api = $this->_listApi($listId, $storeId);

        $webhook = array();
        $webhook['Url'] = $url;
        $webhook['Events'] = array();

        foreach ($types as $type) {
            if ($type == 'subscribe') {
                $webhook['Events'][] = CS_REST_LIST_WEBHOOK_SUBSCRIBE;
            }

            if ($type == 'update') {
                $webhook['Events'][] = CS_REST_LIST_WEBHOOK_UPDATE;
            }

            if ($type == 'deactivate') {
                $webhook['Events'][] = CS_REST_LIST_WEBHOOK_DEACTIVATE;
            }
        }

        $payload = strtolower($payload);

        if ($payload == 'xml') {
            $webhook['PayLoadFormat'] = CS_REST_WEBHOOK_FORMAT_XML;
        } else {
            $webhook['PayloadFormat'] = CS_REST_WEBHOOK_FORMAT_JSON;
        }

        $result = $_api->create_webhook($webhook);

        return $this->_validateResult($result);
    }

    /**
     * Delete an existing webhook
     * @param $listId
     * @param $webhookId
     * @param int $storeId
     * @return CS_REST_Wrapper_Result
     * @throws Exception
     */
    public function deleteWebhook($listId, $webhookId, $storeId = 0) {
        $_api = $this->_listApi($listId, $storeId);

        $result = $_api->delete_webhook($webhookId);

        return $this->_validateResult($result);
    }

    /**
     * Activate an existing webhook
     * @param $listId
     * @param $webhookId
     * @param int $storeId
     * @return CS_REST_Wrapper_Result
     * @throws Exception
     */
    public function activateWebhook($listId, $webhookId, $storeId = 0)
    {
        $_api = $this->_listApi($listId, $storeId);

        $result = $_api->activate_webhook($webhookId);

        return $this->_validateResult($result);
    }

    /**
     * Deactivate an existing webhook
     * @param $listId
     * @param $webhookId
     * @param int $storeId
     * @return CS_REST_Wrapper_Result
     * @throws Exception
     */
    public function deactivateWebhook($listId, $webhookId, $storeId = 0) {
        $_api = $this->_listApi($listId, $storeId);

        $result = $_api->activate_webhook($webhookId);

        return $this->_validateResult($result);
    }

    /**
     * Test if a webhook is configured correctly / still exists
     * @param $listId
     * @param $webhookId
     * @param int $storeId
     * @return CS_REST_Wrapper_Result
     */
    public function testWebhook($listId, $webhookId, $storeId = 0)
    {
        $_api = $this->_listApi($listId, $storeId);

        return $_api->test_webhook($webhookId);
    }

    /**
     * Get all webhooks for a specific list id
     * @param $listId
     * @param int $storeId
     * @return CS_REST_Wrapper_Result
     * @throws Exception
     */
    public function getWebhooks($listId, $storeId = 0)
    {
        $_api = $this->_listApi($listId, $storeId);

        $result = $_api->get_webhooks();

        return $this->_validateResult($result);
    }

    /**
     * Subscribe based on incoming webhook
     * @param $emailAddress
     * @param $state
     * @throws Exception
     */
    public function newsletterSubscribe($emailAddress, $state)
    {
        $subscriber = Mage::getModel('newsletter/subscriber')->loadByEmail($emailAddress);
        if($subscriber->getId()) {
            $subscriber->setSubscriberStatus(1);
            if($state == null) {
                $subscriber->setCampaignMonitorState('');
            } else {
                $subscriber->setCampaignMonitorState($state);
            }
        } else {
            Mage::getModel('newsletter/subscriber')->subscribe($emailAddress);
            $subscriber = Mage::getModel('newsletter/subscriber')->loadByEmail($emailAddress);
            if($subscriber->getId()) {
                $subscriber->setCampaignMonitorState($state);
            }
        }
        $subscriber->save();
    }

    /**
     * Update based on incoming webhook
     * @param $emailAddress
     * @param $state
     * @throws Exception
     */
    public function newsletterUpdate($emailAddress, $state)
    {
        $subscriber = Mage::getModel('newsletter/subscriber')->loadByEmail($emailAddress);
        if($subscriber->getId()) {
            $subscriber->setCampaignMonitorState($state);
        }
        $subscriber->save();
    }

    /**
     * Deactivate based on incoming webhook
     * @param $emailAddress
     * @param $state
     * @throws Exception
     */
    public function newsletterDeactivate($emailAddress, $state)
    {
        $subscriber = Mage::getModel('newsletter/subscriber')->loadByEmail($emailAddress);
        if($subscriber->isSubscribed() && $subscriber->getId()) {
            $subscriber->setCampaignMonitorState($state);
            $subscriber->unsubscribe();
        } else {
            Mage::Log('Could not unsubscribe: ' . $emailAddress, null, 'campaignmonitor.log');
        }
        $subscriber->save();
    }
}
