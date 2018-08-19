<?php
/**
 * BS_Crypto extension
 * 
 * @category       BS
 * @package        BS_Crypto
 * @copyright      Copyright (c) 2018
 */
/**
 * Account admin block
 *
 * @category    BS
 * @package     BS_Crypto
 * @author Bui Phong
 */
class BS_Crypto_Block_Adminhtml_Account extends Mage_Adminhtml_Block_Widget_Grid_Container
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
        $this->_controller         = 'adminhtml_account';
        $this->_blockGroup         = 'bs_crypto';
        parent::__construct();
        $this->_headerText         = Mage::helper('bs_crypto')->__('Account');
        $this->_updateButton('add', 'label', Mage::helper('bs_crypto')->__('Add Account'));


        $isAllowed = Mage::getSingleton('admin/session')->isAllowed("bs_crypto/account/new");
        if(!$isAllowed){
                $this->_removeButton('add');
        }

    }
}
