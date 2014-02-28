<?php

include_once dirname(__FILE__) . '/../../../bootstrap/unit.php';
include_once dirname(__FILE__) . '/../../../bootstrap/database.php';

$t = new lime_test(5, new lime_output_color());

$table = Doctrine::getTable('SnsConfig');

//------------------------------------------------------------
$t->diag('SnsConfigTable');
$t->diag('SnsConfigTable::retrieveByName()');
$siteConfig = $table->retrieveByName('site_name');
$t->isa_ok($siteConfig, 'SnsConfig');

//------------------------------------------------------------
$t->diag('SnsConfigTable::get()');
$t->is($table->get('site_name'), 'test1');
$t->is($table->get('site_name', 'xxx'), 'test1');
$t->is($table->get('xxx', 'xxx'), 'xxx');

//------------------------------------------------------------
$t->diag('SnsConfigTable::set()');
$table->set('foo', 'bar');
$t->is($table->get('foo'), 'bar');
