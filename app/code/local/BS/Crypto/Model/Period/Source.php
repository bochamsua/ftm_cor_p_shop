<?php
/**
 * BS_Crypto extension
 * 
 * @category       BS
 * @package        BS_Crypto
 * @copyright      Copyright (c) 2018
 */
/**
 * Investment Period source model
 *
 * @category    BS
 * @package     BS_Crypto
 * @author Bui Phong
 */
class BS_Crypto_Model_Period_Source extends Mage_Eav_Model_Entity_Attribute_Source_Abstract
{
    /**
     * Get all options
     *
     * @access public
     * @param bool $withEmpty
     * @return array
     * @author Bui Phong
     */
    public function getAllOptions($withEmpty = false)
    {
        if (is_null($this->_options)) {
            $this->_options = Mage::getResourceModel('bs_crypto/period_collection')

            ->load()
            ->toOptionArray();
        }
        $options = $this->_options;
        if ($withEmpty) {
            array_unshift($options, ['value'=>'', 'label'=>'']);
        }
        return $options;
    }

    /**
     * Get a text for option value
     *
     * @access public
     * @param string|integer $value
     * @return string
     * @author Bui Phong
     */
    public function getOptionText($value)
    {
        $options = $this->getAllOptions(false);
        foreach ($options as $item) {
            if ($item['value'] == $value) {
                return $item['label'];
            }
        }
        return false;
    }

    /**
     * Convert to options array
     *
     * @access public
     * @return array
     * @author Bui Phong
     */
    public function toOptionArray()
    {
        return $this->getAllOptions();
    }

    /**
     * Retrieve flat column definition
     *
     * @access public
     * @return array
     * @author Bui Phong
     */
    public function getFlatColums()
    {
        $attributeCode = $this->getAttribute()->getAttributeCode();
        $column = [
            'unsigned'  => true,
            'default'   => null,
            'extra'     => null
        ];
        if (Mage::helper('core')->useDbCompatibleMode()) {
            $column['type']     = 'int';
            $column['is_null']  = true;
        } else {
            $column['type']     = Varien_Db_Ddl_Table::TYPE_INTEGER;
            $column['nullable'] = true;
            $column['comment']  = $attributeCode . ' Investment Period column';
        }
        return [$attributeCode => $column];
    }

    /**
     * Retrieve Select for update attribute value in flat table
     *
     * @access public
     * @param   int $store
     * @return  Varien_Db_Select|null
     * @author Bui Phong
     */
    public function getFlatUpdateSelect($store)
    {
        return Mage::getResourceModel('eav/entity_attribute_option')
            ->getFlatUpdateSelect($this->getAttribute(), $store, false);
    }
}
