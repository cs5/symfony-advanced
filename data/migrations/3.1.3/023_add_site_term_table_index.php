<?php

/**
 * This file is part of the SfAdvanced package.
 * (c) SfAdvanced Project (http://www.sfadvanced.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class Revision23_AddSnsTermTableIndex extends Doctrine_Migration_Base
{
  public function migrate($direction)
  {
    $this->foreignKey($direction, 'site_term_translation', 'site_term_translation_id_site_term_id', array(
      'name' => 'site_term_translation_id_site_term_id',
      'local' => 'id',
      'foreign' => 'id',
      'foreignTable' => 'site_term',
      'onUpdate' => 'CASCADE',
      'onDelete' => 'CASCADE',
    ));
    $this->index($direction, 'site_term_translation', 'site_term_translation_id', array(
      'fields' => array(0 => 'id'),
    ));
  }
}
