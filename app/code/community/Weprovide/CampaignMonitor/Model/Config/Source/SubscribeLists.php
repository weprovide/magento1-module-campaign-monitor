<?php

/**
 * Class Weprovide_CampaignMonitor_Model_Config_Source_SubscribeLists
 *
 * @author Tim Neutkens <tim@weprovide.com>
 * @copyright Copyright (c) 2015, We/Provide http://www.weprovide.com
 */
class Weprovide_CampaignMonitor_Model_Config_Source_SubscribeLists
{
    /**
     * Create select box options array
     * @return array
     */
    public function toOptionArray()
    {
        $lists = Mage::getModel('campaignmonitor/api_clients')->getLists();

        $options = array();

        if(is_array($lists))
        {
            $options[] = array(
                'value' => '',
                'label' => Mage::Helper('campaignmonitor')->__('Select a subscribe list')
            );

            foreach($lists as $list)
            {
                if(isset($list->ListID) && isset($list->Name))
                {
                    $options[] = array(
                        'value' => $list->ListID,
                        'label' => $list->Name
                    );
                }
            }

        } else {
            $options[] = array(
                'value' => '',
                'label' => Mage::Helper('campaignmonitor')->__('Please save a valid API KEY + CLIENT ID')
            );
        }

        return $options;
    }
}