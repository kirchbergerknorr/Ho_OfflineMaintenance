<?php
/**
 * H&O Module Ho_OfflineMaintenance
 * Fork of the ArsOnIt_OfflineMaintenance module: http://www.magentocommerce.com/magento-connect/maintenance-page-artson-it.html
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the H&O Commercial License
 * that is bundled with this package in the file LICENSE_HO.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.h-o.nl/license
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to info@h-o.com so we can send you a copy immediately.
 *
 * @category    Ho
 * @package     Ho_OfflineMaintenance
 * @copyright   Copyright (c) 2012 H&O (http://www.h-o.nl/)
 * @license     H&O Commercial License (http://www.h-o.nl/license)
 */
/**
 * @category   Ho
 * @package    Ho_OfflineMaintenance
 * @author     H&O Developers <info@h-o.nl>
 */
?>
<?php
class Ho_OfflineMaintenance_Controller_Router_Standard extends Mage_Core_Controller_Varien_Router_Standard
{

    /**
     * Check if the request is matched.
     *
     * @param Zend_Controller_Request_Http $request
     * @return bool
     */
    public function match(Zend_Controller_Request_Http $request)
    {
        if (!$this->isAllowedToViewPage($request))
        {
            Mage::getStoreConfigFlag('dev/offlinemaintenance/custom_message', $request->getStoreCodeFromPath())
                ? $this->_sendCustomMessage($request)
                : $this->_send503();

        } elseif (Mage::getStoreConfig('dev/offlinemaintenance/showreminder', $request->getStoreCodeFromPath()))
        {
            /** @var $front Mage_Core_Controller_Varien_Front */
            $front = $this->getFront();

            $front->getResponse()->appendBody('<div style="height:12px; background:red; color: white; position:relative;
                width:100%;padding:3px; z-index:100000;text-trasform:capitalize;">Offline</div>');
        }

		return parent::match($request);
    }


    /**
     * Checks if the current request is allowed.
     *
     * @param Zend_Controller_Request_Http $request
     * @return bool
     */
    public function isAllowedToViewPage(Zend_Controller_Request_Http $request)
    {
        // Is offline maintenance enabled?
        if (Mage::getStoreConfigFlag('dev/offlinemaintenance/enabled', $request->getStoreCodeFromPath()) === false)
        {
            Mage::log(Mage::helper('ho_offlinemaintenance')->__('USER ALLOWED: Offline Maintemance is disabled'));
            return true;
        } else {
            Mage::log(Mage::helper('ho_offlinemaintenance')->__('Offline Maintemance is enabled.'));
        }


        // Is developermode is enabled?
        if (Mage::getIsDeveloperMode())
        {
            Mage::log(Mage::helper('ho_offlinemaintenance')->__('USER ALLOWED: Developermode is enabled'));
            return true;
        } else {
            Mage::log(Mage::helper('ho_offlinemaintenance')->__('Developermode disabled.'));
        }


        // Is the ip allowed by the client restriction?
        /** @var $coreHelper Mage_Core_Helper_Data */
        $coreHelper = Mage::helper('core/data');
        if (Mage::getStoreConfig(Mage_Core_Helper_Data::XML_PATH_DEV_ALLOW_IPS, $request->getStoreCodeFromPath())
            && $coreHelper->isDevAllowed()
        )
        {
            Mage::log(Mage::helper('ho_offlinemaintenance')->__('USER ALLOWED: Developer is in the IP list.'));
            return true;
        } else {
            Mage::log(Mage::helper('ho_offlinemaintenance')->__('Developer isn\'t in the IP list.'));
        }


        // Is the path always allowed?
        /** @var $excludeHelper Ho_OfflineMaintenance_Helper_Arrayfield_Exclude */
        $excludeHelper = Mage::helper('ho_offlinemaintenance/arrayfield_exclude');
        $excludePaths = $excludeHelper->getConfigValue();
        $currentPath = Mage::app()->getRequest()->getPathInfo();
        $excluded = false;
        foreach($excludePaths as $excludePath)
        {
            if (strpos($currentPath, $excludePath['url_path']) === 0)
            {
                $excluded = true;
            }
        }
        if ($excluded)
        {
            Mage::log(Mage::helper('ho_offlinemaintenance')->__('USER ALLOWED: URI is in the exclude list.'));
            return true;
        } else {
            Mage::log(Mage::helper('ho_offlinemaintenance')->__('URI is not in the exclude list.'));
        }


        // Is user logged into the admin panel?
        Mage::getSingleton('core/session', array('name' => 'adminhtml'));
        /** @var $adminSession Mage_Admin_Model_Session */
        $adminSession = Mage::getSingleton('admin/session');
        if($adminSession->isLoggedIn())
        {
            Mage::log(Mage::helper('ho_offlinemaintenance')->__('USER ALLOWED: User is logged in to the admin panel.'));
            return true;
        } else {
            Mage::log(Mage::helper('ho_offlinemaintenance')->__('User is not logged in to the admin panel.'));
        }


        Mage::log(Mage::helper('ho_offlinemaintenance')->__('USER DISALLOWED: Didn\'t match conditions'));
        return false;
    }


    /**
     * Send a 503 header.
     */
    protected function _send503()
    {
        include_once Mage::getBaseDir().'/errors/503.php';
        exit;
    }


    /**
     * Send a custom message, configured in the admin panel.
     *
     * @param Zend_Controller_Request_Http $request
     */
    protected function _sendCustomMessage(Zend_Controller_Request_Http $request)
    {
        Mage::getSingleton('core/session', array('name' => 'front'));

        /** @var $front Mage_Core_Controller_Varien_Front */
        $front = $this->getFront();

        /** @var $response Mage_Core_Controller_Response_Http */
        $response = $front->getResponse();
        $response->setHeader('HTTP/1.1','503 Service Temporarily Unavailable');
        $response->setHeader('Status','503 Service Temporarily Unavailable');
        $response->setHeader('Retry-After','5000');

        $response->setBody(html_entity_decode( Mage::getStoreConfig('dev/offlinemaintenance/message', $request->getStoreCodeFromPath()), ENT_QUOTES, "utf-8" ));
        $response->sendHeaders();
        $response->outputBody();

        // We are done, we can exit now.
        exit;
    }



}