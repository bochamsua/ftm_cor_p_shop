<?php
/**
 * BS_Crypto extension
 * 
 * @category       BS
 * @package        BS_Crypto
 * @copyright      Copyright (c) 2018
 */
/**
 * Investment edit form tab
 *
 * @category    BS
 * @package     BS_Crypto
 * @author Bui Phong
 */
class BS_Crypto_Block_Adminhtml_Investment_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare the form
     *
     * @access protected
     * @return BS_Crypto_Block_Adminhtml_Investment_Edit_Tab_Form
     * @author Bui Phong
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('investment_');
        $form->setFieldNameSuffix('investment');
        $this->setForm($form);
        $fieldset = $form->addFieldset(
            'investment_form',
            ['legend' => Mage::helper('bs_crypto')->__('Investment')]
        );

        $currentObj = Mage::registry('current_investment');

        $customerId = $this->getRequest()->getParam('customer');





        $customers = Mage::getModel('customer/customer')->getCollection()->addNameToSelect();
        if($customerId > 0){
            $customers->addAttributeToFilter('entity_id', $customerId);
        }

        $values = [];

        foreach ($customers as $customer) {
            $values[] = [
                'label' => $customer->getName(). sprintf(" <%s>", $customer->getEmail()),
                'value' => $customer->getId()
            ];
        }



        $fieldset->addField(
            'customer_id',
            'select',
            [
                'label'     => Mage::helper('bs_crypto')->__('Customer'),
                'name'      => 'customer_id',
                'required'  => false,
                'values'    => $values,
                //'after_element_html' => $html
            ]
        );


        $values = Mage::getResourceModel('bs_crypto/period_collection')
            ->toOptionArray();
        array_unshift($values, ['label' => '', 'value' => '']);

        $html = '<a href="{#url}" id="investment_period_id_link" target="_blank"></a>';
        $html .= '<script type="text/javascript">
            function changePeriodIdLink() {
                if ($(\'investment_period_id\').value == \'\') {
                    $(\'investment_period_id_link\').hide();
                } else {
                    $(\'investment_period_id_link\').show();
                    var url = \''.$this->getUrl('adminhtml/crypto_period/edit', ['id'=>'{#id}', 'clear'=>1]).'\';
                    var text = \''.Mage::helper('core')->escapeHtml($this->__('View {#name}')).'\';
                    var realUrl = url.replace(\'{#id}\', $(\'investment_period_id\').value);
                    $(\'investment_period_id_link\').href = realUrl;
                    $(\'investment_period_id_link\').innerHTML = text.replace(\'{#name}\', $(\'investment_period_id\').options[$(\'investment_period_id\').selectedIndex].innerHTML);
                }
            }
            $(\'investment_period_id\').observe(\'change\', changePeriodIdLink);
            changePeriodIdLink();
            </script>';

        $fieldset->addField(
            'period_id',
            'select',
            [
                'label'     => Mage::helper('bs_crypto')->__('Investment Period'),
                'name'      => 'period_id',
                'required'  => false,
                'values'    => $values,
                'after_element_html' => $html
            ]
        );



        $fieldset->addField(
            'init_value',
            'text',
            [
                'label' => Mage::helper('bs_crypto')->__('Investment Value'),
                'name'  => 'init_value',
            'note'	=> $this->__('USDT'),
            'required'  => true,
            'class' => 'required-entry',

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
        $formValues = Mage::registry('current_investment')->getDefaultValues();
        if (!is_array($formValues)) {
            $formValues = [];
        }
        if (Mage::getSingleton('adminhtml/session')->getInvestmentData()) {
            $formValues = array_merge($formValues, Mage::getSingleton('adminhtml/session')->getInvestmentData());
            Mage::getSingleton('adminhtml/session')->setInvestmentData(null);
        } elseif (Mage::registry('current_investment')) {
            $formValues = array_merge($formValues, Mage::registry('current_investment')->getData());
        }
        $form->setValues($formValues);
        return parent::_prepareForm();
    }
}
