<?php 
/**
 * BS_Crypto extension
 * 
 * @category       BS
 * @package        BS_Crypto
 * @copyright      Copyright (c) 2018
 */
/**
 * Investment helper
 *
 * @category    BS
 * @package     BS_Crypto
 * @author Bui Phong
 */
class BS_Crypto_Helper_Investment extends Mage_Core_Helper_Abstract
{

    /**
     * get the url to the investments list page
     *
     * @access public
     * @return string
     * @author Bui Phong
     */
    public function getInvestmentsUrl()
    {
        if ($listKey = Mage::getStoreConfig('bs_crypto/investment/url_rewrite_list')) {
            return Mage::getUrl('', ['_direct'=>$listKey]);
        }
        return Mage::getUrl('bs_crypto/investment/index');
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
        return Mage::getStoreConfigFlag('bs_crypto/investment/breadcrumbs');
    }

    public function getTotalInvestments(){
        $investment =  Mage::getResourceModel('bs_crypto/investment')->getSum('init_value');

        return number_format($investment, 2);
    }

    public function getTotalProfits(){
        $investment =  Mage::getResourceModel('bs_crypto/investment')->getSum('init_value');

        $totalFinal = Mage::getResourceModel('bs_crypto/period')->getSum('total_final');
        return number_format($totalFinal - $investment, 2);
    }

    public function getDateInfo($inv){

        $period = $inv->getParentPeriod();

        return [
            'start' => Mage::helper('bs_crypto/date')->formatDisplayDate($period->getStartDate(),'dd/MM/yyyy'),
            'end'   => Mage::helper('bs_crypto/date')->formatDisplayDate($period->getEndDate(),'dd/MM/yyyy'),
        ];
    }

    public function getProfitInfo($inv){

        $period = $inv->getParentPeriod();

        return [
            'current' => Mage::helper('bs_crypto/date')->formatDisplayDate($period->getStartDate(),'dd/MM/yyyy'),
            'percent'   => Mage::helper('bs_crypto/date')->formatDisplayDate($period->getEndDate(),'dd/MM/yyyy'),
        ];
    }
}
