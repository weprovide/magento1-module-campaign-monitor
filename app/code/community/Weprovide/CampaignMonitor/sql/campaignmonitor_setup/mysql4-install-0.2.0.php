<?php

$installer = $this;
$installer->startSetup();
$installer->run("ALTER TABLE {$this->getTable('newsletter_subscriber')} ADD (campaign_monitor_imported SMALLINT(6) NOT NULL DEFAULT '0');");
$installer->endSetup();
