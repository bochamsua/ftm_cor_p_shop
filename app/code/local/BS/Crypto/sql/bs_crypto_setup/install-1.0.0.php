<?php
/**
 * BS_Crypto extension
 * 
 * @category       BS
 * @package        BS_Crypto
 * @copyright      Copyright (c) 2018
 */
/**
 * Crypto module install script
 *
 * @category    BS
 * @package     BS_Crypto
 * @author Bui Phong
 */
$this->startSetup();
$table = $this->getConnection()
    ->newTable($this->getTable('bs_crypto/account'))
    ->addColumn(
        'entity_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        [
            'identity'  => true,
            'nullable'  => false,
            'primary'   => true,
        ],
        'Account ID'
    )
    ->addColumn(
        'name',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(
            'nullable'  => false,
        ),
        'Account Name'
    )
    ->addColumn(
        'exchange',
        Varien_Db_Ddl_Table::TYPE_INTEGER, null,
        array(
            'nullable'  => false,
        ),
        'Exchange'
    )
    ->addColumn(
        'api_key',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(
            'nullable'  => false,
        ),
        'API Key'
    )
    ->addColumn(
        'api_secret',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(
            'nullable'  => false,
        ),
        'API Secret'
    )
    ->addColumn(
        'read_only',
        Varien_Db_Ddl_Table::TYPE_SMALLINT, null,
        array(),
        'Read Only?'
    )
    ->addColumn(
        'note',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(),
        'Note'
    )
    ->addColumn(
        'status',
        Varien_Db_Ddl_Table::TYPE_SMALLINT, null,
        array(),
        'Enabled'
    )
    ->addColumn(
        'updated_at',
        Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
        null,
        [],
        'Account Modification Time'
    )
    ->addColumn(
        'created_at',
        Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
        null,
        [],
        'Account Creation Time'
    ) 
    ->setComment('Account Table');
$this->getConnection()->createTable($table);
$table = $this->getConnection()
    ->newTable($this->getTable('bs_crypto/period'))
    ->addColumn(
        'entity_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        [
            'identity'  => true,
            'nullable'  => false,
            'primary'   => true,
        ],
        'Investment Period ID'
    )
    ->addColumn(
        'account_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER, null,
        array(
            'unsigned'  => true,
        ),
        'Account ID'
    )
    ->addColumn(
        'name',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(
            'nullable'  => false,
        ),
        'Name'
    )
    ->addColumn(
        'code',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(),
        'Code'
    )
    ->addColumn(
        'start_date',
        Varien_Db_Ddl_Table::TYPE_DATETIME, 255,
        array(
            'nullable'  => false,
        ),
        'Start Date'
    )
    ->addColumn(
        'end_date',
        Varien_Db_Ddl_Table::TYPE_DATETIME, 255,
        array(
            'nullable'  => false,
        ),
        'End Date'
    )
    ->addColumn(
        'total',
        Varien_Db_Ddl_Table::TYPE_DECIMAL, '12,4',
        array(
            'nullable'  => false,
        ),
        'Total Investment'
    )
    ->addColumn(
        'total_final',
        Varien_Db_Ddl_Table::TYPE_DECIMAL, '12,4',
        array(),
        'Final Total Amount'
    )
    ->addColumn(
        'status',
        Varien_Db_Ddl_Table::TYPE_SMALLINT, null,
        array(),
        'Enabled'
    )
    ->addColumn(
        'updated_at',
        Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
        null,
        [],
        'Investment Period Modification Time'
    )
    ->addColumn(
        'created_at',
        Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
        null,
        [],
        'Investment Period Creation Time'
    ) 
    ->addIndex($this->getIdxName('bs_crypto/account', array('account_id')), array('account_id'))
    ->setComment('Investment Period Table');
$this->getConnection()->createTable($table);
$table = $this->getConnection()
    ->newTable($this->getTable('bs_crypto/investment'))
    ->addColumn(
        'entity_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        [
            'identity'  => true,
            'nullable'  => false,
            'primary'   => true,
        ],
        'Investment ID'
    )
    ->addColumn(
        'period_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER, null,
        array(
            'unsigned'  => true,
        ),
        'Investment Period ID'
    )
    ->addColumn(
        'name',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(
            'nullable'  => false,
        ),
        'Name'
    )
    ->addColumn(
        'init_value',
        Varien_Db_Ddl_Table::TYPE_DECIMAL, '12,4',
        array(
            'nullable'  => false,
        ),
        'Investment Value'
    )
    ->addColumn(
        'customer_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER, null,
        array(),
        'Customer'
    )
    ->addColumn(
        'status',
        Varien_Db_Ddl_Table::TYPE_SMALLINT, null,
        array(),
        'Enabled'
    )
    ->addColumn(
        'updated_at',
        Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
        null,
        [],
        'Investment Modification Time'
    )
    ->addColumn(
        'created_at',
        Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
        null,
        [],
        'Investment Creation Time'
    ) 
    ->addIndex($this->getIdxName('bs_crypto/period', array('period_id')), array('period_id'))
    ->setComment('Investment Table');
$this->getConnection()->createTable($table);
$this->endSetup();
