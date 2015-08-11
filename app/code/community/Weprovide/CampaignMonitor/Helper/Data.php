<?php

/**
 * Class Weprovide_CampaignMonitor_Helper_Data
 *
 * @author Lex Beelen <lex@weprovide.com>
 * @copyright Copyright (c) 2015, We/Provide http://www.weprovide.com
 */
class Weprovide_CampaignMonitor_Helper_Data extends Mage_Core_Helper_Abstract
{
    /**
     * @return array
     */
    public function getStoreIds()
    {
        $storeIds = array();

        foreach (Mage::app()->getStores() as $storeId => $val) {
            $storeIds[] = $storeId;
        }
        return $storeIds;
    }

    /**
     * @param bool $inMegabytes
     * @return int|string
     */
    public function getMemoryLimit($inMegabytes = true)
    {
        $memoryLimit = trim(ini_get('memory_limit'));

        if ($memoryLimit == '') {
            return 0;
        }

        $lastMemoryLimitLetter = strtolower(substr($memoryLimit, -1));
        switch($lastMemoryLimitLetter) {
            case 'g':
                $memoryLimit *= 1024;
            case 'm':
                $memoryLimit *= 1024;
            case 'k':
                $memoryLimit *= 1024;
        }

        if ($inMegabytes) {
            $memoryLimit /= 1024 * 1024;
        }

        return $memoryLimit;
    }

    /**
     * @param int $maxSize
     * @return bool|float
     */
    public function setMemoryLimit($maxSize = 512)
    {
        $minSize = 32;
        $currentMemoryLimit = $this->getMemoryLimit();

        if ($maxSize < $minSize || (int)$currentMemoryLimit >= $maxSize) {
            return false;
        }

        for ($i=$minSize; $i<=$maxSize; $i*=2) {

            if (@ini_set('memory_limit',"{$i}M") === false) {
                if ($i == $minSize) {
                    return false;
                } else {
                    return $i/2;
                }
            }
        }

        return true;
    }

    /**
     * Parse webhook json
     * @param $jsonData
     * @return mixed
     * @throws Exception
     */
    public function parseJsonWebhook($jsonData)
    {
        $parsedData = Mage::helper('core')->jsonDecode($jsonData);
        if(isset($parsedData['ListID']) && isset($parsedData['Events'])) {
            return $parsedData;
        } else {
            throw new Exception('Webhook data not valid: ' . $jsonData);
        }
    }

    /**
     * Check if webhook exists based on url
     * @param $url
     * @param $listId
     * @param int $storeId
     * @return bool
     * @throws Exception
     */
    public function webhookExists($url, $listId, $storeId = 0)
    {
        $webhooks = Mage::getModel('campaignmonitor/api_webhooks')->getWebhooks($listId, $storeId)->response;
        if (isset($webhooks)) {
            foreach ($webhooks as $webhook) {
                if (isset($webhook->Url)) {
                    if ($webhook->Url == $url) {
                        return true;
                    }
                }
            }
        } else {
            throw new Exception('Error loading webhooks');
        }

        return false;
    }
}