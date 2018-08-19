<?php
/**
 * BS_Crypto extension
 * 
 * @category       BS
 * @package        BS_Crypto
 * @copyright      Copyright (c) 2018
 */
/**
 * Frontend observer
 *
 * @category    BS
 * @package     BS_Crypto
 * @author Bui Phong
 */
class BS_Crypto_Model_Observer
{
    /**
     * add items to main menu
     *
     * @access public
     * @param Varien_Event_Observer $observer
     * @return array()
     * @author Bui Phong
     */
    public function addItemsToTopmenuItems($observer)
    {
        $menu = $observer->getMenu();
        $tree = $menu->getTree();
        $action = Mage::app()->getFrontController()->getAction()->getFullActionName();

        $investmentNodeId = 'investment';
        $data = [
            'name' => Mage::helper('bs_crypto')->__('My Investments'),
            'id' => $investmentNodeId,
            'url' => Mage::helper('bs_crypto/investment')->getInvestmentsUrl(),
            'is_active' => ($action == 'bs_crypto_investment_index' || $action == 'bs_crypto_investment_view')
        ];
        $investmentNode = new Varien_Data_Tree_Node($data, 'id', $tree, $menu);
        $menu->addChild($investmentNode);
        /*$customerNodeId = 'customer';
        $data = [
            'name' => Mage::helper('bs_crypto')->__('Customers'),
            'id' => $customerNodeId,
            'url' => Mage::helper('bs_crypto/customer')->getCustomersUrl(),
            'is_active' => ($action == 'bs_crypto_customer_index' || $action == 'bs_crypto_customer_view')
        ];
        $customerNode = new Varien_Data_Tree_Node($data, 'id', $tree, $menu);
        $menu->addChild($customerNode);
        $periodNodeId = 'period';
        $data = [
            'name' => Mage::helper('bs_crypto')->__('Investment Periods'),
            'id' => $periodNodeId,
            'url' => Mage::helper('bs_crypto/period')->getPeriodsUrl(),
            'is_active' => ($action == 'bs_crypto_period_index' || $action == 'bs_crypto_period_view')
        ];
        $periodNode = new Varien_Data_Tree_Node($data, 'id', $tree, $menu);
        $menu->addChild($periodNode);
        $investmentNodeId = 'investment';
        $data = [
            'name' => Mage::helper('bs_crypto')->__('Investments'),
            'id' => $investmentNodeId,
            'url' => Mage::helper('bs_crypto/investment')->getInvestmentsUrl(),
            'is_active' => ($action == 'bs_crypto_investment_index' || $action == 'bs_crypto_investment_view')
        ];
        $investmentNode = new Varien_Data_Tree_Node($data, 'id', $tree, $menu);
        $menu->addChild($investmentNode);*/
        return $this;
    }

    public function doBeforeBlockToHtml($observer){

        $block = $observer->getBlock();
        $massactionClass  = Mage::getConfig()->getBlockClassName('adminhtml/widget_grid_massaction');
        $gridClass = Mage::getConfig()->getBlockClassName('adminhtml/widget_grid');
        $blockType = $block->getType();




        //Edit block: Save/Submit/Close buttons handler
        if($blockType == "adminhtml/customer_edit"){//like bs_sur/adminhtml_sur_edit
            $alias = $block->getBlockAlias();
            $alias = explode('_', $alias);
            $currentType = $alias[0];


            $customerId = $block->getRequest()->getParam('id');

            if($customerId > 0){
                $block->addButton(
                    'new_investment',
                    [
                        'label'   => Mage::helper('bs_crypto')->__('Add New Investment'),
                        'onclick'   => 'setLocation(\'' . $block->getUrl('*/crypto_investment/new', ['customer'=>$customerId]) .'\')',
                        'class'   => 'add',
                    ]
                );
            }




        }
    }

    public function modelSaveBefore($observer){
        $obj = $observer->getObject();
        $isNew = $obj->isObjectNew();

        $resourceName = $obj->getResourceName();

        $id = $obj->getId();
        $type = end(explode("/", $resourceName));


        $data = [];
        if($type == 'investment'){
            //calculate percent
            /*$periodId = $obj->getPeriodId();
            $period = Mage::getModel('bs_crypto/period')->load($periodId);
            $total = $period->getTotal();

            $initValue = $obj->getInitValue();

            $percent = number_format($initValue * 100 / $total, 2);

            $data['percent'] = $percent;*/

            if($isNew){
                $data['name'] = Mage::helper('bs_crypto')->getNextInvestmentNo();

            }

            $obj->addData($data);
        }

    }
}
