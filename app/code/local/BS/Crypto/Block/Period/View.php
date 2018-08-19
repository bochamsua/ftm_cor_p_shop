<?php
/**
 * BS_Crypto extension
 * 
 * @category       BS
 * @package        BS_Crypto
 * @copyright      Copyright (c) 2018
 */
/**
 * Investment Period view block
 *
 * @category    BS
 * @package     BS_Crypto
 * @author Bui Phong
 */
class BS_Crypto_Block_Period_View extends Mage_Core_Block_Template
{
    /**
     * get the current investment period
     *
     * @access public
     * @return mixed (BS_Crypto_Model_Period|null)
     * @author Bui Phong
     */
    public function getCurrentPeriod()
    {
        return Mage::registry('current_period');
    }
}
