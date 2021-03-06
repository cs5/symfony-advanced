<?php

include(dirname(__FILE__).'/../../bootstrap/functional.php');

$browser = new saTestFunctional(new saBrowser(), new lime_test(null, new lime_output_color()));
$browser
  ->info('Login')
  ->login('site@example.com', 'password')

  ->info('/confirmation/friend_confirm/2 - CSRF')
  ->post('/confirmation/friend_confirm/2')
  ->checkCSRF()

  ->info('/confirmation/community_confirm/11 - CSRF')
  ->post('/confirmation/community_confirm/11')
  ->checkCSRF()

  ->login('site2@example.com', 'password')

  ->info('/confirmation/community_admin_request/5 - CSRF')
  ->post('/confirmation/community_admin_request/5')
  ->checkCSRF()

  ->login('site3@example.com', 'password')

  ->info('/confirmation/community_sub_admin_request/8 - CSRF')
  ->post('/confirmation/community_sub_admin_request/8')
  ->checkCSRF()

  ->login('html1@example.com', 'password')

  ->info('/confirmation?category=friend_confirm - XSS')
  ->get('/confirmation?category=friend_confirm')
  ->with('html_escape')->begin()
    ->isAllEscapedData('Member', 'name')
  ->end()

  ->info('/confirmation?category=community_confirm - XSS')
  ->get('/confirmation?category=community_confirm')
  ->with('html_escape')->begin()
    ->isAllEscapedData('Member', 'name')
    ->isAllEscapedData('Community', 'name')
  ->end()

  ->login('html2@example.com', 'password')

  ->info('/confirmation?category=community_admin_request - XSS')
  ->get('/confirmation?category=community_admin_request')
  ->with('html_escape')->begin()
    ->isAllEscapedData('Member', 'name')
    ->isAllEscapedData('Community', 'name')
  ->end()

  ->info('/confirmation?category=community_sub_admin_request - XSS')
  ->get('/confirmation?category=community_sub_admin_request')
  ->with('html_escape')->begin()
    ->isAllEscapedData('Member', 'name')
    ->isAllEscapedData('Community', 'name')
  ->end()
;
