<?php
/**
 * BS_Crypto extension
 * 
 * @category       BS
 * @package        BS_Crypto
 * @copyright      Copyright (c) 2018
 */
/**
 * Account model
 *
 * @category    BS
 * @package     BS_Crypto
 * @author Bui Phong
 */
class BS_Crypto_Model_Account extends Mage_Core_Model_Abstract
{
    /**
     * Entity code.
     * Can be used as part of method name for entity processing
     */
    const ENTITY    = 'bs_crypto_account';
    const CACHE_TAG = 'bs_crypto_account';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'bs_crypto_account';

    /**
     * Parameter name in event
     *
     * @var string
     */
    protected $_eventObject = 'account';

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
        $this->_init('bs_crypto/account');
    }

    /**
     * before save account
     *
     * @access protected
     * @return BS_Crypto_Model_Account
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
     * save account relation
     *
     * @access public
     * @return BS_Crypto_Model_Account
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
     * @return BS_Crypto_Model_Period_Collection
     * @author Bui Phong
     */
    public function getSelectedPeriodsCollection()
    {
        if (!$this->hasData('_period_collection')) {
            if (!$this->getId()) {
                return new Varien_Data_Collection();
            } else {
                $collection = Mage::getResourceModel('bs_crypto/period_collection')
                        ->addFieldToFilter('account_id', $this->getId());
                $this->setData('_period_collection', $collection);
            }
        }
        return $this->getData('_period_collection');
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
