<?php

include_once dirname(__FILE__).'/../../../bootstrap/unit.php';
include_once dirname(__FILE__).'/../../../bootstrap/database.php';

$t = new lime_test(5, new lime_output_color());

$table = Doctrine::getTable('SiteTerm');
$table->configure('en');
//------------------------------------------------------------
$t->diag('SiteTerm');
$t->is((string)$table->findOneByName('friend'), 'friend');
$t->is((string)$table->findOneByName('friend')->withArticle(), 'a friend');
$t->is((string)$table->findOneByName('friend')->pluralize(), 'friends');
$t->is((string)$table->findOneByName('friend')->fronting(), 'Friend');
$t->is((string)$table->findOneByName('friend')->titleize(), 'Friend');
