<?php
/**
 * BS_Crypto extension
 * 
 * @category       BS
 * @package        BS_Crypto
 * @copyright      Copyright (c) 2018
 */
/**
 * Account admin controller
 *
 * @category    BS
 * @package     BS_Crypto
 * @author Bui Phong
 */
class BS_Crypto_Adminhtml_Crypto_AccountController extends BS_Crypto_Controller_Adminhtml_Crypto
{
    /**
     * init the account
     *
     * @access protected
     * @return BS_Crypto_Model_Account
     */
    protected function _initAccount()
    {
        $accountId  = (int) $this->getRequest()->getParam('id');
        $account    = Mage::getModel('bs_crypto/account');
        if ($accountId) {
            $account->load($accountId);
        }
        Mage::register('current_account', $account);
        return $account;
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
             ->_title(Mage::helper('bs_crypto')->__('Accounts'));
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
     * edit account - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function editAction()
    {
        $accountId    = $this->getRequest()->getParam('id');
        $account      = $this->_initAccount();
        if ($accountId && !$account->getId()) {
            $this->_getSession()->addError(
                Mage::helper('bs_crypto')->__('This account no longer exists.')
            );
            $this->_redirect('*/*/');
            return;
        }
        $data = Mage::getSingleton('adminhtml/session')->getAccountData(true);
        if (!empty($data)) {
            $account->setData($data);
        }
        Mage::register('account_data', $account);
        $this->loadLayout();
        $this->_title(Mage::helper('bs_crypto')->__('Crypto'))
             ->_title(Mage::helper('bs_crypto')->__('Accounts'));
        if ($account->getId()) {
            $this->_title($account->getName());
        } else {
            $this->_title(Mage::helper('bs_crypto')->__('Add account'));
        }
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
        $this->renderLayout();
    }

    /**
     * new account action
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
     * save account - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost('account')) {
            try {
                $account = $this->_initAccount();
                $account->addData($data);
                $account->save();
                $add = '';
                if($this->getRequest()->getParam('popup')){
                    $add = '<script>window.opener.'.$this->getJsObjectName().'.reload(); window.close()</script>';
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_crypto')->__('Account was successfully saved. %s', $add)
                );
                Mage::getSingleton('adminhtml/session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', ['id' => $account->getId()]);
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setAccountData($data);
                $this->_redirect('*/*/edit', ['id' => $this->getRequest()->getParam('id')]);
                return;
            } catch (Exception $e) {
                Mage::logException($e);
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_crypto')->__('There was a problem saving the account.')
                );
                Mage::getSingleton('adminhtml/session')->setAccountData($data);
                $this->_redirect('*/*/edit', ['id' => $this->getRequest()->getParam('id')]);
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_crypto')->__('Unable to find account to save.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * delete account - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function deleteAction()
    {
        if ( $this->getRequest()->getParam('id') > 0) {
            try {
                $account = Mage::getModel('bs_crypto/account');
                $account->setId($this->getRequest()->getParam('id'))->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_crypto')->__('Account was successfully deleted.')
                );
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', ['id' => $this->getRequest()->getParam('id')]);
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_crypto')->__('There was an error deleting account.')
                );
                $this->_redirect('*/*/edit', ['id' => $this->getRequest()->getParam('id')]);
                Mage::logException($e);
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_crypto')->__('Could not find account to delete.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * mass delete account - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massDeleteAction()
    {
        $accountIds = $this->getRequest()->getParam('account');
        if (!is_array($accountIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_crypto')->__('Please select accounts to delete.')
            );
        } else {
            try {
                foreach ($accountIds as $accountId) {
                    $account = Mage::getModel('bs_crypto/account');
                    $account->setId($accountId)->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_crypto')->__('Total of %d accounts were successfully deleted.', count($accountIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_crypto')->__('There was an error deleting accounts.')
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
        $accountIds = $this->getRequest()->getParam('account');
        if (!is_array($accountIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_crypto')->__('Please select accounts.')
            );
        } else {
            try {
                foreach ($accountIds as $accountId) {
                $account = Mage::getSingleton('bs_crypto/account')->load($accountId)
                            ->setStatus($this->getRequest()->getParam('status'))
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d accounts were successfully updated.', count($accountIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_crypto')->__('There was an error updating accounts.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * mass Exchange change - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massExchangeAction()
    {
        $accountIds = $this->getRequest()->getParam('account');
        if (!is_array($accountIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_crypto')->__('Please select accounts.')
            );
        } else {
            try {
                foreach ($accountIds as $accountId) {
                $account = Mage::getSingleton('bs_crypto/account')->load($accountId)
                    ->setExchange($this->getRequest()->getParam('flag_exchange'))
                    ->setIsMassupdate(true)
                    ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d accounts were successfully updated.', count($accountIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_crypto')->__('There was an error updating accounts.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * mass Read Only? change - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massReadOnlyAction()
    {
        $accountIds = $this->getRequest()->getParam('account');
        if (!is_array($accountIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_crypto')->__('Please select accounts.')
            );
        } else {
            try {
                foreach ($accountIds as $accountId) {
                $account = Mage::getSingleton('bs_crypto/account')->load($accountId)
                    ->setReadOnly($this->getRequest()->getParam('flag_read_only'))
                    ->setIsMassupdate(true)
                    ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d accounts were successfully updated.', count($accountIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_crypto')->__('There was an error updating accounts.')
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
        $fileName   = 'account.csv';
        $content    = $this->getLayout()->createBlock('bs_crypto/adminhtml_account_grid')
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
        $fileName   = 'account.xls';
        $content    = $this->getLayout()->createBlock('bs_crypto/adminhtml_account_grid')
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
        $fileName   = 'account.xml';
        $content    = $this->getLayout()->createBlock('bs_crypto/adminhtml_account_grid')
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
        return Mage::getSingleton('admin/session')->isAllowed('bs_crypto/account');
    }
}
