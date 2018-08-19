<?php
/**
 * BS_Crypto extension
 * 
 * @category       BS
 * @package        BS_Crypto
 * @copyright      Copyright (c) 2018
 */
/**
 * Investment admin widget chooser
 *
 * @category    BS
 * @package     BS_Crypto
 * @author Bui Phong
 */

class BS_Crypto_Block_Adminhtml_Investment_Widget_Chooser extends Mage_Adminhtml_Block_Widget_Grid
{
    /**
     * Block construction, prepare grid params
     *
     * @access public
     * @param array $arguments Object data
     * @return void
     * @author Bui Phong
     */
    public function __construct($arguments=[])
    {
        parent::__construct($arguments);
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('DESC');
        $this->setUseAjax(true);
        $this->setDefaultFilter(['chooser_status' => '1']);
    }

    /**
     * Prepare chooser element HTML
     *
     * @access public
     * @param Varien_Data_Form_Element_Abstract $element Form Element
     * @return Varien_Data_Form_Element_Abstract
     * @author Bui Phong
     */
    public function prepareElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
        $uniqId = Mage::helper('core')->uniqHash($element->getId());
        $sourceUrl = $this->getUrl(
            'bs_crypto/adminhtml_crypto_investment_widget/chooser',
            ['uniq_id' => $uniqId]
        );
        $chooser = $this->getLayout()->createBlock('widget/adminhtml_widget_chooser')
            ->setElement($element)
            ->setTranslationHelper($this->getTranslationHelper())
            ->setConfig($this->getConfig())
            ->setFieldsetId($this->getFieldsetId())
            ->setSourceUrl($sourceUrl)
            ->setUniqId($uniqId);
        if ($element->getValue()) {
            $investment = Mage::getModel('bs_crypto/investment')->load($element->getValue());
            if ($investment->getId()) {
                $chooser->setLabel($investment->getName());
            }
        }
        $element->setData('after_element_html', $chooser->toHtml());
        return $element;
    }

    /**
     * Grid Row JS Callback
     *
     * @access public
     * @return string
     * @author Bui Phong
     */
    public function getRowClickCallback()
    {
        $chooserJsObject = $this->getId();
        $js = '
            function (grid, event) {
                var trElement = Event.findElement(event, "tr");
                var investmentId = trElement.down("td").innerHTML.replace(/^\s+|\s+$/g,"");
                var investmentTitle = trElement.down("td").next().innerHTML;
                '.$chooserJsObject.'.setElementValue(investmentId);
                '.$chooserJsObject.'.setElementLabel(investmentTitle);
                '.$chooserJsObject.'.close();
            }
        ';
        return $js;
    }

    /**
     * Prepare a static blocks collection
     *
     * @access protected
     * @return BS_Crypto_Block_Adminhtml_Investment_Widget_Chooser
     * @author Bui Phong
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('bs_crypto/investment')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * Prepare columns for the a grid
     *
     * @access protected
     * @return BS_Crypto_Block_Adminhtml_Investment_Widget_Chooser
     * @author Bui Phong
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'chooser_id',
            [
                'header' => Mage::helper('bs_crypto')->__('Id'),
                'align'  => 'right',
                'index'  => 'entity_id',
                'type'   => 'number',
                'width'  => 50
            ]
        );

        $this->addColumn(
            'chooser_name',
            [
                'header' => Mage::helper('bs_crypto')->__('Name'),
                'align'  => 'left',
                'index'  => 'name',
            ]
        );
        /*$this->addColumn(
            'chooser_status',
            array(
                'header'  => Mage::helper('bs_crypto')->__('Status'),
                'index'   => 'status',
                'type'    => 'options',
                'options' => array(
                    0 => Mage::helper('bs_crypto')->__('Disabled'),
                    1 => Mage::helper('bs_crypto')->__('Enabled')
                ),
            )
        );*/
        return parent::_prepareColumns();
    }

    /**
     * get url for grid
     *
     * @access public
     * @return string
     * @author Bui Phong
     */
    public function getGridUrl()
    {
        return $this->getUrl(
            'adminhtml/crypto_investment_widget/chooser',
            ['_current' => true]
        );
    }

    /**
     * after collection load
     *
     * @access protected
     * @return BS_Crypto_Block_Adminhtml_Investment_Widget_Chooser
     * @author Bui Phong
     */
    protected function _afterLoadCollection()
    {
        $this->getCollection()->walk('afterLoad');
        parent::_afterLoadCollection();
    }
}
