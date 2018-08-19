<?php
/**
 * BS_Crypto extension
 * 
 * @category       BS
 * @package        BS_Crypto
 * @copyright      Copyright (c) 2018
 */
/**
 * Investment front contrller
 *
 * @category    BS
 * @package     BS_Crypto
 * @author Bui Phong
 */
class BS_Crypto_InvestmentController extends Mage_Core_Controller_Front_Action
{

    public function preDispatch()
    {
        parent::preDispatch();
        $action = $this->getRequest()->getActionName();
        $loginUrl = Mage::helper('customer')->getLoginUrl();

        if (!Mage::getSingleton('customer/session')->authenticate($this, $loginUrl)) {
            $this->setFlag('', self::FLAG_NO_DISPATCH, true);
        }
    }

    /**
      * default action
      *
      * @access public
      * @return void
      * @author Bui Phong
      */
    public function indexAction()
    {
        $this->loadLayout();
        $this->_initLayoutMessages('catalog/session');
        $this->_initLayoutMessages('customer/session');
        $this->_initLayoutMessages('checkout/session');

        $this->getLayout()->getBlock('head')->setTitle($this->__('My Investment'));

        if (Mage::helper('bs_crypto/investment')->getUseBreadcrumbs()) {
            if ($breadcrumbBlock = $this->getLayout()->getBlock('breadcrumbs')) {
                $breadcrumbBlock->addCrumb(
                    'home',
                    [
                        'label' => Mage::helper('bs_crypto')->__('Home'),
                        'link'  => Mage::getUrl(),
                    ]
                );
                $breadcrumbBlock->addCrumb(
                    'investments',
                    [
                        'label' => Mage::helper('bs_crypto')->__('Investments'),
                        'link'  => '',
                    ]
                );
            }
        }
        $headBlock = $this->getLayout()->getBlock('head');
        if ($headBlock) {
            $headBlock->addLinkRel('canonical', Mage::helper('bs_crypto/investment')->getInvestmentsUrl());
        }
        $this->renderLayout();
    }

    /**
     * init Investment
     *
     * @access protected
     * @return BS_Crypto_Model_Investment
     * @author Bui Phong
     */
    protected function _initInvestment()
    {
        $investmentId   = $this->getRequest()->getParam('id', 0);
        $investment     = Mage::getModel('bs_crypto/investment')
            ->setStoreId(Mage::app()->getStore()->getId())
            ->load($investmentId);
        if (!$investment->getId()) {
            return false;
        }
        return $investment;
    }

    /**
     * view investment action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function viewAction()
    {
        $investment = $this->_initInvestment();
        if (!$investment) {
            $this->_forward('no-route');
            return;
        }
        Mage::register('current_investment', $investment);
        $this->loadLayout();
        $this->_initLayoutMessages('catalog/session');
        $this->_initLayoutMessages('customer/session');
        $this->_initLayoutMessages('checkout/session');

        $this->getLayout()->getBlock('head')->setTitle($investment->getName());

        if ($root = $this->getLayout()->getBlock('root')) {
            $root->addBodyClass('crypto-investment crypto-investment' . $investment->getId());
        }
        if (Mage::helper('bs_crypto/investment')->getUseBreadcrumbs()) {
            if ($breadcrumbBlock = $this->getLayout()->getBlock('breadcrumbs')) {
                $breadcrumbBlock->addCrumb(
                    'home',
                    [
                        'label'    => Mage::helper('bs_crypto')->__('Home'),
                        'link'     => Mage::getUrl(),
                    ]
                );
                $breadcrumbBlock->addCrumb(
                    'investments',
                    [
                        'label' => Mage::helper('bs_crypto')->__('Investments'),
                        'link'  => Mage::helper('bs_crypto/investment')->getInvestmentsUrl(),
                    ]
                );
                $breadcrumbBlock->addCrumb(
                    'investment',
                    [
                        'label' => $investment->getName(),
                        'link'  => '',
                    ]
                );
            }
        }
        $headBlock = $this->getLayout()->getBlock('head');
        if ($headBlock) {
            $headBlock->addLinkRel('canonical', $investment->getInvestmentUrl());
        }
        $this->renderLayout();
    }
}
