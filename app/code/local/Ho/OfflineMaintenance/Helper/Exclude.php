<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Mage
 * @package     Mage_CatalogInventory
 * @copyright   Copyright (c) 2011 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */


class Ho_OfflineMaintenance_Helper_Exclude extends Mage_Core_Helper_Abstract
{
    /**
     * Retrieve fixed qty value
     *
     * @param mixed $qty
     * @return float|null
     */
    protected function _fixQty($qty)
    {
	    return (!empty($qty) ? (float)$qty : null);
    }

    /**
     * Generate a storable representation of a value
     *
     * @param mixed $value
     * @return string
     */
    protected function _serializeValue($value)
    {
        unset($value['__empty']);
        if (is_numeric($value)) {
            $data = (float)$value;
            return (string)$data;
        } else if (is_array($value)) {
            $data = array();
            foreach ($value as $groupId => $qty) {
            if (!array_key_exists($groupId, $data)) {
                $data[$groupId] = $qty;
            }
            }
            return serialize($data);
        } else {
            return '';
        }
    }

    /**
     * Create a value from a storable representation
     *
     * @param mixed $value
     * @return array
     */
    protected function _unserializeValue($value)
    {
        if (is_numeric($value)) {
            return array(
            Mage_Customer_Model_Group::CUST_GROUP_ALL => $value
            );
        } else if (is_string($value) && !empty($value)) {
            return unserialize($value);
        } else {
            return array();
        }
    }

    /**
     * Check whether value is in form retrieved by _encodeArrayFieldValue()
     *
     * @param mixed
     * @return bool
     */
    protected function _isEncodedArrayFieldValue($value)
    {
        if (!is_array($value)) {
            return false;
        }
        unset($value['__empty']);
        foreach ($value as $_id => $row) {
            if (!is_string($row)) {
            return false;
            }
        }
        return true;
    }

    /**
     * Encode value to be used in Mage_Adminhtml_Block_System_Config_Form_Field_Array_Abstract
     *
     * @param array
     * @return array
     */
    protected function _encodeArrayFieldValue(array $value)
    {
        $result = array();
        unset($value['__empty']);
        foreach ($value as $key => $data) {
            if (strlen($data['url_path']) > 0)
            {
            $result[] = $data;
            }
        }
        return $result;
    }

    /**
     * Decode value from used in Mage_Adminhtml_Block_System_Config_Form_Field_Array_Abstract
     *
     * @param array
     * @return array
     */
    protected function _decodeArrayFieldValue(array $value)
    {
        $result = array();
        unset($value['__empty']);
        foreach ($value as $_id => $row) {
            if (   !is_array($row)
            || !array_key_exists('url_path', $row)
            )
            {
            continue;
            }
            $result[] = $row['url_path'];
        }
        return $result;
    }

    /**
     * Retrieve min_sale_qty value from config
     *
     * @param mixed $store
     * @return float|null
     */
    public function getConfigValue($store = null)
    {
        $value = Mage::getStoreConfig('dev/offlinemaintenance/exclude_pages', $store);
        $value = $this->_unserializeValue($value);
        if ($this->_isEncodedArrayFieldValue($value)) {
            $value = $this->_decodeArrayFieldValue($value);
        }

        return $value;
    }

    /**
     * Make value readable by Mage_Adminhtml_Block_System_Config_Form_Field_Array_Abstract
     *
     * @param mixed $value
     * @return array
     */
    public function makeArrayFieldValue($value)
    {
        $value = $this->_unserializeValue($value);
        if (!$this->_isEncodedArrayFieldValue($value)) {
            $value = $this->_encodeArrayFieldValue($value);
        }
        return $value;
    }

    /**
     * Make value ready for store
     *
     * @param mixed $value
     * @return string
     */
    public function makeStorableArrayFieldValue($value)
    {
        if ($this->_isEncodedArrayFieldValue($value)) {
            $value = $this->_decodeArrayFieldValue($value);
        }
        $value = $this->_serializeValue($value);
        return $value;
    }
}
