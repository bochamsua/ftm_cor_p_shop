<?php
/**
 * BS_Crypto extension
 * 
 * @category       BS
 * @package        BS_Crypto
 * @copyright      Copyright (c) 2018
 */
/**
 * Investment widget block
 *
 * @category    BS
 * @package     BS_Crypto
 * @author Bui Phong
 */
class BS_Crypto_Block_Investment_Widget_View extends Mage_Core_Block_Template implements
    Mage_Widget_Block_Interface
{
    protected $_htmlTemplate = 'bs_crypto/investment/widget/view.phtml';

    /**
     * Prepare a for widget
     *
     * @access protected
     * @return BS_Crypto_Block_Investment_Widget_View
     * @author Bui Phong
     */
    protected function _beforeToHtml()
    {
        parent::_beforeToHtml();
        $investmentId = $this->getData('investment_id');
        if ($investmentId) {
            $investment = Mage::getModel('bs_crypto/investment')
                ->setStoreId(Mage::app()->getStore()->getId())
                ->load($investmentId);
            if ($investment->getStatus()) {
                $this->setCurrentInvestment($investment);
                $this->setTemplate($this->_htmlTemplate);
            }
        }
        return $this;
    }
}
