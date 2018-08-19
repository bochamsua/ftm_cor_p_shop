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

            $totalUSDT = 0;
            foreach ($balances as $coin => $value) {
                    $coins = $value['available'] + $value['onOrder'];

                    $btcTotal = $value['btcTotal'];

                    if($coin == 'USDT'){
                        $totalUSDT += $coins;
                    }else {
                        if(isset($price[$coin.'USDT'])){
                            $usdtPrice = $price[$coin.'USDT'];
                            $totalUSDT += $coins * $usdtPrice;
                        }else {
                            $totalUSDT += $btcTotal * $btcPrice;
                        }
                    }
            }



            $total = $period->getTotal();


            return [
                'current'  => $totalUSDT,
                'total'  => $total,
            ];

        }

        return false;
    }

    public function getCustomerInvestmentInfo($_investment){

        $periodId = $_investment->getPeriodId();

        $currentState = $this->getAccountInfo($periodId);;

        $init = $_investment->getInitValue();

        $currentTotal = $init * $currentState['current']/$currentState['total'];

        $percent = ($currentTotal - $init)*100/$init;


        return [
            'current'  => number_format($currentTotal, 2),
            'percent'  => number_format($percent, 2),
        ];




    }
}
