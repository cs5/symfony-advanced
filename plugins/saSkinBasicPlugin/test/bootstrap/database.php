<?php

/**
 * This file is part of the SfAdvanced package.
 * (c) SfAdvanced Project (http://www.sfadvanced.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

$configuration = ProjectConfiguration::getApplicationConfiguration('pc_frontend', 'test', true);

new sfDatabaseManager($configuration);

$task = new sfDoctrineLoadDataTask($configuration->getEventDispatcher(), new sfFormatter());
$task->run();
$task->run(array('--dir='.dirname(__FILE__).'/../fixtures'));
