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
class  Ho_OfflineMaintenance_Block_Adminhtml_System_Config_Exclude extends Mage_Adminhtml_Block_System_Config_Form_Field_Array_Abstract
{
    /**
     * Prepare to render
     */
    protected function _prepareToRender()
    {
        $this->addColumn('url_path', array(
            'label' => Mage::helper('customer')->__('URL Path'),
            'style' => 'width:300px',
        ));
        $this->_addAfter = false;
        $this->_addButtonLabel = Mage::helper('cataloginventory')->__('Add new rule');
    }
}
