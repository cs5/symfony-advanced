<?php

include dirname(__FILE__).'/../../bootstrap/functional.php';

$browser = new saTestFunctional(new sfBrowser(), new lime_test(null, new lime_output_color()));

include dirname(__FILE__).'/../../bootstrap/database.php';

$browser->login('site@example.com', 'password');
$browser->setCulture('en');

$browser->get('/')
  ->with('user')->isAuthenticated()
;