<?php
/**
 * BS_Crypto extension
 * 
 * @category       BS
 * @package        BS_Crypto
 * @copyright      Copyright (c) 2018
 */
/**
 * Account admin edit tabs
 *
 * @category    BS
 * @package     BS_Crypto
 * @author Bui Phong
 */
class BS_Crypto_Block_Adminhtml_Account_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    /**
     * Initialize Tabs
     *
     * @access public
     * @author Bui Phong
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('account_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('bs_crypto')->__('Account'));
    }

    /**
     * before render html
     *
     * @access protected
     * @return BS_Crypto_Block_Adminhtml_Account_Edit_Tabs
     * @author Bui Phong
     */
    protected function _beforeToHtml()
    {
        $this->addTab(
            'form_account',
            [
                'label'   => Mage::helper('bs_crypto')->__('Account'),
                'title'   => Mage::helper('bs_crypto')->__('Account'),
                'content' => $this->getLayout()->createBlock(
                    'bs_crypto/adminhtml_account_edit_tab_form'
                )
                ->toHtml(),
            ]
        );
        return parent::_beforeToHtml();
    }

    /**
     * Retrieve account entity
     *
     * @access public
     * @return BS_Crypto_Model_Account
     * @author Bui Phong
     */
    public function getAccount()
    {
        return Mage::registry('current_account');
    }
}
