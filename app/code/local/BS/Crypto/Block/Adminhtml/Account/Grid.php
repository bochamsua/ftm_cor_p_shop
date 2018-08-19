<?php
/**
 * BS_Crypto extension
 * 
 * @category       BS
 * @package        BS_Crypto
 * @copyright      Copyright (c) 2018
 */
/**
 * Account admin grid block
 *
 * @category    BS
 * @package     BS_Crypto
 * @author Bui Phong
 */
class BS_Crypto_Block_Adminhtml_Account_Grid extends Mage_Adminhtml_Block_Widget_Grid
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
        $this->setId('accountGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    /**
     * prepare collection
     *
     * @access protected
     * @return BS_Crypto_Block_Adminhtml_Account_Grid
     * @author Bui Phong
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('bs_crypto/account')
            ->getCollection();
        
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * prepare grid collection
     *
     * @access protected
     * @return BS_Crypto_Block_Adminhtml_Account_Grid
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
                'header'    => Mage::helper('bs_crypto')->__('Account Name'),
                'align'     => 'left',
                'index'     => 'name',
            ]
        );
        

        $this->addColumn(
            'exchange',
            [
                'header' => Mage::helper('bs_crypto')->__('Exchange'),
                'index'  => 'exchange',
                'type'  => 'options',
                'options' => Mage::helper('bs_crypto')->convertOptions(
                    Mage::getModel('bs_crypto/account_attribute_source_exchange')->getAllOptions(false)
                )

            ]
        );
        $this->addColumn(
            'api_key',
            [
                'header' => Mage::helper('bs_crypto')->__('API Key'),
                'index'  => 'api_key',
                'type'=> 'text',

            ]
        );

        /*$this->addColumn(
            'read_only',
            [
                'header' => Mage::helper('bs_crypto')->__('Read Only?'),
                'index'  => 'read_only',
                'type'    => 'options',
                    'options'    => array(
                    '1' => Mage::helper('bs_crypto')->__('Yes'),
                    '0' => Mage::helper('bs_crypto')->__('No'),
                )

            ]
        );*/
        $this->addColumn(
            'note',
            [
                'header' => Mage::helper('bs_crypto')->__('Note'),
                'index'  => 'note',
                'type'=> 'text',

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
     * @return BS_Crypto_Block_Adminhtml_Account_Grid
     * @author Bui Phong
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('account');

        $isAllowedEdit = Mage::getSingleton('admin/session')->isAllowed("bs_crypto/account/edit");
        $isAllowedDelete = Mage::getSingleton('admin/session')->isAllowed("bs_crypto/account/delete");

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




        $this->getMassactionBlock()->addItem(
            'exchange',
            [
                'label'      => Mage::helper('bs_crypto')->__('Change Exchange'),
                'url'        => $this->getUrl('*/*/massExchange', ['_current'=>true]),
                'additional' => [
                    'flag_exchange' => [
                        'name'   => 'flag_exchange',
                        'type'   => 'select',
                        'class'  => 'required-entry',
                        'label'  => Mage::helper('bs_crypto')->__('Exchange'),
                        'values' => Mage::getModel('bs_crypto/account_attribute_source_exchange')
                            ->getAllOptions(true),

                    ]
                ]
            ]
        );
        $this->getMassactionBlock()->addItem(
            'read_only',
            [
                'label'      => Mage::helper('bs_crypto')->__('Change Read Only?'),
                'url'        => $this->getUrl('*/*/massReadOnly', ['_current'=>true]),
                'additional' => [
                    'flag_read_only' => [
                        'name'   => 'flag_read_only',
                        'type'   => 'select',
                        'class'  => 'required-entry',
                        'label'  => Mage::helper('bs_crypto')->__('Read Only?'),
                        'values' => array(
                                '1' => Mage::helper('bs_crypto')->__('Yes'),
                                '0' => Mage::helper('bs_crypto')->__('No'),
                            )

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
     * @param BS_Crypto_Model_Account
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
     * @return BS_Crypto_Block_Adminhtml_Account_Grid
     * @author Bui Phong
     */
    protected function _afterLoadCollection()
    {
        $this->getCollection()->walk('afterLoad');
        parent::_afterLoadCollection();
    }
}
