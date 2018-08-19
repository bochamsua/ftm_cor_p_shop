<?php
/**
 * BS_Crypto extension
 * 
 * @category       BS
 * @package        BS_Crypto
 * @copyright      Copyright (c) 2018
 */
/**
 * Investment admin controller
 *
 * @category    BS
 * @package     BS_Crypto
 * @author Bui Phong
 */
class BS_Crypto_Adminhtml_Crypto_InvestmentController extends BS_Crypto_Controller_Adminhtml_Crypto
{
    /**
     * init the investment
     *
     * @access protected
     * @return BS_Crypto_Model_Investment
     */
    protected function _initInvestment()
    {
        $investmentId  = (int) $this->getRequest()->getParam('id');
        $investment    = Mage::getModel('bs_crypto/investment');
        if ($investmentId) {
            $investment->load($investmentId);
        }
        Mage::register('current_investment', $investment);
        return $investment;
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
        $this->_title(Mage::helper('bs_crypto')->__('Crypto'))
             ->_title(Mage::helper('bs_crypto')->__('Investments'));
        $this->renderLayout();
    }

    /**
     * grid action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function gridAction()
    {
        $this->loadLayout()->renderLayout();
    }

    /**
     * edit investment - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function editAction()
    {
        $investmentId    = $this->getRequest()->getParam('id');
        $investment      = $this->_initInvestment();
        if ($investmentId && !$investment->getId()) {
            $this->_getSession()->addError(
                Mage::helper('bs_crypto')->__('This investment no longer exists.')
            );
            $this->_redirect('*/*/');
            return;
        }
        $data = Mage::getSingleton('adminhtml/session')->getInvestmentData(true);
        if (!empty($data)) {
            $investment->setData($data);
        }
        Mage::register('investment_data', $investment);
        $this->loadLayout();
        $this->_title(Mage::helper('bs_crypto')->__('Crypto'))
             ->_title(Mage::helper('bs_crypto')->__('Investments'));
        if ($investment->getId()) {
            $this->_title($investment->getName());
        } else {
            $this->_title(Mage::helper('bs_crypto')->__('Add investment'));
        }
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
        $this->renderLayout();
    }

    /**
     * new investment action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function newAction()
    {
        $this->_forward('edit');
    }

    /**
     * save investment - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost('investment')) {
            try {
                $investment = $this->_initInvestment();
                $investment->addData($data);
                $investment->save();
                $add = '';
                if($this->getRequest()->getParam('popup')){
                    $add = '<script>window.opener.'.$this->getJsObjectName().'.reload(); window.close()</script>';
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_crypto')->__('Investment was successfully saved. %s', $add)
                );
                Mage::getSingleton('adminhtml/session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', ['id' => $investment->getId()]);
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setInvestmentData($data);
                $this->_redirect('*/*/edit', ['id' => $this->getRequest()->getParam('id')]);
                return;
            } catch (Exception $e) {
                Mage::logException($e);
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_crypto')->__('There was a problem saving the investment.')
                );
                Mage::getSingleton('adminhtml/session')->setInvestmentData($data);
                $this->_redirect('*/*/edit', ['id' => $this->getRequest()->getParam('id')]);
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_crypto')->__('Unable to find investment to save.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * delete investment - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function deleteAction()
    {
        if ( $this->getRequest()->getParam('id') > 0) {
            try {
                $investment = Mage::getModel('bs_crypto/investment');
                $investment->setId($this->getRequest()->getParam('id'))->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_crypto')->__('Investment was successfully deleted.')
                );
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', ['id' => $this->getRequest()->getParam('id')]);
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_crypto')->__('There was an error deleting investment.')
                );
                $this->_redirect('*/*/edit', ['id' => $this->getRequest()->getParam('id')]);
                Mage::logException($e);
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_crypto')->__('Could not find investment to delete.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * mass delete investment - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massDeleteAction()
    {
        $investmentIds = $this->getRequest()->getParam('investment');
        if (!is_array($investmentIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_crypto')->__('Please select investments to delete.')
            );
        } else {
            try {
                foreach ($investmentIds as $investmentId) {
                    $investment = Mage::getModel('bs_crypto/investment');
                    $investment->setId($investmentId)->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_crypto')->__('Total of %d investments were successfully deleted.', count($investmentIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_crypto')->__('There was an error deleting investments.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * mass status change - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massStatusAction()
    {
        $investmentIds = $this->getRequest()->getParam('investment');
        if (!is_array($investmentIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_crypto')->__('Please select investments.')
            );
        } else {
            try {
                foreach ($investmentIds as $investmentId) {
                $investment = Mage::getSingleton('bs_crypto/investment')->load($investmentId)
                            ->setStatus($this->getRequest()->getParam('status'))
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d investments were successfully updated.', count($investmentIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_crypto')->__('There was an error updating investments.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * mass investment period change - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massPeriodIdAction()
    {
        $investmentIds = $this->getRequest()->getParam('investment');
        if (!is_array($investmentIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_crypto')->__('Please select investments.')
            );
        } else {
            try {
                foreach ($investmentIds as $investmentId) {
                $investment = Mage::getSingleton('bs_crypto/investment')->load($investmentId)
                    ->setPeriodId($this->getRequest()->getParam('flag_period_id'))
                    ->setIsMassupdate(true)
                    ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d investments were successfully updated.', count($investmentIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_crypto')->__('There was an error updating investments.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * export as csv - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function exportCsvAction()
    {
        $fileName   = 'investment.csv';
        $content    = $this->getLayout()->createBlock('bs_crypto/adminhtml_investment_grid')
            ->getCsv();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * export as MsExcel - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function exportExcelAction()
    {
        $fileName   = 'investment.xls';
        $content    = $this->getLayout()->createBlock('bs_crypto/adminhtml_investment_grid')
            ->getExcelFile();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * export as xml - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function exportXmlAction()
    {
        $fileName   = 'investment.xml';
        $content    = $this->getLayout()->createBlock('bs_crypto/adminhtml_investment_grid')
            ->getXml();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * Check if admin has permissions to visit related pages
     *
     * @access protected
     * @return boolean
     * @author Bui Phong
     */
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('bs_crypto/investment');
    }
}
