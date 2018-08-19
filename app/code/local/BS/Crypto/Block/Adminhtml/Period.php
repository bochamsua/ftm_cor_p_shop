<?php
/**
 * BS_Crypto extension
 * 
 * @category       BS
 * @package        BS_Crypto
 * @copyright      Copyright (c) 2018
 */
/**
 * Investment Period admin block
 *
 * @category    BS
 * @package     BS_Crypto
 * @author Bui Phong
 */
class BS_Crypto_Block_Adminhtml_Period extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    /**
     * constructor
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function __construct()
    {
        $this->_controller         = 'adminhtml_period';
        $this->_blockGroup         = 'bs_crypto';
        parent::__construct();
        $this->_headerText         = Mage::helper('bs_crypto')->__('Investment Period');
        $this->_updateButton('add', 'label', Mage::helper('bs_crypto')->__('Add Investment Period'));


        $isAllowed = Mage::getSingleton('admin/session')->isAllowed("bs_crypto/period/new");
        if(!$isAllowed){
                $this->_removeButton('add');
        }

    }
}
