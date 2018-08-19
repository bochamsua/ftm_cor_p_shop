<?php
/**
 * BS_Crypto extension
 *
 * @category       BS
 * @package        BS_Crypto
 * @copyright      Copyright (c) 2018
 */
/**
 * Crypto default helper
 *
 * @category    BS
 * @package     BS_Crypto
 * @author Bui Phong
 */
require_once(dirname(__FILE__).DS.'lib'.DS.'binance.php');

class BS_Crypto_Helper_Data extends Mage_Core_Helper_Abstract
{
    /**
     * convert array to options
     *
     * @access public
     * @param $options
     * @return array
     * @author Bui Phong
     */
    public function convertOptions($options)
    {
        $converted = [];
        foreach ($options as $option) {
            if (isset($option['value']) && !is_array($option['value']) &&
                isset($option['label']) && !is_array($option['label'])) {
                $converted[$option['value']] = $option['label'];
            }
        }
        return $converted;
    }

    public function getNextInvestmentNo(){


        $collection    = Mage::getModel("bs_crypto/investment")->getCollection();

        $collection->setOrder('entity_id', 'DESC');

        $nextRefNo = null;
        if($collection->getFirstItem() && $collection->getFirstItem()->getId()){
            $lastRefNo = $collection->getFirstItem()->getName();
            $lastRefNo = explode("-", $lastRefNo);
            $lastIncrement = intval(end($lastRefNo));
            $nextIncrement = $lastIncrement + 1;

            if($nextIncrement < 10){
                $nextIncrement = '0'.$nextIncrement;
            }

            $nextRefNo = sprintf("INV-%s", $nextIncrement);

        }else {
            $nextRefNo = sprintf("INV-01");
        }

        return $nextRefNo;
    }


    public function getAccountInfo($periodId){

        $period = Mage::getSingleton('bs_crypto/period')->load($periodId);
        $account = $period->getParentAccount();

        if($account->getId()){
            $api = new Binance\API($account->getApiKey(), $account->getApiSecret());


            $price = $api->prices();

            $btcPrice = $price['BTCUSDT'];

            $balances = $api->balances($price);

            $totalBtc = $api->btc_total;
            $totalUSDT = $totalBtc * $btcPrice;

            return [
                'btc' => $totalBtc,
                'usdt'  => $totalUSDT
            ];

        }

        return false;
    }

    public function getCustomerInvestmentInfo($customerId){

        $investment = Mage::getModel('bs_crypto/investment')->getCollection()->addFieldToFilter('customer_id', ['eq' => $customerId]);

        if($inv = $investment->getFirstItem() && $investment->getFirstItem()->getId()){
            $period = $inv->getPeriodId();
        }


        //$total = $this->getAccountInfo($exCode);


    }
}
