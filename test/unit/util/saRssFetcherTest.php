<?php

include_once dirname(__FILE__).'/../../bootstrap/unit.php';

$t = new lime_test(42, new lime_output_color());

//------------------------------------------------------------

class saRssFetcherMock extends saRssFetcher
{
  public function createSimplePieObject($fileName)
  {
    $filePath = self::calcFixtureFilePath($fileName);

    $feed = new SimplePie();
    $feed->set_file(new SimplePie_File($filePath));
    $feed->set_cache_location(sfConfig::get('sf_cache_dir'));
    if (!(@$feed->init()))
    {
      return false;
    }

    return $feed;
  }

  static public function calcFixtureFilePath($path)
  {
    return dirname(__FILE__).'/../../fixtures/feeds/'.$path;
  }
}

//------------------------------------------------------------
$t->diag('saRssFetcher');

$instance = new saRssFetcher('UTF-8');
$mock = new saRssFetcherMock('UTF-8');

$t->diag('->__construct()');
$t->is($instance->charset, 'UTF-8', '__construct() sets the specified character set to its property');

$t->diag('->createSimplePieObject()');
$t->isa_ok($instance->createSimplePieObject('http://www.sfadvanced.jp/'), 'SimplePie', '->createSimplePieObject() returns an instance of "SimplePie"');
$t->is($instance->createSimplePieObject('http://example.com/undefined.rss'), false, '->createSimplePieObject() returns false if the specified uri is 404');
$t->is($instance->createSimplePieObject('Invalid Format URI'), false, '->createSimplePieObject() returns false if the specified uri is wrong');

$t->diag('->getFeedTitle()');

$t->is($mock->getFeedTitle('www.sfadvanced.jp.feed.rss'), 'SfAdvanced', '->getFeedTitle() returns RSS feed title');
$t->is($mock->getFeedTitle('www.sfadvanced.jp.feed.atom'), 'SfAdvanced', '->getFeedTitle() returns Atom feed title');
$t->is($instance->getFeedTitle('http://example.com/undefined.rss'), false, '->getFeedTitle() returns false if the specified uri is 404');

$t->diag('->getFeedDescription()');

$t->is($mock->getFeedDescription('www.sfadvanced.jp.feed.rss'), 'オープンソースのSiteエンジン SfAdvancedプロジェクト', '->getFeedTitle() returns RSS feed description');
$t->is($mock->getFeedDescription('www.sfadvanced.jp.feed.atom'), 'オープンソースのSiteエンジン SfAdvancedプロジェクト', '->getFeedTitle() returns Atom feed description');
$t->is($instance->getFeedDescription('http://example.com/undefined.rss'), false, '->getFeedTitle() returns false if the specified uri is 404');

$t->diag('->fetch()');
$result = $mock->fetch('www.sfadvanced.jp.feed.rss');
$t->is(count($result), 10, '->fetch() for www.sfadvanced.jp is returns array that contains recently 10 RSS entries');
$t->is($result[0]['title'], 'SfAdvanced 3.2RC1 リリースのお知らせ', '->fetch() returns RSS entries that contains valid title');
$t->is($result[1]['title'], '', '->fetch() returns RSS entries that contains empty title');
$t->is($result[0]['body'], 'SfAdvanced 開発チームの海老原です。
				本日 2009/11/30（月）、 開発版 SfAdvanced 3.2RC1 をリリースしました。
				今後の sfAdvanced1 のリリーススケジュール にて発表 [...]', '->fetch() returns RSS entries that contains valid body');
$t->is($result[4]['body'], '<p>開発チームの海老原です。</p>
				<p>11/23 に <a href="http://www.sfadvanced.jp/archives/3931/">saAlbumPlugin のアップデート</a>が、 11/24 に <a href="http://www.sfadvanced.jp/archives/3938/">saDiaryPlugin のアップデート</a>がありました。</p>
				<p>どちらも重要なバグフィックスがおこなわれたリリースのため、 SfAdvanced 3.1.5 にバンドルされているプラグインも、新しく更新されたバージョンに変更しました。</p>
				<p>以下のコマンドを実行することで、更新された saAlbumPlugin と saDiaryPlugin が利用できます。<br />
				<code>./symfony sfadvanced:migrate</code></p>', '->fetch() returns RSS entries that contains valid content');
$t->is($result[5]['body'], '', '->fetch() returns RSS entries that contains empty body');
$t->is($result[0]['link'], 'http://www.sfadvanced.jp/archives/3988/', '->fetch() returns RSS entries that contains valid link');
$t->is($result[2]['link'], '', '->fetch() returns RSS entries that contains empty link');
$t->is($result[0]['date'], '2009-11-30 01:16:36', '->fetch() returns RSS entries that contains valid date');
$t->is($result[6]['date'], '', '->fetch() returns RSS entries that contains empty date');
$t->isa_ok($result[0]['enclosure'], 'SimplePie_Enclosure', '->fetch() returns RSS entries that contains valid enclosure');
$t->is($result[7]['enclosure'], '', '->fetch() returns RSS entries that contains empty enclosure');

$result = $mock->fetch('www.sfadvanced.jp.feed.atom');
$t->is(count($result), 10, '->fetch() for www.sfadvanced.jp is returns array that contains recently 10 Atom entries');
$t->is($result[0]['title'], 'SfAdvanced 3.2RC1 リリースのお知らせ', '->fetch() returns Atom entries that contains valid title');
$t->is($result[1]['title'], '', '->fetch() returns Atom entries that contains empty title');
$t->is($result[0]['body'], 'SfAdvanced 開発チームの海老原です。
				本日 2009/11/30（月）、 開発版 SfAdvanced 3.2RC1 をリリースしました。
				今後の sfAdvanced1 のリリーススケジュール にて発表 [...]', '->fetch() returns Atom entries that contains valid body');
$t->is($result[4]['body'], '<p>開発チームの海老原です。</p>
				<p>11/23 に <a href="http://www.sfadvanced.jp/archives/3931/">saAlbumPlugin のアップデート</a>が、 11/24 に <a href="http://www.sfadvanced.jp/archives/3938/">saDiaryPlugin のアップデート</a>がありました。</p>
				<p>どちらも重要なバグフィックスがおこなわれたリリースのため、 SfAdvanced 3.1.5 にバンドルされているプラグインも、新しく更新されたバージョンに変更しました。</p>
				<p>以下のコマンドを実行することで、更新された saAlbumPlugin と saDiaryPlugin が利用できます。<br />
				<code>./symfony sfadvanced:migrate</code></p>', '->fetch() returns Atom entries that contains valid content');
$t->is($result[5]['body'], '', '->fetch() returns Atom entries that contains empty body');
$t->is($result[0]['link'], 'http://www.sfadvanced.jp/archives/3988/', '->fetch() returns Atom entries that contains valid link');
$t->is($result[2]['link'], '', '->fetch() returns Atom entries that contains empty link');
$t->is($result[0]['date'], '2009-11-30 01:16:36', '->fetch() returns Atom entries that contains valid date');
$t->is($result[6]['date'], '2009-11-23 19:57:27', '->fetch() returns Atom entries that contains updated date');
$t->is($result[8]['date'], '', '->fetch() returns Atom entries that contains empty date');
$t->isa_ok($result[0]['enclosure'], 'SimplePie_Enclosure', '->fetch() returns Atom entries that contains valid enclosure');
$t->is($result[7]['enclosure'], '', '->fetch() returns Atom entries that contains empty enclosure');
$result = $mock->fetch('www.sfadvanced.jp.feed.rss', true);
$t->is($result[0], 'SfAdvanced', '->fetch() returns also RSS feed title if the second parameter is true');
$result = $mock->fetch('www.sfadvanced.jp.feed.atom', true);
$t->is($result[0], 'SfAdvanced', '->fetch() returns also Atom feed title if the second parameter is true');
$t->is($instance->fetch('http://example.com/undefined.rss'), false, '->fetch() returns false if the specified uri is 404');
$t->is($mock->fetch('www.sfadvanced.jp.feed.empty.rss'), false, '->fetch() returns false if the specified feed does not have any entries');
$t->is($mock->fetch('www.sfadvanced.jp.feed.empty.rss', true), false, '->fetch() returns false if the specified feed does not have any entries even if the second parameter is specified');

$t->diag('::autoDiscovery');
$url = saRssFetcher::autoDiscovery(saRssFetcherMock::calcFixtureFilePath('www.sfadvanced.jp.html'));
$t->is($url, 'http://www.sfadvanced.jp/feed/', '::autoDiscovery() returns a valid feed url');
$url = saRssFetcher::autoDiscovery(saRssFetcherMock::calcFixtureFilePath('www.co3k.org.html'));
$t->is($url, '', '::autoDiscovery() returns an empty string if the specified uri does not have related feeds');

