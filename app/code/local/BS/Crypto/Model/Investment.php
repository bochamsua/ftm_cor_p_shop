<?php
/**
 * BS_Crypto extension
 * 
 * @category       BS
 * @package        BS_Crypto
 * @copyright      Copyright (c) 2018
 */
/**
 * Investment model
 *
 * @category    BS
 * @package     BS_Crypto
 * @author Bui Phong
 */
class BS_Crypto_Model_Investment extends Mage_Core_Model_Abstract
{
    /**
     * Entity code.
     * Can be used as part of method name for entity processing
     */
    const ENTITY    = 'bs_crypto_investment';
    const CACHE_TAG = 'bs_crypto_investment';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'bs_crypto_investment';

    /**
     * Parameter name in event
     *
     * @var string
     */
    protected $_eventObject = 'investment';

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
        $this->_init('bs_crypto/investment');
    }

    /**
     * before save investment
     *
     * @access protected
     * @return BS_Crypto_Model_Investment
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
     * get the url to the investment details page
     *
     * @access public
     * @return string
     * @author Bui Phong
     */
    public function getInvestmentUrl()
    {
        return Mage::getUrl('bs_crypto/investment/view', ['id'=>$this->getId()]);
    }

    /**
     * save investment relation
     *
     * @access public
     * @return BS_Crypto_Model_Investment
     * @author Bui Phong
     */
    protected function _afterSave()
    {
        return parent::_afterSave();
    }

    /**
     * Retrieve parent 
     *
     * @access public
     * @return null|BS_Crypto_Model_Period
     * @author Bui Phong
     */
    public function getParentPeriod()
    {
        if (!$this->hasData('_parent_period')) {
            if (!$this->getPeriodId()) {
                return null;
            } else {
                $period = Mage::getModel('bs_crypto/period')
                    ->load($this->getPeriodId());
                if ($period->getId()) {
                    $this->setData('_parent_period', $period);
                } else {
                    $this->setData('_parent_period', null);
                }
            }
        }
        return $this->getData('_parent_period');
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
