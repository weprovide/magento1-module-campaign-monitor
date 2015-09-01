<?php

/**
 * Class Weprovide_CampaignMonitor_Block_Jsinit
 *
 * @author Lex Beelen <lex@weprovide.com>
 * @copyright Copyright (c) 2015, We/Provide http://www.weprovide.com
 */
class Weprovide_CampaignMonitor_Block_Jsinit extends Mage_Adminhtml_Block_Template
{
    /**
     * Prepare Layout
     */
    protected function _prepareLayout()
    {
        $section = $this->getAction()->getRequest()->getParam('section', false);

        if ($section == 'campaignmonitor') {
            $this->getLayout()
                ->getBlock('head')
                ->addJs('mage/adminhtml/campaignmonitor/jquery/jquery-1.11.3.min.js')
                ->addJs('mage/adminhtml/campaignmonitor/jquery/jquery-no-conflict.js')
                ->addJs('mage/adminhtml/campaignmonitor/main.js');
        }
        parent::_prepareLayout();
    }

    /**
     * @return string
     */
    protected function _toHtml()
    {
        $section = $this->getAction()->getRequest()->getParam('section', false);

        if ($section == 'campaignmonitor') {
            return parent::_toHtml();
        } else {
            return '';
        }
    }
}
