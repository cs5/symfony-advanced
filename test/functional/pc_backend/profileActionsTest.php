<?php
include(dirname(__FILE__).'/../../bootstrap/functional.php');

$browser = new saTestFunctional(new saBrowser(), new lime_test(null, new lime_output_color()));
$browser
  ->info('Login')
  ->get('/')
  ->click('ログイン', array('admin_user' => array(
    'username' => 'admin',
    'password' => 'password',
  )))

// CSRF
  ->info('/profile/edit - CSRF')
  ->post('/profile/edit')
  ->checkCSRF()

  ->info('/profile/delete/id/1 - CSRF')
  ->post('/profile/delete/id/1')
  ->checkCSRF()

  ->info('/profile/sortProfile - CSRF')
  ->setHttpHeader('X_REQUESTED_WITH', 'XMLHttpRequest')
  ->post('/profile/sortProfile')
  ->checkCSRF()

  ->info('/profile/editOption - CSRF')
  ->post('/profile/editOption')
  ->checkCSRF()

  ->info('/profile/deleteOption/id/1 - CSRF')
  ->post('/profile/deleteOption/id/1')
  ->checkCSRF()

  ->info('/profile/sortProfileOption - CSRF')
  ->setHttpHeader('X_REQUESTED_WITH', 'XMLHttpRequest')
  ->post('/profile/sortProfileOption')
  ->checkCSRF()
;
