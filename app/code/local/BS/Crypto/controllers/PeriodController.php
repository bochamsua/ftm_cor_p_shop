<?php
/**
 * BS_Crypto extension
 * 
 * @category       BS
 * @package        BS_Crypto
 * @copyright      Copyright (c) 2018
 */
/**
 * Investment Period front contrller
 *
 * @category    BS
 * @package     BS_Crypto
 * @author Bui Phong
 */
class BS_Crypto_PeriodController extends Mage_Core_Controller_Front_Action
{

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
        if (Mage::helper('bs_crypto/period')->getUseBreadcrumbs()) {
            if ($breadcrumbBlock = $this->getLayout()->getBlock('breadcrumbs')) {
                $breadcrumbBlock->addCrumb(
                    'home',
                    [
                        'label' => Mage::helper('bs_crypto')->__('Home'),
                        'link'  => Mage::getUrl(),
                    ]
                );
                $breadcrumbBlock->addCrumb(
                    'periods',
                    [
                        'label' => Mage::helper('bs_crypto')->__('Investment Periods'),
                        'link'  => '',
                    ]
                );
            }
        }
        $headBlock = $this->getLayout()->getBlock('head');
        if ($headBlock) {
            $headBlock->addLinkRel('canonical', Mage::helper('bs_crypto/period')->getPeriodsUrl());
        }
        $this->renderLayout();
    }

    /**
     * init Investment Period
     *
     * @access protected
     * @return BS_Crypto_Model_Period
     * @author Bui Phong
     */
    protected function _initPeriod()
    {
        $periodId   = $this->getRequest()->getParam('id', 0);
        $period     = Mage::getModel('bs_crypto/period')
            ->setStoreId(Mage::app()->getStore()->getId())
            ->load($periodId);
        if (!$period->getId()) {
            return false;
        }
        return $period;
    }

    /**
     * view investment period action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function viewAction()
    {
        $period = $this->_initPeriod();
        if (!$period) {
            $this->_forward('no-route');
            return;
        }
        Mage::register('current_period', $period);
        $this->loadLayout();
        $this->_initLayoutMessages('catalog/session');
        $this->_initLayoutMessages('customer/session');
        $this->_initLayoutMessages('checkout/session');
        if ($root = $this->getLayout()->getBlock('root')) {
            $root->addBodyClass('crypto-period crypto-period' . $period->getId());
        }
        if (Mage::helper('bs_crypto/period')->getUseBreadcrumbs()) {
            if ($breadcrumbBlock = $this->getLayout()->getBlock('breadcrumbs')) {
                $breadcrumbBlock->addCrumb(
                    'home',
                    [
                        'label'    => Mage::helper('bs_crypto')->__('Home'),
                        'link'     => Mage::getUrl(),
                    ]
                );
                $breadcrumbBlock->addCrumb(
                    'periods',
                    [
                        'label' => Mage::helper('bs_crypto')->__('Investment Periods'),
                        'link'  => Mage::helper('bs_crypto/period')->getPeriodsUrl(),
                    ]
                );
                $breadcrumbBlock->addCrumb(
                    'period',
                    [
                        'label' => $period->getName(),
                        'link'  => '',
                    ]
                );
            }
        }
        $headBlock = $this->getLayout()->getBlock('head');
        if ($headBlock) {
            $headBlock->addLinkRel('canonical', $period->getPeriodUrl());
        }
        $this->renderLayout();
    }
}
