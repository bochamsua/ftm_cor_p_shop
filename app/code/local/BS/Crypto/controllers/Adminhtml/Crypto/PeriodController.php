<?php
/**
 * BS_Crypto extension
 * 
 * @category       BS
 * @package        BS_Crypto
 * @copyright      Copyright (c) 2018
 */
/**
 * Investment Period admin controller
 *
 * @category    BS
 * @package     BS_Crypto
 * @author Bui Phong
 */
class BS_Crypto_Adminhtml_Crypto_PeriodController extends BS_Crypto_Controller_Adminhtml_Crypto
{
    /**
     * init the investment period
     *
     * @access protected
     * @return BS_Crypto_Model_Period
     */
    protected function _initPeriod()
    {
        $periodId  = (int) $this->getRequest()->getParam('id');
        $period    = Mage::getModel('bs_crypto/period');
        if ($periodId) {
            $period->load($periodId);
        }
        Mage::register('current_period', $period);
        return $period;
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
             ->_title(Mage::helper('bs_crypto')->__('Investment Periods'));
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
     * edit investment period - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function editAction()
    {
        $periodId    = $this->getRequest()->getParam('id');
        $period      = $this->_initPeriod();
        if ($periodId && !$period->getId()) {
            $this->_getSession()->addError(
                Mage::helper('bs_crypto')->__('This investment period no longer exists.')
            );
            $this->_redirect('*/*/');
            return;
        }
        $data = Mage::getSingleton('adminhtml/session')->getPeriodData(true);
        if (!empty($data)) {
            $period->setData($data);
        }
        Mage::register('period_data', $period);
        $this->loadLayout();
        $this->_title(Mage::helper('bs_crypto')->__('Crypto'))
             ->_title(Mage::helper('bs_crypto')->__('Investment Periods'));
        if ($period->getId()) {
            $this->_title($period->getName());
        } else {
            $this->_title(Mage::helper('bs_crypto')->__('Add investment period'));
        }
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
        $this->renderLayout();
    }

    /**
     * new investment period action
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
     * save investment period - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost('period')) {
            try {
                $data = $this->_filterDates($data, array('start_date' ,'end_date'));
                $period = $this->_initPeriod();
                $period->addData($data);
                $period->save();
                $add = '';
                if($this->getRequest()->getParam('popup')){
                    $add = '<script>window.opener.'.$this->getJsObjectName().'.reload(); window.close()</script>';
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_crypto')->__('Investment Period was successfully saved. %s', $add)
                );
                Mage::getSingleton('adminhtml/session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', ['id' => $period->getId()]);
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setPeriodData($data);
                $this->_redirect('*/*/edit', ['id' => $this->getRequest()->getParam('id')]);
                return;
            } catch (Exception $e) {
                Mage::logException($e);
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_crypto')->__('There was a problem saving the investment period.')
                );
                Mage::getSingleton('adminhtml/session')->setPeriodData($data);
                $this->_redirect('*/*/edit', ['id' => $this->getRequest()->getParam('id')]);
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_crypto')->__('Unable to find investment period to save.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * delete investment period - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function deleteAction()
    {
        if ( $this->getRequest()->getParam('id') > 0) {
            try {
                $period = Mage::getModel('bs_crypto/period');
                $period->setId($this->getRequest()->getParam('id'))->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_crypto')->__('Investment Period was successfully deleted.')
                );
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', ['id' => $this->getRequest()->getParam('id')]);
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_crypto')->__('There was an error deleting investment period.')
                );
                $this->_redirect('*/*/edit', ['id' => $this->getRequest()->getParam('id')]);
                Mage::logException($e);
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_crypto')->__('Could not find investment period to delete.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * mass delete investment period - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massDeleteAction()
    {
        $periodIds = $this->getRequest()->getParam('period');
        if (!is_array($periodIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_crypto')->__('Please select investment periods to delete.')
            );
        } else {
            try {
                foreach ($periodIds as $periodId) {
                    $period = Mage::getModel('bs_crypto/period');
                    $period->setId($periodId)->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_crypto')->__('Total of %d investment periods were successfully deleted.', count($periodIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_crypto')->__('There was an error deleting investment periods.')
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
        $periodIds = $this->getRequest()->getParam('period');
        if (!is_array($periodIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_crypto')->__('Please select investment periods.')
            );
        } else {
            try {
                foreach ($periodIds as $periodId) {
                $period = Mage::getSingleton('bs_crypto/period')->load($periodId)
                            ->setStatus($this->getRequest()->getParam('status'))
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d investment periods were successfully updated.', count($periodIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_crypto')->__('There was an error updating investment periods.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * mass account change - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massAccountIdAction()
    {
        $periodIds = $this->getRequest()->getParam('period');
        if (!is_array($periodIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_crypto')->__('Please select investment periods.')
            );
        } else {
            try {
                foreach ($periodIds as $periodId) {
                $period = Mage::getSingleton('bs_crypto/period')->load($periodId)
                    ->setAccountId($this->getRequest()->getParam('flag_account_id'))
                    ->setIsMassupdate(true)
                    ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d investment periods were successfully updated.', count($periodIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_crypto')->__('There was an error updating investment periods.')
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
        $fileName   = 'period.csv';
        $content    = $this->getLayout()->createBlock('bs_crypto/adminhtml_period_grid')
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
        $fileName   = 'period.xls';
        $content    = $this->getLayout()->createBlock('bs_crypto/adminhtml_period_grid')
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
        $fileName   = 'period.xml';
        $content    = $this->getLayout()->createBlock('bs_crypto/adminhtml_period_grid')
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
        return Mage::getSingleton('admin/session')->isAllowed('bs_crypto/period');
    }
}
