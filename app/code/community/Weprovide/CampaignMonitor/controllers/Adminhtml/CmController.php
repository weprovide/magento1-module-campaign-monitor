<?php

/**
 * Class Weprovide_CampaignMonitor_Adminhtml_CmController
 *
 * @author Lex Beelen <lex@weprovide.com>
 * @copyright Copyright (c) 2015, We/Provide http://www.weprovide.com
 */
class Weprovide_CampaignMonitor_Adminhtml_CmController extends Mage_Adminhtml_Controller_Action
{

	/**
	 * ajaxSubscribeListAction
	 */
	public function ajaxSubscribeListAction()
	{
		$jsonData = Mage::getModel('campaignmonitor/api_clients')->getLists(0, Mage::app()->getRequest()->getParam('client_id'));
		$this->getResponse()->setHeader('Content-type', 'application/json');
		$this->getResponse()->setBody(Mage::helper('core')->jsonEncode($jsonData));
		return;
	}

}
