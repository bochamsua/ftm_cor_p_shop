<?php
/**
 * BS_Crypto extension
 * 
 * @category       BS
 * @package        BS_Crypto
 * @copyright      Copyright (c) 2018
 */
/**
 * Investment Period model
 *
 * @category    BS
 * @package     BS_Crypto
 * @author Bui Phong
 */
class BS_Crypto_Model_Period extends Mage_Core_Model_Abstract
{
    /**
     * Entity code.
     * Can be used as part of method name for entity processing
     */
    const ENTITY    = 'bs_crypto_period';
    const CACHE_TAG = 'bs_crypto_period';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'bs_crypto_period';

    /**
     * Parameter name in event
     *
     * @var string
     */
    protected $_eventObject = 'period';

    /**
     * constructor
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function _construct()
    {
        parent::_construct();
        $this->_init('bs_crypto/period');
    }

    /**
     * before save investment period
     *
     * @access protected
     * @return BS_Crypto_Model_Period
     * @author Bui Phong
     */
    protected function _beforeSave()
    {
        parent::_beforeSave();
        $now = Mage::getSingleton('core/date')->gmtDate();
        if ($this->isObjectNew()) {
            $this->setCreatedAt($now);
        }
        $this->setUpdatedAt($now);
        return $this;
    }

    /**
     * get the url to the investment period details page
     *
     * @access public
     * @return string
     * @author Bui Phong
     */
    public function getPeriodUrl()
    {
        return Mage::getUrl('bs_crypto/period/view', ['id'=>$this->getId()]);
    }

    /**
     * save investment period relation
     *
     * @access public
     * @return BS_Crypto_Model_Period
     * @author Bui Phong
     */
    protected function _afterSave()
    {
        return parent::_afterSave();
    }

    /**
     * Retrieve  collection
     *
     * @access public
     * @return BS_Crypto_Model_Investment_Collection
     * @author Bui Phong
     */
    public function getSelectedInvestmentsCollection()
    {
        if (!$this->hasData('_investment_collection')) {
            if (!$this->getId()) {
                return new Varien_Data_Collection();
            } else {
                $collection = Mage::getResourceModel('bs_crypto/investment_collection')
                        ->addFieldToFilter('period_id', $this->getId());
                $this->setData('_investment_collection', $collection);
            }
        }
        return $this->getData('_investment_collection');
    }

    /**
     * Retrieve parent 
     *
     * @access public
     * @return null|BS_Crypto_Model_Account
     * @author Bui Phong
     */
    public function getParentAccount()
    {
        if (!$this->hasData('_parent_account')) {
            if (!$this->getAccountId()) {
                return null;
            } else {
                $account = Mage::getModel('bs_crypto/account')
                    ->load($this->getAccountId());
                if ($account->getId()) {
                    $this->setData('_parent_account', $account);
                } else {
                    $this->setData('_parent_account', null);
                }
            }
        }
        return $this->getData('_parent_account');
    }

    /**
     * get default values
     *
     * @access public
     * @return array
     * @author Bui Phong
     */
    public function getDefaultValues()
    {
        $values = [];
        $values['status'] = 1;
        return $values;
    }
    
}
