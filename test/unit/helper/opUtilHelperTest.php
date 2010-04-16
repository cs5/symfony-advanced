<?php

include_once dirname(__FILE__).'/../../bootstrap/unit.php';
include_once dirname(__FILE__) . '/../../bootstrap/database.php';

sfContext::createInstance(ProjectConfiguration::getApplicationConfiguration('pc_frontend', 'test', true));

include_once sfConfig::get('sf_lib_dir').'/vendor/symfony/lib/helper/HelperHelper.php';
use_helper('opUtil', 'Url', 'Tag');

$t = new lime_test(null, new lime_output_color());

//------------------------------------------------------------
$t->diag('cycle_vars()');
$t->is(cycle_vars('test', 'item1,item2'), 'item1');
$t->is(cycle_vars('test', 'item1,item2'), 'item2');
$t->is(cycle_vars('test', 'item1,item2'), 'item1');

//------------------------------------------------------------
$t->diag('op_format_last_login_time()');
$now = time();
$t->is(op_format_last_login_time($now - 2, $now), 'less than a minute ago');
$t->is(op_format_last_login_time($now - 8, $now), 'less than a minute ago');
$t->is(op_format_last_login_time($now - 13, $now), 'less than a minute ago');
$t->is(op_format_last_login_time($now - 25, $now), 'less than a minute ago');
$t->is(op_format_last_login_time($now - 49, $now), 'less than a minute ago');
$t->is(op_format_last_login_time($now - 60, $now), '1 minute ago');

$t->is(op_format_last_login_time($now - 10 * 60, $now), '10 minutes ago');
$t->is(op_format_last_login_time($now - 50 * 60, $now), 'about 1 hour ago');

$t->is(op_format_last_login_time($now - 3 * 3600, $now), 'about 3 hours ago');
$t->is(op_format_last_login_time($now - 25 * 3600, $now), '1 day ago');

$t->is(op_format_last_login_time($now - 4 * 86400, $now), '4 days ago');
$t->is(op_format_last_login_time($now - 35 * 86400, $now), 'about 1 month ago');
$t->is(op_format_last_login_time($now - 75 * 86400, $now), '3 months ago');

$t->is(op_format_last_login_time($now - 370 * 86400, $now), 'about 1 year ago');
$t->is(op_format_last_login_time($now - 4 * 370 * 86400, $now), 'over 4 years ago');
$t->is(op_format_last_login_time($now - 1000 * 86400, $now), 'over 2 years ago');

//------------------------------------------------------------
$t->diag('op_link_to_member()');
$t->is(op_link_to_member(1), link_to('A', '@obj_member_profile?id=1'), 'link to member 1');
$t->is(op_link_to_member(1, '@obj_friend_unlink'), link_to('A', '@obj_friend_unlink?id=1'), 'link to unlink member 1');
$t->is(op_link_to_member(1, '@obj_member_profile', array('link_target' => 'tetetete')), link_to('tetetete', '@obj_member_profile?id=1'), 'link to member 1 (free text)');
$t->is(op_link_to_member(9999), '-', 'set undefine member');
$t->is(op_link_to_member(null), '-', 'set null member');

Doctrine::getTable('SnsConfig')->set('nickname_of_member_who_does_not_have_credentials', 'I am a pen.');
$t->is(op_link_to_member(null), 'I am a pen.', 'set nickname_of_member_who_does_not_have_credentials original setting');
