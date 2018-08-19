<?php
/**
 * BS_Crypto extension
 * 
 * @category       BS
 * @package        BS_Crypto
 * @copyright      Copyright (c) 2018
 */
/**
 * Investment Period admin widget controller
 *
 * @category    BS
 * @package     BS_Crypto
 * @author Bui Phong
 */
class BS_Crypto_Adminhtml_Crypto_Period_WidgetController extends Mage_Adminhtml_Controller_Action
{
    /**
     * Chooser Source action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function chooserAction()
    {
        $uniqId = $this->getRequest()->getParam('uniq_id');
        $grid = $this->getLayout()->createBlock(
            'bs_crypto/adminhtml_period_widget_chooser',
            '',
            [
                'id' => $uniqId,
            ]
        );
        $this->getResponse()->setBody($grid->toHtml());
    }
}
