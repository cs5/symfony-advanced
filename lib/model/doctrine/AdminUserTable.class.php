<?php

/**
 * This file is part of the SfAdvanced package.
 * (c) SfAdvanced Project (http://www.sfadvanced.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class AdminUserTable extends Doctrine_Table
{
  //TODO: use findOneByUsername()
  public function retrieveByUsername($username)
  {
    return $this->createQuery()
      ->where('username = ?', $username)
      ->fetchOne();
  }

  //TODO: use findAll()
  public function retrievesAll()
  {
    return $this->createQuery()->execute();
  }
}
