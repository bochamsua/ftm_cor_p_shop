<?php
chdir(dirname(__FILE__));
require_once 'app/Mage.php';

umask(0);
Mage::app();

$storeId = '0';

$crypto = Mage::helper('bs_crypto');

$binanceInfo = $crypto->getAccountInfo(1);

print_r($binanceInfo);
















