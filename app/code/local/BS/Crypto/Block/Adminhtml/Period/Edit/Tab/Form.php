<?php
/**
 * BS_Crypto extension
 * 
 * @category       BS
 * @package        BS_Crypto
 * @copyright      Copyright (c) 2018
 */
/**
 * Investment Period edit form tab
 *
 * @category    BS
 * @package     BS_Crypto
 * @author Bui Phong
 */
class BS_Crypto_Block_Adminhtml_Period_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare the form
     *
     * @access protected
     * @return BS_Crypto_Block_Adminhtml_Period_Edit_Tab_Form
     * @author Bui Phong
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('period_');
        $form->setFieldNameSuffix('period');
        $this->setForm($form);
        $fieldset = $form->addFieldset(
            'period_form',
            ['legend' => Mage::helper('bs_crypto')->__('Investment Period')]
        );

        $currentObj = Mage::registry('current_period');
        $values = Mage::getResourceModel('bs_crypto/account_collection')
            ->toOptionArray();
        array_unshift($values, ['label' => '', 'value' => '']);

        $html = '<a href="{#url}" id="period_account_id_link" target="_blank"></a>';
        $html .= '<script type="text/javascript">
            function changeAccountIdLink() {
                if ($(\'period_account_id\').value == \'\') {
                    $(\'period_account_id_link\').hide();
                } else {
                    $(\'period_account_id_link\').show();
                    var url = \''.$this->getUrl('adminhtml/crypto_account/edit', ['id'=>'{#id}', 'clear'=>1]).'\';
                    var text = \''.Mage::helper('core')->escapeHtml($this->__('View {#name}')).'\';
                    var realUrl = url.replace(\'{#id}\', $(\'period_account_id\').value);
                    $(\'period_account_id_link\').href = realUrl;
                    $(\'period_account_id_link\').innerHTML = text.replace(\'{#name}\', $(\'period_account_id\').options[$(\'period_account_id\').selectedIndex].innerHTML);
                }
            }
            $(\'period_account_id\').observe(\'change\', changeAccountIdLink);
            changeAccountIdLink();
            </script>';

        $fieldset->addField(
            'account_id',
            'select',
            [
                'label'     => Mage::helper('bs_crypto')->__('Account'),
                'name'      => 'account_id',
                'required'  => false,
                'values'    => $values,
                'after_element_html' => $html
            ]
        );

        $fieldset->addField(
            'name',
            'text',
            [
                'label' => Mage::helper('bs_crypto')->__('Name'),
                'name'  => 'name',
            'required'  => true,
            'class' => 'required-entry',

           ]
        );

        $fieldset->addField(
            'code',
            'text',
            [
                'label' => Mage::helper('bs_crypto')->__('Code'),
                'name'  => 'code',

           ]
        );

        $fieldset->addField(
            'start_date',
            'date',
            [
                'label' => Mage::helper('bs_crypto')->__('Start Date'),
                'name'  => 'start_date',
            'required'  => true,
            'class' => 'required-entry',

            'image' => $this->getSkinUrl('images/grid-cal.gif'),
            'format'  => Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT),
           ]
        );

        $fieldset->addField(
            'end_date',
            'date',
            [
                'label' => Mage::helper('bs_crypto')->__('End Date'),
                'name'  => 'end_date',
            'required'  => true,
            'class' => 'required-entry',

            'image' => $this->getSkinUrl('images/grid-cal.gif'),
            'format'  => Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT),
           ]
        );

        $fieldset->addField(
            'total',
            'text',
            [
                'label' => Mage::helper('bs_crypto')->__('Total Investment'),
                'name'  => 'total',
            'note'	=> $this->__('USDT'),
            'required'  => true,
            'class' => 'required-entry',

           ]
        );

        $fieldset->addField(
            'total_final',
            'text',
            [
                'label' => Mage::helper('bs_crypto')->__('Final Total Amount'),
                'name'  => 'total_final',

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
        $formValues = Mage::registry('current_period')->getDefaultValues();
        if (!is_array($formValues)) {
            $formValues = [];
        }
        if (Mage::getSingleton('adminhtml/session')->getPeriodData()) {
            $formValues = array_merge($formValues, Mage::getSingleton('adminhtml/session')->getPeriodData());
            Mage::getSingleton('adminhtml/session')->setPeriodData(null);
        } elseif (Mage::registry('current_period')) {
            $formValues = array_merge($formValues, Mage::registry('current_period')->getData());
        }
        $form->setValues($formValues);
        return parent::_prepareForm();
    }
}
