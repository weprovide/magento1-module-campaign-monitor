<?php

$installer = $this;
$installer->startSetup();
$installer->run("ALTER TABLE {$this->getTable('newsletter_subscriber')} ADD (campaign_monitor_state VARCHAR(254) NOT NULL DEFAULT '');");
$installer->endSetup();
