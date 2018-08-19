<?php
/**
 * BS_Crypto extension
 * 
 * @category       BS
 * @package        BS_Crypto
 * @copyright      Copyright (c) 2018
 */
/**
 * Investment admin grid block
 *
 * @category    BS
 * @package     BS_Crypto
 * @author Bui Phong
 */
class BS_Crypto_Block_Adminhtml_Investment_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    /**
     * constructor
     *
     * @access public
     * @author Bui Phong
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('investmentGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    /**
     * prepare collection
     *
     * @access protected
     * @return BS_Crypto_Block_Adminhtml_Investment_Grid
     * @author Bui Phong
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('bs_crypto/investment')
            ->getCollection();
        
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * prepare grid collection
     *
     * @access protected
     * @return BS_Crypto_Block_Adminhtml_Investment_Grid
     * @author Bui Phong
     */
    protected function _prepareColumns()
    {

        $this->addColumn(
            'name',
            [
                'header'    => Mage::helper('bs_crypto')->__('Name'),
                'align'     => 'left',
                'index'     => 'name',
            ]
        );

        $customersModel = Mage::getResourceModel('customer/customer_collection')->addNameToSelect();

        $customers = [];

        foreach ($customersModel as $customer) {
            $customers[$customer->getId()] = $customer->getName(). sprintf(" <%s>", $customer->getEmail());
        }

        $this->addColumn(
            'customer_id',
            [
                'header'    => Mage::helper('bs_crypto')->__('Customer'),
                'index'     => 'customer_id',
                'type'      => 'options',
                'options'   => $customers,

            ]
        );

        $this->addColumn(
            'period_id',
            [
                'header'    => Mage::helper('bs_crypto')->__('Investment Period'),
                'index'     => 'period_id',
                'type'      => 'options',
                'options'   => Mage::getResourceModel('bs_crypto/period_collection')
                    ->toOptionHash(),
                'renderer'  => 'bs_crypto/adminhtml_helper_column_renderer_parent',
                'params'    => [
                    'id'    => 'getPeriodId'
                ],
                'base_link' => 'adminhtml/crypto_period/edit'
            ]
        );

        

        $this->addColumn(
            'init_value',
            [
                'header' => Mage::helper('bs_crypto')->__('Investment Value'),
                'index'  => 'init_value',
                'type'=> 'number',

            ]
        );



        /*$this->addColumn(
            'status',
            array(
                'header'  => Mage::helper('bs_crypto')->__('Status'),
                'index'   => 'status',
                'type'    => 'options',
                'options' => array(
                    '1' => Mage::helper('bs_crypto')->__('Enabled'),
                    '0' => Mage::helper('bs_crypto')->__('Disabled'),
                )
            )
        );*/

        //$this->addColumn(
        //    'action',
        //    array(
        //        'header'  =>  Mage::helper('bs_crypto')->__('Action'),
        //        'width'   => '100',
        //        'type'    => 'action',
        //        'getter'  => 'getId',
        //        'actions' => array(
        //            array(
        //                'caption' => Mage::helper('bs_crypto')->__('Edit'),
        //                'url'     => array('base'=> '*/*/edit'),
        //                'field'   => 'id'
        //            )
        //        ),
        //        'filter'    => false,
        //        'is_system' => true,
        //       'sortable'  => false,
        //    )
        //);
        //$this->addExportType('*/*/exportCsv', Mage::helper('bs_crypto')->__('CSV'));
        //$this->addExportType('*/*/exportExcel', Mage::helper('bs_crypto')->__('Excel'));
        //$this->addExportType('*/*/exportXml', Mage::helper('bs_crypto')->__('XML'));
        return parent::_prepareColumns();
    }

    /**
     * prepare mass action
     *
     * @access protected
     * @return BS_Crypto_Block_Adminhtml_Investment_Grid
     * @author Bui Phong
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('investment');

        $isAllowedEdit = Mage::getSingleton('admin/session')->isAllowed("bs_crypto/investment/edit");
        $isAllowedDelete = Mage::getSingleton('admin/session')->isAllowed("bs_crypto/investment/delete");

        if($isAllowedDelete){
            $this->getMassactionBlock()->addItem(
                'delete',
                [
                    'label'=> Mage::helper('bs_crypto')->__('Delete'),
                    'url'  => $this->getUrl('*/*/massDelete'),
                    'confirm'  => Mage::helper('bs_crypto')->__('Are you sure?')
                ]
            );
        }

        if($isAllowedEdit){
            $this->getMassactionBlock()->addItem(
                'status',
                [
                    'label'      => Mage::helper('bs_crypto')->__('Change status'),
                    'url'        => $this->getUrl('*/*/massStatus', ['_current'=>true]),
                    'additional' => [
                        'status' => [
                            'name'   => 'status',
                            'type'   => 'select',
                            'class'  => 'required-entry',
                            'label'  => Mage::helper('bs_crypto')->__('Status'),
                            'values' => [
                                '1' => Mage::helper('bs_crypto')->__('Enabled'),
                                '0' => Mage::helper('bs_crypto')->__('Disabled'),
                            ]
                        ]
                    ]
                ]
            );




        $values = Mage::getResourceModel('bs_crypto/period_collection')->toOptionHash();
        $values = array_reverse($values, true);
        $values[''] = '';
        $values = array_reverse($values, true);
        $this->getMassactionBlock()->addItem(
            'period_id',
            [
                'label'      => Mage::helper('bs_crypto')->__('Change Investment Period'),
                'url'        => $this->getUrl('*/*/massPeriodId', ['_current'=>true]),
                'additional' => [
                    'flag_period_id' => [
                        'name'   => 'flag_period_id',
                        'type'   => 'select',
                        'class'  => 'required-entry',
                        'label'  => Mage::helper('bs_crypto')->__('Investment Period'),
                        'values' => $values
                    ]
                ]
            ]
        );
        }
        return $this;
    }

    /**
     * get the row url
     *
     * @access public
     * @param BS_Crypto_Model_Investment
     * @return string
     * @author Bui Phong
     */
    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', ['id' => $row->getId()]);
    }

    /**
     * get the grid url
     *
     * @access public
     * @return string
     * @author Bui Phong
     */
    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', ['_current'=>true]);
    }

    /**
     * after collection load
     *
     * @access protected
     * @return BS_Crypto_Block_Adminhtml_Investment_Grid
     * @author Bui Phong
     */
    protected function _afterLoadCollection()
    {
        $this->getCollection()->walk('afterLoad');
        parent::_afterLoadCollection();
    }
}
