<?php
/**
 * BS_Crypto extension
 * 
 * @category       BS
 * @package        BS_Crypto
 * @copyright      Copyright (c) 2018
 */
/**
 * Investment admin edit tabs
 *
 * @category    BS
 * @package     BS_Crypto
 * @author Bui Phong
 */
class BS_Crypto_Block_Adminhtml_Investment_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
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
        $this->setId('investment_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('bs_crypto')->__('Investment'));
    }

    /**
     * before render html
     *
     * @access protected
     * @return BS_Crypto_Block_Adminhtml_Investment_Edit_Tabs
     * @author Bui Phong
     */
    protected function _beforeToHtml()
    {
        $this->addTab(
            'form_investment',
            [
                'label'   => Mage::helper('bs_crypto')->__('Investment'),
                'title'   => Mage::helper('bs_crypto')->__('Investment'),
                'content' => $this->getLayout()->createBlock(
                    'bs_crypto/adminhtml_investment_edit_tab_form'
                )
                ->toHtml(),
            ]
        );
        return parent::_beforeToHtml();
    }

    /**
     * Retrieve investment entity
     *
     * @access public
     * @return BS_Crypto_Model_Investment
     * @author Bui Phong
     */
    public function getInvestment()
    {
        return Mage::registry('current_investment');
    }
}