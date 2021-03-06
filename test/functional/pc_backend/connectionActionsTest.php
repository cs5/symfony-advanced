<?php

include(dirname(__FILE__).'/../../bootstrap/functional.php');

$browser = new saTestFunctional(new sfBrowser(), new lime_test(null, new lime_output_color()));
$browser
  ->info('Login')
  ->get('/default/login')
  ->click('ログイン', array('admin_user' => array(
    'username' => 'admin',
    'password' => 'password',
  )))
  ->isStatusCode(302)

// CSRF
  ->info('/connection - CSRF')
  ->post('/connection')
  ->checkCSRF()

  ->info('/connection/1 - CSRF')
  ->post('/connection/1')
  ->checkCSRF()

  ->info('/connection/1/delete - CSRF')
  ->post('/connection/1/delete')
  ->checkCSRF()

  ->info('//connection/removeToken/id/1 - CSRF')
  ->post('/connection/removeToken/id/1')
  ->checkCSRF()
;
