<?php 
/**
 * BS_Crypto extension
 * 
 * @category       BS
 * @package        BS_Crypto
 * @copyright      Copyright (c) 2018
 */
/**
 * Investment Period helper
 *
 * @category    BS
 * @package     BS_Crypto
 * @author Bui Phong
 */
class BS_Crypto_Helper_Period extends Mage_Core_Helper_Abstract
{

    /**
     * get the url to the investment periods list page
     *
     * @access public
     * @return string
     * @author Bui Phong
     */
    public function getPeriodsUrl()
    {
        if ($listKey = Mage::getStoreConfig('bs_crypto/period/url_rewrite_list')) {
            return Mage::getUrl('', ['_direct'=>$listKey]);
        }
        return Mage::getUrl('bs_crypto/period/index');
    }

    /**
     * check if breadcrumbs can be used
     *
     * @access public
     * @return bool
     * @author Bui Phong
     */
    public function getUseBreadcrumbs()
    {
        return Mage::getStoreConfigFlag('bs_crypto/period/breadcrumbs');
    }
}
