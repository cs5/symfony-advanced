<?php

/**
 * This file is part of the SfAdvanced package.
 * (c) SfAdvanced Project (http://www.sfadvanced.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class Revision43_ChangeSessionDataColumnName extends Doctrine_Migration_Base
{
  public function up()
  {
    $this->renameColumn('session', 'data', 'session_data');
  }

  public function down()
  {
    $this->renameColumn('session', 'session_data', 'data');
  }
}
