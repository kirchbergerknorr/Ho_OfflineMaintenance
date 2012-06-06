<?php
/*
 * Magento H&O Offline Maintenance Page
 *
 * @category   H&O
 * @package    Ho_OfflineMaintenance
 * @copyright  Copyright (c) 2012 H&O (http://www.h-o.nl)
 * @author     Paul Hachmang (paul@h-o.nl
 * @licence    MIT
 */
class Ho_OfflineMaintenance_Controller_Router_Standard extends Mage_Core_Controller_Varien_Router_Standard
{

    public function match(Zend_Controller_Request_Http $request)
    {
		$storeenabled = Mage::getStoreConfig('dev/offlinemaintenance/enabled', $request->getStoreCodeFromPath());
		if ($storeenabled)
		{
			Mage::getSingleton('core/session', array('name' => 'adminhtml'));
			if (!Mage::getSingleton('admin/session')->isLoggedIn() && !$this->isExcluded())
			{
                if (Mage::getStoreConfig('dev/offlinemaintenance/custom_message') == 0)
                {
                    include_once Mage::getBaseDir() . '/errors/503.php';
                    exit;
                } else {
                    Mage::getSingleton('core/session', array('name' => 'front'));

                    $front = $this->getFront();
                    $response = $front->getResponse();
                    $response->setHeader('HTTP/1.1','503 Service Temporarily Unavailable');
                    $response->setHeader('Status','503 Service Temporarily Unavailable');
                    $response->setHeader('Retry-After','5000');

                    $response->setBody(html_entity_decode( Mage::getStoreConfig('dev/offlinemaintenance/message', $request->getStoreCodeFromPath()), ENT_QUOTES, "utf-8" )); 			$response->sendHeaders();
                    $response->outputBody();
                }
			}
			else
			{
				$showreminder = Mage::getStoreConfig('dev/offlinemaintenance/showreminder', $request->getStoreCodeFromPath());
				if ($showreminder)
				{
					$front = $this->getFront();
					$response = $front->getResponse()->appendBody('<div style="height:12px; background:red; color: white; position:relative; width:100%;padding:3px; z-index:100000;text-trasform:capitalize;">Offline</div>');
				}
			}
		}
		return parent::match($request);

    }

    /**
     * Checks if the current request is allowed.
     * @return bool
     */
    public function isExcluded()
    {
        /** @var $coreHelper Mage_Core_Helper_Data */
        $coreHelper = Mage::helper('core/data');
        if (Mage::getStoreConfig(Mage_Core_Helper_Data::XML_PATH_DEV_ALLOW_IPS) && $coreHelper->isDevAllowed())
        {
            return true;
        }

        /** @var $excludeHelper Ho_OfflineMaintenance_Helper_Exclude */
        $excludeHelper = Mage::helper('offlinemaintenance/exclude');
        $excludePaths = $excludeHelper->getConfigValue();

        $currentPath = Mage::app()->getRequest()->getPathInfo();
        foreach($excludePaths as $excludePath)
        {
            if (strpos($currentPath, $excludePath['url_path']) === 0)
            {
                return true;
            }
        }

        return false;
    }



}