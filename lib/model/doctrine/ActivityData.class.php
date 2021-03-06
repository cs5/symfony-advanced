<?php

/**
 * This file is part of the SfAdvanced package.
 * (c) SfAdvanced Project (http://www.sfadvanced.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * ActivityData
 *
 * This class has been auto-generated by the Doctrine ORM Framework
 *
 * @package    SfAdvanced
 * @subpackage model
 * @author     Shogo Kawahara <kawahara@bucyou.net>
 * @version    SVN: $Id: Builder.php 6820 2009-11-30 17:27:49Z jwage $
 */
class ActivityData extends BaseActivityData
{
  public function getPublicFlagCaption()
  {
    return $this->getTable()->publicFlagToCaption($this->getPublicFlag());
  }

  public function getReplies($publicFlag = ActivityDataTable::PUBLIC_FLAG_SITE, $limit = 10)
  {
    $query = $this->getTable()->createQuery('a')
      ->leftJoin('a.Member')
      ->addWhere('a.in_reply_to_activity_id = ?', $this->getId())
      ->orderBy('a.id')
      ->limit($limit);

    if (is_array($publicFlag))
    {
      $query->andWhereIn('a.public_flag', $publicFlag);
    }
    else
    {
      $query->andWhere('a.public_flag = ?', $publicFlag);
    }

    return $query->execute();
  }
}
