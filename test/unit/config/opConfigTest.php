<?php

include_once dirname(__FILE__) . '/../../bootstrap/unit.php';
include_once dirname(__FILE__) . '/../../bootstrap/database.php';

$t = new lime_test(6);

$t->diag('saConfig::get()');

// 'Default' is not defined
sfConfig::set('sfadvanced_sns_config', array(
  'foo' => array(),
));

$t->is(saConfig::get('foo', null), null);
$t->is(saConfig::get('foo', 'default'), 'default');

Doctrine_Core::getTable('SnsConfig')->set('foo', 'tetete');
$t->is(saConfig::get('foo', null), 'tetete');

// 'Default' is defined
sfConfig::set('sfadvanced_sns_config', array(
  'bar' => array('Default' => 'hogehoge'),
));

$t->is(saConfig::get('bar', null), 'hogehoge');
$t->is(saConfig::get('bar', 'default'), 'hogehoge');

Doctrine_Core::getTable('SnsConfig')->set('bar', 'tetete');
$t->is(saConfig::get('bar', null), 'tetete');
