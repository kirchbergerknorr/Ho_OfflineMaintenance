<?php
/**
 * H&O Module Ho_OfflineMaintenance
 * Fork of the ArsOnIt_OfflineMaintenance module:
 * http://www.magentocommerce.com/magento-connect/maintenance-page-artson-it.html
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
 * @copyright   Copyright (c) 2014 H&O (http://www.h-o.nl/)
 * @license     H&O Commercial License (http://www.h-o.nl/license)
 */

?>
<?php
class Ho_OfflineMaintenance_Helper_Data
    extends Mage_Core_Helper_Abstract
{
    public function getAdminhtmlCss()
    {
        if ($this->isOffline() && Mage::getStoreConfigFlag('dev/offlinemaintenance/showreminder_admin')) {
            return 'ho/offlinemaintenance/offline.css';
        }

        return 'ho/offlinemaintenance/online.css';
    }

    public function isOffline()
    {
        // Is offline maintenance enabled?
        $request = Mage::app()->getRequest();
        if (Mage::getStoreConfigFlag('dev/offlinemaintenance/enabled', $request->getStoreCodeFromPath())) {
            return true;
        }

        return false;
    }
}
