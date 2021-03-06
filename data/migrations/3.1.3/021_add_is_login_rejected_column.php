<?php

/**
 * This file is part of the SfAdvanced package.
 * (c) SfAdvanced Project (http://www.sfadvanced.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class Revision21_AddIsLoginRejectedColumn extends Doctrine_Migration_Base
{
  public function up()
  {
    $this->addColumn('member', 'is_login_rejected', 'boolean', null, array(
      'notnull' => 1,
      'default' => 0,
    ));

    // and try to fix community topic revision if the plugin is exists
    $conn = Doctrine_Manager::getInstance()->getConnectionForComponent('SiteConfig');
    $result = $conn->fetchOne('SELECT value FROM site_config WHERE name = ?', array('saCommunityTopicPlugin_revision'));
    if (!$result)
    {
      Doctrine::getTable('SiteConfig')->set('saCommunityTopicPlugin_revision', '4');
    }
  }

  public function down()
  {
    $this->removeColumn('member', 'is_login_rejected');
  }
}
