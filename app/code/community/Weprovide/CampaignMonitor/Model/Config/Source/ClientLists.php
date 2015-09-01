<?php

/**
 * Class Weprovide_CampaignMonitor_Model_Config_Source_CustomerLists
 *
 * @author Lex Beelen <lex@weprovide.com>
 * @copyright Copyright (c) 2015, We/Provide http://www.weprovide.com
 */
class Weprovide_CampaignMonitor_Model_Config_Source_ClientLists
{
    /**
     * Create select box options array
     * @return array
     */
    public function toOptionArray()
    {

        $options = array();

        if(Mage::getModel('campaignmonitor/setting')->getApiKey()) {

            $lists = Mage::getModel('campaignmonitor/api_general')->getClients();

            if (is_array($lists)) {
                $options[] = array(
                    'value' => '',
                    'label' => Mage::Helper('campaignmonitor')->__('Select a client')
                );

                foreach ($lists as $list) {
                    if (isset($list->ClientID) && isset($list->Name)) {
                        $options[] = array(
                            'value' => $list->ClientID,
                            'label' => $list->Name
                        );
                    }
                }

            } else {
                $options[] = array(
                    'value' => '',
                    'label' => Mage::Helper('campaignmonitor')->__('Please save a valid API KEY')
                );
            }
        } else{
            $options[] = array(
                'value' => '',
                'label' => Mage::Helper('campaignmonitor')->__('Please save a valid API KEY')
            );
        }

        return $options;
    }
}