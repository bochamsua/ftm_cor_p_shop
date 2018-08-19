<?php
/**
 * BS_Crypto extension
 * 
 * @category       BS
 * @package        BS_Crypto
 * @copyright      Copyright (c) 2018
 */
/**
 * Investment Period admin grid block
 *
 * @category    BS
 * @package     BS_Crypto
 * @author Bui Phong
 */
class BS_Crypto_Block_Adminhtml_Period_Grid extends Mage_Adminhtml_Block_Widget_Grid
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
        $this->setId('periodGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    /**
     * prepare collection
     *
     * @access protected
     * @return BS_Crypto_Block_Adminhtml_Period_Grid
     * @author Bui Phong
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('bs_crypto/period')
            ->getCollection();
        
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * prepare grid collection
     *
     * @access protected
     * @return BS_Crypto_Block_Adminhtml_Period_Grid
     * @author Bui Phong
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'entity_id',
            [
                'header' => Mage::helper('bs_crypto')->__('Id'),
                'index'  => 'entity_id',
                'type'   => 'number'
            ]
        );

        $this->addColumn(
            'name',
            [
                'header'    => Mage::helper('bs_crypto')->__('Name'),
                'align'     => 'left',
                'index'     => 'name',
            ]
        );
        

        $this->addColumn(
            'code',
            [
                'header' => Mage::helper('bs_crypto')->__('Code'),
                'index'  => 'code',
                'type'=> 'text',

            ]
        );

        $this->addColumn(
            'account_id',
            [
                'header'    => Mage::helper('bs_crypto')->__('Account'),
                'index'     => 'account_id',
                'type'      => 'options',
                'options'   => Mage::getResourceModel('bs_crypto/account_collection')
                    ->toOptionHash(),
                'renderer'  => 'bs_crypto/adminhtml_helper_column_renderer_parent',
                'params'    => [
                    'id'    => 'getAccountId'
                ],
                'base_link' => 'adminhtml/crypto_account/edit'
            ]
        );

        $this->addColumn(
            'start_date',
            [
                'header' => Mage::helper('bs_crypto')->__('Start Date'),
                'index'  => 'start_date',
                'type'=> 'date',

            ]
        );
        $this->addColumn(
            'end_date',
            [
                'header' => Mage::helper('bs_crypto')->__('End Date'),
                'index'  => 'end_date',
                'type'=> 'date',

            ]
        );
        $this->addColumn(
            'total',
            [
                'header' => Mage::helper('bs_crypto')->__('Total Investment'),
                'index'  => 'total',
                'type'=> 'number',

            ]
        );
        $this->addColumn(
            'total_final',
            [
                'header' => Mage::helper('bs_crypto')->__('Final Total Amount'),
                'index'  => 'total_final',
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
     * @return BS_Crypto_Block_Adminhtml_Period_Grid
     * @author Bui Phong
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('period');

        $isAllowedEdit = Mage::getSingleton('admin/session')->isAllowed("bs_crypto/period/edit");
        $isAllowedDelete = Mage::getSingleton('admin/session')->isAllowed("bs_crypto/period/delete");

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




        $values = Mage::getResourceModel('bs_crypto/account_collection')->toOptionHash();
        $values = array_reverse($values, true);
        $values[''] = '';
        $values = array_reverse($values, true);
        $this->getMassactionBlock()->addItem(
            'account_id',
            [
                'label'      => Mage::helper('bs_crypto')->__('Change Account'),
                'url'        => $this->getUrl('*/*/massAccountId', ['_current'=>true]),
                'additional' => [
                    'flag_account_id' => [
                        'name'   => 'flag_account_id',
                        'type'   => 'select',
                        'class'  => 'required-entry',
                        'label'  => Mage::helper('bs_crypto')->__('Account'),
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
     * @param BS_Crypto_Model_Period
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
     * @return BS_Crypto_Block_Adminhtml_Period_Grid
     * @author Bui Phong
     */
    protected function _afterLoadCollection()
    {
        $this->getCollection()->walk('afterLoad');
        parent::_afterLoadCollection();
    }
}
