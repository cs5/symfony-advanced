<?php

include_once dirname(__FILE__).'/../../bootstrap/unit.php';
include_once dirname(__FILE__) . '/../../bootstrap/database.php';
$_SERVER['REQUEST_URI']  = '/';
$_SERVER['HTTP_HOST']    = 'site.example.com';
$_SERVER['SCRIPT_NAME']  = '/index.php';
$_SERVER['HTTP_REFERER'] = 'http://site.example.com/';

sfContext::createInstance($configuration);
saToolkit::clearCache();
include_once dirname(__FILE__) . '/../model/doctrine/fixtures/TestActivityTemplateConfigHandler.php';
include_once sfConfig::get('sf_lib_dir').'/vendor/symfony/lib/helper/HelperHelper.php';
use_helper('I18N', 'Tag', 'Url', 'saUtil', 'saActivity');

$t = new lime_test(18, new lime_output_color());

$t->diag('sa_activity_body_filter()');
$activity1 = new ActivityData();
$activity1->body = 'foo';

$activity2 = new ActivityData();
$activity2->template = 'test_template';
$activity2->template_param = array('%foo%' => 'bar');

$activity3 = new ActivityData();
$activity3->template = 'xxxx_template';

$activity4 = new ActivityData();
$activity4->body = 'http://www.sfadvanced.jp';

$activity5 = new ActivityData();
$activity5->body = 'http://www.sfadvanced.jp';
$activity5->uri = '@homepage';

$t->is(sa_activity_body_filter($activity1), 'foo', 'sa_activity_body_filter() returns "foo"');
$t->is(sa_activity_body_filter($activity2), 'Test test A test, bar!!!', 'sa_activity_body_filter() returns "Test test A test, bar!!!"');
$t->is(sa_activity_body_filter($activity3), '', 'sa_activity_body_filter() returns ""');
$t->is(sa_activity_body_filter($activity4), '<a href="http://www.sfadvanced.jp" target="_blank">http://www.sfadvanced.jp</a>', 'sa_activity_body_filter() returns autolinked text');
$t->is(sa_activity_body_filter($activity4, false), 'http://www.sfadvanced.jp', 'sa_activity_body_filter() returns "http//www.sfadvanced.jp"');
$t->is(sa_activity_body_filter($activity5), '<a href="/index.php/">http://www.sfadvanced.jp</a>', 'sa_activity_body_filter() returns linked text by uri of ActivityData');

sfConfig::set('sf_app', 'mobile_frontend');

$t->is(sa_activity_body_filter($activity4), '<a href="http://site.example.com/proxy?url=http%3A%2F%2Fwww.sfadvanced.jp">http://www.sfadvanced.jp</a>', 'sa_activity_body_filter() returns autolinked text');

function test_filter(sfEvent $event, $value)
{
  return '';
}

sfContext::getInstance()->getEventDispatcher()->connect('sa_activity.filter_body', 'test_filter');
$t->is(sa_activity_body_filter($activity1),  '', 'sa_activity_body_filter() returns ""');

$t->diag('sa_activity_image_uri() [file_id]');

$activityImage = new ActivityImage();
$activityImage->File->fromArray(array(
  'name' => 'ac_hogehoge_png',
  'type' => 'image/png',
));
$t->is(sa_activity_image_uri($activityImage), '/cache/img/png/w_h/ac_hogehoge_png.png');
$t->is(sa_activity_image_uri($activityImage, array('size' => '48x48')), '/cache/img/png/w48_h48/ac_hogehoge_png.png');
$t->is(sa_activity_image_uri($activityImage, array(), true), 'http://site.example.com/cache/img/png/w_h/ac_hogehoge_png.png');

$t->diag('sa_activity_image_uri() [uri]');

$activityImage = new ActivityImage();
$activityImage->fromArray(array(
  'uri' => 'http://example.com/images/hogehoge.png',
  'mimetype' => 'image/png',
));
$t->is(sa_activity_image_uri($activityImage), 'http://example.com/images/hogehoge.png');
$t->is(sa_activity_image_uri($activityImage, array('size' => '48x48')), 'http://example.com/images/hogehoge.png');
$t->is(sa_activity_image_uri($activityImage, array(), true), 'http://example.com/images/hogehoge.png');

$t->diag('sa_activity_image_tag() [file_id]');

$activityImage = new ActivityImage();
$activityImage->File->fromArray(array(
  'name' => 'ac_hogehoge_png',
  'type' => 'image/png',
));
$t->is(sa_activity_image_tag($activityImage), '<img alt="" src="/cache/img/png/w_h/ac_hogehoge_png.png" />');
$t->is(sa_activity_image_tag($activityImage, array('size' => '48x48')), '<img alt="" src="/cache/img/png/w48_h48/ac_hogehoge_png.png" />');

$t->diag('sa_activity_image_tag() [uri]');

$activityImage = new ActivityImage();
$activityImage->fromArray(array(
  'uri' => 'http://example.com/images/hogehoge.png',
  'mimetype' => 'image/png',
));
$t->is(sa_activity_image_tag($activityImage), '<img src="http://example.com/images/hogehoge.png" />');
$t->is(sa_activity_image_tag($activityImage, array('size' => '48x48')), '<img src="http://example.com/images/hogehoge.png" height="48" width="48" />');
