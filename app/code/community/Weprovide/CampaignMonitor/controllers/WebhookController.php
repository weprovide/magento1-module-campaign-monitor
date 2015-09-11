<?php

/**
 * Class Weprovide_CampaignMonitor_WebhookController
 *
 * @author Tim Neutkens <tim@weprovide.com>
 * @copyright Copyright (c) 2015, We/Provide http://www.weprovide.com
 */
class Weprovide_CampaignMonitor_WebhookController extends Mage_Core_Controller_Front_Action
{
    /**
     * Webhook post controller
     * @throws Exception
     * @throws Zend_Controller_Request_Exception
     */
    public function indexAction()
    {
        $request = $this->getRequest();
        if($request->isPost() && $request->getHeader('User-Agent') == 'CreateSend') {
            $usualPost = $request->getPost();

            $postData = !empty($usualPost) ? $usualPost : $request->getRawBody();

            if(!$postData) {
                Mage::Log('Post data is empty', null, 'campaignmonitor.log');
                throw new Exception('Post data is empty');
            }

            $parsedData = Mage::helper('campaignmonitor')->parseJsonWebhook($postData);
            $events = $parsedData['Events'];

            foreach($events as $event) {
                $eventType = $event['Type'];
                $emailAddress = $event['EmailAddress'];
                $state = Mage::helper('campaignmonitor')->escapeHtml($event['State']);

                if(isset($eventType) && isset($emailAddress)) {

                    if($eventType == 'Subscribe') {
                        Mage::getModel('campaignmonitor/api_webhooks')->newsletterSubscribe($emailAddress, $state);
                    }

                    if($eventType == 'Update') {
                        Mage::getModel('campaignmonitor/api_webhooks')->newsletterUpdate($emailAddress, $state);
                    }

                    if($eventType == 'Deactivate') {
                        Mage::getModel('campaignmonitor/api_webhooks')->newsletterDeactivate($emailAddress, $state);
                    }

                } else {
                    Mage::Log('Type is not set', null, 'campaignmonitor.log');
                }

            }

        }
    }
}