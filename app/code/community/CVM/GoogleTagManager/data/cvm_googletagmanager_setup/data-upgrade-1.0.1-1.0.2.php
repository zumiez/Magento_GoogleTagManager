<?php

/* @var $this Mage_Core_Model_Resource_Setup */
$installer = $this;
$installer->startSetup();
$installer->setConfigData('google/googletagmanager/active', 1);
$installer->setConfigData('google/googletagmanager/containerid', 'GTM-TS6FZ9');
$installer->setConfigData('google/googletagmanager/datalayertransactions', 1);
$installer->endSetup();