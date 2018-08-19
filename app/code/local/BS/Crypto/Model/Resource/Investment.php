<?php
/**
 * BS_Crypto extension
 * 
 * @category       BS
 * @package        BS_Crypto
 * @copyright      Copyright (c) 2018
 */
/**
 * Investment resource model
 *
 * @category    BS
 * @package     BS_Crypto
 * @author Bui Phong
 */
class BS_Crypto_Model_Resource_Investment extends Mage_Core_Model_Resource_Db_Abstract
{

    /**
     * constructor
     *
     * @access public
     * @author Bui Phong
     */
    public function _construct()
    {
        $this->_init('bs_crypto/investment', 'entity_id');
    }

    public function getSum($column)
    {
        $select = $this->getReadConnection()
            ->select()
            ->from($this->getMainTable(), array('sum' => new Zend_Db_Expr('SUM('.$column.')')));

        return $this->getReadConnection()->fetchOne($select);
    }
}
