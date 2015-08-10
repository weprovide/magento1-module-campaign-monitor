<?php

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

                if(isset($eventType) && isset($emailAddress)) {
                    if($eventType == 'Deactivate') {
                        $subscriber = Mage::getModel('newsletter/subscriber')->loadByEmail($emailAddress);
                        if($subscriber->isSubscribed() && $subscriber->getId()) {
                            $state = Mage::helper('campaignmonitor')->escapeHtml($event['State']);
                            if(isset($state)) {
                                $subscriber->setCampaignMonitorState($state);
                            } else {
                                Mage::Log('State is not set', null, 'campaignmonitor.log');
                            }
                            $subscriber->unsubscribe();
                        } else {
                            Mage::Log('Could not unsubscribe: ' . $emailAddress, null, 'campaignmonitor.log');
                        }
                    }
                } else {
                    Mage::Log('Type is not set', null, 'campaignmonitor.log');
                }

            }

        }
    }
}