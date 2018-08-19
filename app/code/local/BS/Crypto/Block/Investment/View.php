<?php
/**
 * BS_Crypto extension
 * 
 * @category       BS
 * @package        BS_Crypto
 * @copyright      Copyright (c) 2018
 */
/**
 * Investment view block
 *
 * @category    BS
 * @package     BS_Crypto
 * @author Bui Phong
 */
class BS_Crypto_Block_Investment_View extends Mage_Core_Block_Template
{
    /**
     * get the current investment
     *
     * @access public
     * @return mixed (BS_Crypto_Model_Investment|null)
     * @author Bui Phong
     */
    public function getCurrentInvestment()
    {
        return Mage::registry('current_investment');
    }

    public function getBackUrl()
    {
        return Mage::getUrl('*/*/');
    }
}
