<?php
/**
 * BS_Crypto extension
 * 
 * @category       BS
 * @package        BS_Crypto
 * @copyright      Copyright (c) 2018
 */
/**
 * Admin search model
 *
 * @category    BS
 * @package     BS_Crypto
 * @author Bui Phong
 */
class BS_Crypto_Model_Adminhtml_Search_Investment extends Varien_Object
{
    /**
     * Load search results
     *
     * @access public
     * @return BS_Crypto_Model_Adminhtml_Search_Investment
     * @author Bui Phong
     */
    public function load()
    {
        $arr = [];
        if (!$this->hasStart() || !$this->hasLimit() || !$this->hasQuery()) {
            $this->setResults($arr);
            return $this;
        }
        $collection = Mage::getResourceModel('bs_crypto/investment_collection')
            ->addFieldToFilter('name', ['like' => $this->getQuery().'%'])
            ->setCurPage($this->getStart())
            ->setPageSize($this->getLimit())
            ->load();
        foreach ($collection->getItems() as $investment) {
            $arr[] = [
                'id'          => 'investment/1/'.$investment->getId(),
                'type'        => Mage::helper('bs_crypto')->__('Investment'),
                'name'        => $investment->getName(),
                'description' => $investment->getName(),
                'url' => Mage::helper('adminhtml')->getUrl(
                    '*/crypto_investment/edit',
                    ['id'=>$investment->getId()]
                ),
            ];
        }
        $this->setResults($arr);
        return $this;
    }
}
