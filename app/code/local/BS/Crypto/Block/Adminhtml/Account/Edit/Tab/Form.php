<?php
/**
 * BS_Crypto extension
 * 
 * @category       BS
 * @package        BS_Crypto
 * @copyright      Copyright (c) 2018
 */
/**
 * Account edit form tab
 *
 * @category    BS
 * @package     BS_Crypto
 * @author Bui Phong
 */
class BS_Crypto_Block_Adminhtml_Account_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare the form
     *
     * @access protected
     * @return BS_Crypto_Block_Adminhtml_Account_Edit_Tab_Form
     * @author Bui Phong
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('account_');
        $form->setFieldNameSuffix('account');
        $this->setForm($form);
        $fieldset = $form->addFieldset(
            'account_form',
            ['legend' => Mage::helper('bs_crypto')->__('Account')]
        );

        $currentObj = Mage::registry('current_account');

        $fieldset->addField(
            'name',
            'text',
            [
                'label' => Mage::helper('bs_crypto')->__('Account Name'),
                'name'  => 'name',
            'required'  => true,
            'class' => 'required-entry',

           ]
        );

        $fieldset->addField(
            'exchange',
            'select',
            [
                'label' => Mage::helper('bs_crypto')->__('Exchange'),
                'name'  => 'exchange',
            'required'  => true,
            'class' => 'required-entry',

            'values'=> Mage::getModel('bs_crypto/account_attribute_source_exchange')->getAllOptions(true),
           ]
        );

        $fieldset->addField(
            'api_key',
            'text',
            [
                'label' => Mage::helper('bs_crypto')->__('API Key'),
                'name'  => 'api_key',
            'required'  => true,
            'class' => 'required-entry',

           ]
        );

        $fieldset->addField(
            'api_secret',
            'text',
            [
                'label' => Mage::helper('bs_crypto')->__('API Secret'),
                'name'  => 'api_secret',
                'required'  => true,
                'class' => 'required-entry',
                'note'  => Mage::helper('bs_crypto')->__('After saving, this value will be hidden for security reason'),



           ]
        );

       /* $fieldset->addField(
            'read_only',
            'select',
            [
                'label' => Mage::helper('bs_crypto')->__('Read Only?'),
                'name'  => 'read_only',

            'values'=> array(
                array(
                    'value' => 1,
                    'label' => Mage::helper('bs_crypto')->__('Yes'),
                ),
                array(
                    'value' => 0,
                    'label' => Mage::helper('bs_crypto')->__('No'),
                ),
            ),
           ]
        );*/

        $fieldset->addField(
            'note',
            'text',
            [
                'label' => Mage::helper('bs_crypto')->__('Note'),
                'name'  => 'note',

           ]
        );
        /*$fieldset->addField(
            'status',
            'select',
            array(
                'label'  => Mage::helper('bs_crypto')->__('Status'),
                'name'   => 'status',
                'values' => array(
                    array(
                        'value' => 1,
                        'label' => Mage::helper('bs_crypto')->__('Enabled'),
                    ),
                    array(
                        'value' => 0,
                        'label' => Mage::helper('bs_crypto')->__('Disabled'),
                    ),
                ),
            )
        );*/
        $formValues = Mage::registry('current_account')->getDefaultValues();
        if (!is_array($formValues)) {
            $formValues = [];
        }
        if (Mage::getSingleton('adminhtml/session')->getAccountData()) {
            $formValues = array_merge($formValues, Mage::getSingleton('adminhtml/session')->getAccountData());
            Mage::getSingleton('adminhtml/session')->setAccountData(null);
        } elseif (Mage::registry('current_account')) {
            $formValues = array_merge($formValues, Mage::registry('current_account')->getData());
        }
        $form->setValues($formValues);
        return parent::_prepareForm();
    }

    protected function _afterToHtml($html)
    {
        $currentId = 0;
        if(Mage::registry('current_account')->getId()){
            $currentId = Mage::registry('current_account')->getId();
        }

        $id = $this->getForm()->getHtmlIdPrefix();
        $html .= "<script>";

        if($currentId){
            $html .= "Event.observe(document, \"dom:loaded\", function(e) {
                          $('".$id."api_secret').value = '*****';
                          
                    });
                    
              
                    ";
        }

        $html .= "
                    
                     
                </script>";
        return parent::_afterToHtml($html);
    }
}
