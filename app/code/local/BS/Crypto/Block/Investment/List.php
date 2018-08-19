<?php
/**
 * BS_Crypto extension
 * 
 * @category       BS
 * @package        BS_Crypto
 * @copyright      Copyright (c) 2018
 */
/**
 * Investment list block
 *
 * @category    BS
 * @package     BS_Crypto
 * @author Bui Phong
 */
class BS_Crypto_Block_Investment_List extends Mage_Core_Block_Template
{
    /**
     * initialize
     *
     * @access public
     * @author Bui Phong
     */
    public function __construct()
    {
        parent::__construct();


        $investments = Mage::getResourceModel('bs_crypto/investment_collection')
            ->addFieldToFilter('customer_id', Mage::getSingleton('customer/session')->getCustomer()->getId())
            ->setOrder('created_at', 'desc')
        ;
        $investments->setOrder('name', 'asc');
        $this->setInvestments($investments);
    }

    /**
     * prepare the layout
     *
     * @access protected
     * @return BS_Crypto_Block_Investment_List
     * @author Bui Phong
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        $pager = $this->getLayout()->createBlock(
            'page/html_pager',
            'bs_crypto.investment.html.pager'
        )
        ->setCollection($this->getInvestments());
        $this->setChild('pager', $pager);
        $this->getInvestments()->load();
        return $this;
    }

    /**
     * get the pager html
     *
     * @access public
     * @return string
     * @author Bui Phong
     */
    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }


}
