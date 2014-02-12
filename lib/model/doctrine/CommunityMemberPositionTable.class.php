<?php

/**
 * This file is part of the SfAdvanced package.
 * (c) SfAdvanced Project (http://www.sfadvanced.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * CommunityMemberPositionTable
 * 
 * @package    SfAdvanced
 * @subpackage model
 * @author     Shogo Kawahara <kawahara@tejimaya.net>
 * @author     Kimura Youichi <kim.upsilon@bucyou.net>
 */
class CommunityMemberPositionTable extends Doctrine_Table
{
  public function getPositionsByMemberIdAndCommunityId($memberId, $communityId)
  {
    $query = $this->createQuery()
      ->select('name')
      ->where('member_id = ?', $memberId)
      ->andWhere('community_id = ?', $communityId)
      ->setHydrationMode(Doctrine_Core::HYDRATE_NONE);

    $results = array();
    foreach ($query->execute() as $row)
    {
      $results[] = $row[0];
    }

    $query->free();

    return $results;
  }
}
