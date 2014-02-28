<?php

include_once dirname(__FILE__) . '/../../../bootstrap/unit.php';
include_once dirname(__FILE__) . '/../../../bootstrap/database.php';

$t = new lime_test(6, new lime_output_color());

$siteConfig1 = Doctrine::getTable('SiteConfig')->find(1);
$siteConfig2 = Doctrine::getTable('SiteConfig')->find(2);
$siteConfig3 = new SiteConfig();
$siteConfig3->setName('xxxxxxxxxx');

//------------------------------------------------------------
$t->diag('SiteConfig');
$t->diag('SiteConfig::getConfig()');
$t->isa_ok($siteConfig1->getConfig(), 'array');
$t->isa_ok($siteConfig2->getConfig(), 'array');
$t->ok(!$siteConfig3->getConfig());

//------------------------------------------------------------
$t->diag('SiteConfig::getValue()');
$t->is($siteConfig1->getValue(), 'test1');

//------------------------------------------------------------
$t->diag('SiteConfig::setValue()');
$siteConfig2->setValue(array('0', '1'));
$t->is($siteConfig2->getValue(), array('0', '1'));
$t->is($siteConfig2->rawGet('value'), serialize($siteConfig2->getValue()));
