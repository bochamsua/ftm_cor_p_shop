<?php
/**
 * BS_Crypto extension
 * 
 * @category       BS
 * @package        BS_Crypto
 * @copyright      Copyright (c) 2018
 */
/**
 * Investment Period list block
 *
 * @category    BS
 * @package     BS_Crypto
 * @author Bui Phong
 */
class BS_Crypto_Block_Period_List extends Mage_Core_Block_Template
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
        $periods = Mage::getResourceModel('bs_crypto/period_collection')
                         ->addFieldToFilter('status', 1);
        $periods->setOrder('name', 'asc');
        $this->setPeriods($periods);
    }

    /**
     * prepare the layout
     *
     * @access protected
     * @return BS_Crypto_Block_Period_List
     * @author Bui Phong
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        $pager = $this->getLayout()->createBlock(
            'page/html_pager',
            'bs_crypto.period.html.pager'
        )
        ->setCollection($this->getPeriods());
        $this->setChild('pager', $pager);
        $this->getPeriods()->load();
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
