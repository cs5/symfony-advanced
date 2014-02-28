<?php

include_once dirname(__FILE__) . '/../../../bootstrap/unit.php';
include_once dirname(__FILE__) . '/../../../bootstrap/database.php';

$t = new lime_test(5, new lime_output_color());

$table = Doctrine::getTable('SiteConfig');

//------------------------------------------------------------
$t->diag('SiteConfigTable');
$t->diag('SiteConfigTable::retrieveByName()');
$siteConfig = $table->retrieveByName('site_name');
$t->isa_ok($siteConfig, 'SiteConfig');

//------------------------------------------------------------
$t->diag('SiteConfigTable::get()');
$t->is($table->get('site_name'), 'test1');
$t->is($table->get('site_name', 'xxx'), 'test1');
$t->is($table->get('xxx', 'xxx'), 'xxx');

//------------------------------------------------------------
$t->diag('SiteConfigTable::set()');
$table->set('foo', 'bar');
$t->is($table->get('foo'), 'bar');
