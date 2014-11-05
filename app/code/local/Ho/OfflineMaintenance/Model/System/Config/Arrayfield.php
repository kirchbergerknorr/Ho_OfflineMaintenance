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
/**
 * Backend for serialized array data
 *
 * @category   Ho
 * @package    Ho_OfflineMaintenance
 * @author     H&O Developers <info@h-o.nl>
 */
?>
<?php
abstract class Ho_OfflineMaintenance_Model_System_Config_Arrayfield
    extends Mage_Core_Model_Config_Data
{
    protected $_helper = '';

    /**
     * Process data after load
     */
    protected function _afterLoad()
    {
        $value = $this->getValue();

        /** @var $helperShow Mage_Core_Helper_Abstract */
        $helperShow = Mage::helper($this->_helper);
        $value = $helperShow->makeArrayFieldValue($value);
        $this->setValue($value);
    }

    /**
     * Prepare data before save
     */
    protected function _beforeSave()
    {
        $value = $this->getValue();

        /** @var $helperShow Mage_Core_Helper_Abstract */
        $helperShow = Mage::helper($this->_helper);
        $value = $helperShow->makeStorableArrayFieldValue($value);
        $this->setValue($value);
    }
}
