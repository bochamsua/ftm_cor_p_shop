<?php
/**
 * BS_Crypto extension
 * 
 * @category       BS
 * @package        BS_Crypto
 * @copyright      Copyright (c) 2018
 */
/**
 * Investment Period widget block
 *
 * @category    BS
 * @package     BS_Crypto
 * @author Bui Phong
 */
class BS_Crypto_Block_Period_Widget_View extends Mage_Core_Block_Template implements
    Mage_Widget_Block_Interface
{
    protected $_htmlTemplate = 'bs_crypto/period/widget/view.phtml';

    /**
     * Prepare a for widget
     *
     * @access protected
     * @return BS_Crypto_Block_Period_Widget_View
     * @author Bui Phong
     */
    protected function _beforeToHtml()
    {
        parent::_beforeToHtml();
        $periodId = $this->getData('period_id');
        if ($periodId) {
            $period = Mage::getModel('bs_crypto/period')
                ->setStoreId(Mage::app()->getStore()->getId())
                ->load($periodId);
            if ($period->getStatus()) {
                $this->setCurrentPeriod($period);
                $this->setTemplate($this->_htmlTemplate);
            }
        }
        return $this;
    }
}
