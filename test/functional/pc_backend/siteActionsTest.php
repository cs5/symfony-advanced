<?php

include(dirname(__FILE__).'/../../bootstrap/functional.php');

$browser = new saTestFunctional(new sfBrowser(), new lime_test(null, new lime_output_color()));
$params = array('admin_user' => array(
));
$browser
  ->info('0. Login')
  ->get('/default/login')
  ->click('ログイン', array('admin_user' => array(
    'username' => 'admin',
    'password' => 'password',
  )))
  ->isStatusCode(302)

  ->info('1. When an admin user tries to change the Site configuration. (ref. #3488)')
  ->info('A category is not selected, admin user can change the general configuration.')
  ->get('/site/config')
  ->click('設定変更')
  ->isStatusCode(302)

  ->info('A general category is selected, admin user can change the general configuration.')
  ->get('/site/config/category/general')
  ->click('設定変更')
  ->isStatusCode(302)

  ->info('An authentication category is selected, admin user can change the authentication configuration.')
  ->get('/site/config/category/authentication')
  ->click('設定変更')
  ->isStatusCode(302)

  ->info('A mobile category is selected, admin user can change the mobile configuration.')
  ->get('/site/config/category/mobile')
  ->click('設定変更')
  ->isStatusCode(302)

  ->info('A policy category is selected, admin user can change the policy configuration.')
  ->get('/site/config/category/policy')
  ->click('設定変更')
  ->isStatusCode(302)

// CSRF
  ->info('/site/config - CSRF')
  ->post('/site/config')
  ->checkCSRF()

  ->info('/site/config/category/external_login_page - CSRF')
  ->post('/site/config/category/external_login_page')
  ->checkCSRF()

  ->info('/site/config/category/authentication - CSRF')
  ->post('/site/config/category/authentication')
  ->checkCSRF()

  ->info('/site/config/category/mobile - CSRF')
  ->post('/site/config/category/mobile')
  ->checkCSRF()

  ->info('/site/config/category/policy - CSRF')
  ->post('/site/config/category/policy')
  ->checkCSRF()

  ->info('/site/config/category/api_keys - CSRF')
  ->post('/site/config/category/api_keys')
  ->checkCSRF()

  ->info('/site/term - CSRF')
  ->post('/site/term')
  ->checkCSRF()

  ->info('/site/cache - CSRF')
  ->post('/site/cache')
  ->checkCSRF()

  ->info('/site/richTextarea - CSRF')
  ->post('/site/richTextarea')
  ->checkCSRF()

  ->info('/site/changeRichTextareaButtonOrder - CSRF')
  ->setHttpHeader('X_REQUESTED_WITH', 'XMLHttpRequest')
  ->post('/site/changeRichTextareaButtonOrder')
  ->checkCSRF()
;
