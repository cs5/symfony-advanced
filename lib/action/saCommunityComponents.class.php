<?php

/**
 * This file is part of the SfAdvanced package.
 * (c) SfAdvanced Project (http://www.sfadvanced.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * saCommunityComponents
 *
 * @package    SfAdvanced
 * @subpackage action
 * @author     Shogo Kawahara <kawahara@tejimaya.net>
 * @author     Kousuke Ebihara <ebihara@php.net>
 */
abstract class saCommunityComponents extends sfComponents
{
  public function executeCautionAboutCommunityMemberPre(saWebRequest $request)
  {
    $memberId = sfContext::getInstance()->getUser()->getMemberId();

    $this->communityMembersCount = Doctrine::getTable('CommunityMember')->countCommunityMembersPre($memberId);
  }

  public function executeCautionAboutChangeAdminRequest(saWebRequest $request)
  {
    $this->communityCount = Doctrine::getTable('Community')->countPositionRequestCommunities('admin');
  }

  public function executeCautionAboutSubAdminRequest(saWebRequest $request)
  {
    $this->communityCount = Doctrine::getTable('Community')->countPositionRequestCommunities('sub_admin');
  }

}
