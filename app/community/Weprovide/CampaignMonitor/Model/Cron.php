<?php

/**
 * Class Weprovide_CampaignMonitor_Model_Cron
 */
class Weprovide_CampaignMonitor_Model_Cron
{

    /**
     * @param int $storeId
     * @return Mage_Newsletter_Model_Resource_Subscriber_Collection
     */
    public function getCollection($storeId = 0)
    {
        $customer = Mage::getModel('customer/customer');
        $firstname  = $customer->getAttribute('firstname');
        $lastname   = $customer->getAttribute('lastname');

        $collection = Mage::getModel('newsletter/subscriber')->getCollection();
        $collection->getSelect()
            ->limit(Weprovide_CampaignMonitor_Model_Setting::IMPORT_SUBSCRIBER_LIMIT)
            ->joinLeft(
                array('customer_lastname_table' => $lastname->getBackend()->getTable()), 'customer_lastname_table.entity_id=main_table.customer_id AND customer_lastname_table.attribute_id = ' . (int) $lastname->getAttributeId(),
                array('customer_lastname' => 'value')
            )
            ->joinLeft(
                array('customer_firstname_table' => $firstname->getBackend()->getTable()), 'customer_firstname_table.entity_id=main_table.customer_id AND customer_firstname_table.attribute_id = ' . (int) $firstname->getAttributeId(),
                array('customer_firstname' => 'value')
            )
            ->where('main_table.store_id = ?', $storeId)
            ->where('main_table.subscriber_status = ?', 1)
            ->where('main_table.campaign_monitor_imported = ?', 0);
        return $collection;
    }

    /**
     * Import
     */
    public function import()
    {
        Mage::helper('campaignmonitor')->setMemoryLimit(Weprovide_CampaignMonitor_Model_Setting::MEMORY_LIMIT);

        $subscribeListApiKeys = array();

        foreach(Mage::helper('campaignmonitor')->getStoreIds() as $storeId) {

            if (Mage::getModel('campaignmonitor/setting')->isEnabled($storeId)) {

                $subscribeListApiKey = Mage::getModel('campaignmonitor/setting')->getSubscribeListApiKey($storeId);

                if(!empty($subscribeListApiKey) && !in_array($subscribeListApiKey, $subscribeListApiKeys)) {

                    try {
                        Mage::getModel('campaignmonitor/api_subscribers')->import($this->getCollection($storeId), $storeId, false);
                    } catch (Exception $e) {
                        Mage::log($e->getMessage(), NULL, 'campaignmonitor.log');
                    }

                    $subscribeListApiKeys[] = $subscribeListApiKey;
                }
            }
        }
	}

}