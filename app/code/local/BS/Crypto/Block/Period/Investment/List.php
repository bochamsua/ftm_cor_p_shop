<?php
/**
 * BS_Crypto extension
 * 
 * @category       BS
 * @package        BS_Crypto
 * @copyright      Copyright (c) 2018
 */
/**
 * Investment Period Investments list block
 *
 * @category    BS
 * @package     BS_Crypto
 * @author Bui Phong
 */
class BS_Crypto_Block_Period_Investment_List extends BS_Crypto_Block_Investment_List
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
        $period = $this->getPeriod();
        if ($period) {
            $this->getInvestments()->addFieldToFilter('period_id', $period->getId());
        }
    }

    /**
     * prepare the layout - actually do nothing
     *
     * @access protected
     * @return BS_Crypto_Block_Period_Investment_List
     * @author Bui Phong
     */
    protected function _prepareLayout()
    {
        return $this;
    }

    /**
     * get the current investment period
     *
     * @access public
     * @return BS_Crypto_Model_Period
     * @author Bui Phong
     */
    public function getPeriod()
    {
        return Mage::registry('current_period');
    }
}
